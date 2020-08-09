<?php 

namespace App\Repositories\Restaurant;

use App\Models\Restaurant;
use App\Models\MealRestaurant;
use App\Repositories\Restaurant\RestaurantRepositoryInterface;

class RestaurantRepository implements RestaurantRepositoryInterface
{
    private $model;
    
    private $distanceWeight = 10;

    private $successfulOrdersWeight = 5;

    private $customerRecommendationCountWeight = 5;

    private $customerMealRecommendationCountWeight = 3;

    public function __construct(Restaurant $model)
    {
        $this->model = $model;
    }

    public function maxDistanceOfRestaurants(string $mealName,float $latitude, float $longitude):float{
        
        $query = $this->model->select(\DB::raw("getDistance({$latitude},{$longitude},latitude,longitude) as distance"));
        $query = $this->searchMeals($query, $mealName);
        $distanceRaw = $query->orderBy('distance','DESC')->limit(1)->first();
        return isset($distanceRaw->distance)?$distanceRaw->distance:false;
    }


    public function maxSuccessfulOrdersForRestaurants(string $mealName):int{
        
        $query = $this->searchMeals($this->model,$mealName);
        $maxSuccessfulOrders = $query->max('successful_orders');
        return $maxSuccessfulOrders?$maxSuccessfulOrders:false;
    }

    public function maxCustomerRecommendationCountForRestaurants(string $mealName):int{
        
        $query = $this->searchMeals($this->model,$mealName);
        $maxCustomerRecommendation = $query->max('customer_recommendation_count');
        return $maxCustomerRecommendation?$maxCustomerRecommendation:false;
    }


    public function maxCustomerMealRecommendationCountForRestaurants(string $mealName):int{
        
        $query = MealRestaurant::whereHas('meal',function($builder) use($mealName){
            $builder->where('meal_name', 'like', '%' . $mealName . '%');
        });
        $maxCustomerMealRecommendation = $query->max('meal_recommendation_count');
        return $maxCustomerMealRecommendation?$maxCustomerMealRecommendation:false;
    }

    public function searchForMeal(string $mealName,float $latitude, float $longitude,int $limit = 3)
    {
        $maxDistance = $this->maxDistanceOfRestaurants($mealName,$latitude,$longitude);

        $maxSuccessfulOrders = $this->maxSuccessfulOrdersForRestaurants($mealName);
        
        $maxCustomerRecommendationCount = $this->maxCustomerRecommendationCountForRestaurants($mealName);

        $maxCustomerMealRecommendationCount = $this->maxCustomerMealRecommendationCountForRestaurants($mealName);
        
        $validRanks = $this->isValidRankParameters([$maxDistance,
        $maxSuccessfulOrders,
        $maxCustomerRecommendationCount,
        $maxCustomerMealRecommendationCount]);
         
        // need to be updated to handle each parameter sepritly 
        if(!$validRanks) return collect([]);

        $customerMealRecommendationCountWeight= $this->customerMealRecommendationCountWeight;

        $selectQueries = [
            \DB::raw("( (1 - (getDistance({$latitude},{$longitude}, latitude, longitude) / {$maxDistance}))*{$this->distanceWeight}) as dist_rank"),
            \DB::raw("((successful_orders / {$maxSuccessfulOrders})*{$this->successfulOrdersWeight}) as successful_orders_rank"),
            \DB::raw("((customer_recommendation_count / {$maxCustomerRecommendationCount})*{$this->customerRecommendationCountWeight} ) as customer_recommendation_count_rank"),
            \DB::raw("restaurants.*"),
            
        ];
        
        $query = $this->model->select($selectQueries);
        $query->addSelect(['customer_meal_recommendation_count_rank' => function ($subQuery) use($mealName,$maxCustomerMealRecommendationCount,$customerMealRecommendationCountWeight) {
            $subQuery->select(\DB::raw("((avg(meal_recommendation_count)/{$maxCustomerMealRecommendationCount})*{$customerMealRecommendationCountWeight})"))
            ->from('meal_restaurant')
            ->join('meals','meal_id','=','meals.id')
            ->where('meal_name', 'like', '%' . $mealName . '%')
            ->whereColumn('meal_restaurant.meal_id', 'meals.id')
            ->whereColumn('meal_restaurant.restaurant_id', 'restaurants.id');
               
        }]);
        $query = $this->searchMeals($query,$mealName);
        $query = $query->orderByRaw(" dist_rank + 
        successful_orders_rank + 
        customer_recommendation_count_rank+
        customer_meal_recommendation_count_rank  DESC");
        return $query->limit($limit)->get();
    
    }

    private function searchMeals($query, string $mealName){
        return $query->whereHas('meals',function($builder) use($mealName){
            $builder->where('meal_name', 'like', '%' . $mealName . '%');
        });
    }

    private function isValidRankParameters(array $parameters):bool
    {
        foreach ($parameters as $parameter) {
            if($parameter === false) return false;
        }
        return true;
    }
}