<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Restaurant\RestaurantRepositoryInterface;

class RestaurantsController extends Controller
{
    private $restaurantRepository;

    public function __construct(RestaurantRepositoryInterface $restaurantRepository)
    {
        $this->restaurantRepository = $restaurantRepository;
    }

    public function searchForRestaurant()
    {   
        //return $this->restaurantRepository->maxDistanceOfRestaurants("meal",30.012647,31.210113);

        //return $this->restaurantRepository->maxSuccessfulOrdersForRestaurants("meal");

        //return $this->restaurantRepository->maxCustomerRecommendationCountForRestaurants("meal");
        
        //return $this->restaurantRepository->maxCustomerMealRecommendationCountForRestaurants("meal");

        return $this->restaurantRepository->searchForMeal("meal tt",30.012647,31.210113);
    }

}
