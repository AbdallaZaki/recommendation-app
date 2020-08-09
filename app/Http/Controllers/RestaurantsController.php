<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Restaurant\RestaurantRepositoryInterface;
use App\Http\Requests\SearchMealsRequest;

class RestaurantsController extends Controller
{
    private $restaurantRepository;

    public function __construct(RestaurantRepositoryInterface $restaurantRepository)
    {
        $this->restaurantRepository = $restaurantRepository;
    }

    public function showSearchForm()
    {
        //return view("search");
        //return $this->restaurantRepository->maxDistanceOfRestaurants('meal',30.012647,31.210113);
        //return $this->restaurantRepository->maxSuccessfulOrdersForRestaurants('meal');
        //return $this->restaurantRepository->maxCustomerRecommendationCountForRestaurants('meal z');
        //return $this->restaurantRepository->maxCustomerMealRecommendationCountForRestaurants('meal z');
        return $this->restaurantRepository->searchForMeal('meal z',30.012647,31.210113);
    }

    public function searchForRestaurants(SearchMealsRequest $request)
    {   
        
        list($lat,$lan) = explode(',',$request->map_coordinates);
    
        $restaurants = $this->restaurantRepository->searchForMeal($request->meal_name,$lat,$lan);

        return view('search',compact('restaurants'));
    }

}
