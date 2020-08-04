<?php 

namespace App\Repositories\Restaurant;

use App\Models\Restaurant;
use App\Repositories\Restaurant\RestaurantRepositoryInterface;

class RestaurantRepository implements RestaurantRepositoryInterface
{
    private $model;

    public function __construct(Restaurant $model)
    {
        $this->model = $model;
    }

    public function maxDistanceOfRestaurants(){
        
    }
}