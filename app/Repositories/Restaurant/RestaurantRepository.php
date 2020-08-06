<?php 

namespace App\Repositories\Restaurant;

use App\Models\Restaurant;
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
        $query = $this->joinMeals($query);
        $query = $this->searchMeals($query,$mealName);
        $distanceRaw = $query->orderBy('distance','DESC')->limit(1)->first();
        return isset($distanceRaw->distance)?$distanceRaw->distance:false;
    }


    public function maxSuccessfulOrdersForRestaurants(string $mealName):int{
        
        $query = $this->model->select(\DB::raw("max(successful_orders) as maxSuccessfulOrdersCount"));
        $query = $this->joinMeals($query);
        $query = $this->searchMeals($query,$mealName);
        $maxSuccessfulOrdersRaw = $query->orderBy('maxSuccessfulOrdersCount','DESC')->limit(1)->first();
        return isset($maxSuccessfulOrdersRaw->maxSuccessfulOrdersCount)?
        $maxSuccessfulOrdersRaw->maxSuccessfulOrdersCount:false;
    }

    public function maxCustomerRecommendationCountForRestaurants(string $mealName):int{
        
        $query = $this->model->select(\DB::raw("max(customer_recommendation_count) as maxCustomerRecommendationCount "));
        $query = $this->joinMeals($query);
        $query = $this->searchMeals($query,$mealName);
        $maxCustomerRecommendationRaw = $query->orderBy('maxCustomerRecommendationCount','DESC')->limit(1)->first();
        return isset($maxCustomerRecommendationRaw->maxCustomerRecommendationCount)?
        $maxCustomerRecommendationRaw->maxCustomerRecommendationCount:false;
    }


    public function maxCustomerMealRecommendationCountForRestaurants(string $mealName):int{
        
        $query = $this->model->select(\DB::raw("
        max((select meal_recommendation_count from meal_restaurant
        where meal_restaurant.meal_id = meals.id 
        and restaurants.id = meal_restaurant.restaurant_id  ) ) as 
        maxCustomerMealRecommendationCount"));
        $query = $this->joinMeals($query);
        $query = $this->searchMeals($query,$mealName);
        $maxCustomerMealRecommendationRaw = $query->orderBy('maxCustomerMealRecommendationCount','DESC')->limit(1)->first();
        return isset($maxCustomerMealRecommendationRaw->maxCustomerMealRecommendationCount)?
        $maxCustomerMealRecommendationRaw->maxCustomerMealRecommendationCount:false;
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

        $query = \DB::raw("
            select
            id,
            avg(customer_meal_recommendation_count_rank) as customer_meal_recommendation_count_rank,
            dist_rank,
            successful_orders_rank,
            customer_recommendation_count_rank,
            restaurant_name 
            from
                (
                select
            ( (1 - (getDistance({$latitude},{$longitude}, latitude, longitude) / {$maxDistance}))*{$this->distanceWeight}) as dist_rank,
                    (
            (successful_orders / {$maxSuccessfulOrders})*{$this->successfulOrdersWeight} 
                    )
                    as successful_orders_rank,
                    (
            (customer_recommendation_count / {$maxCustomerRecommendationCount})*{$this->customerRecommendationCountWeight} 
                    )
                    as customer_recommendation_count_rank,
                    (
            (( 
                    select
                        meal_recommendation_count 
                    from
                        meal_restaurant 
                    where
                        meal_restaurant.meal_id = meals.id 
                        and meal_restaurant.restaurant_id = `restaurants`.`id` ) / {$maxCustomerMealRecommendationCount}) * {$this->customerMealRecommendationCountWeight}
                )
                as customer_meal_recommendation_count_rank,
                restaurants.* 
            from
                `restaurants` 
                inner join
                    `meal_restaurant` 
                    on `restaurants`.`id` = `meal_restaurant`.`restaurant_id` 
                inner join
                    `meals` 
                    on `meal_id` = `meals`.`id` 
            where
                `meal_name` like '%{$mealName}%'
            )
            as restaurants_data

            group by id 
            order by
            dist_rank + 
            successful_orders_rank + 
            customer_recommendation_count_rank + 
            customer_meal_recommendation_count_rank DESC limit {$limit};
            
        ");
        
        return \DB::select($query);
    }

    private function joinMeals($query)
    {
        return $query->join('meal_restaurant','restaurant_id','=','restaurants.id')
        ->join('meals','meal_id','=','meals.id');
    }

    private function searchMeals($query, string $mealName){
        return $query->where('meal_name', 'like', '%' . $mealName . '%');
    }

    private function isValidRankParameters(array $parameters):bool
    {
        foreach ($parameters as $parameter) {
            if($parameter === false) return false;
        }
        return true;
    }
}