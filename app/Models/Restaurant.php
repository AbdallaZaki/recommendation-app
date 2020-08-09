<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Meal;

class Restaurant extends Model
{   
    protected $fillable = [
        'restaurant_name',              
        'successful_orders',           
        'customer_recommendation_count',
        'latitude',                  
        'longitude'   
    ];

    /**
     * Get the restaurant meals.
     */
    public function meals()
    {
        return $this->belongsToMany(Meal::class)->withPivot('meal_recommendation_count');
    }
}
