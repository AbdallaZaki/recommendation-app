<?php 

namespace App\Repositories\Restaurant;

interface RestaurantRepositoryInterface
{
    public function maxDistanceOfRestaurants(string $mealName,float $latitude, float $longitude):float;

    public function maxSuccessfulOrdersForRestaurants(string $mealName):int;

    public function maxCustomerRecommendationCountForRestaurants(string $mealName):int;

    public function maxCustomerMealRecommendationCountForRestaurants(string $mealName):int;

    public function searchForMeal(string $mealName,float $latitude, float $longitude,int $limit = 3);

}
