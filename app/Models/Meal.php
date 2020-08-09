<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Restaurant;

class Meal extends Model
{   
    protected $fillable = [
        'meal_name'
    ];

    /**
     * Get the meal restaurants.
     */
    public function restaurant()
    {
        return $this->belongsToMany(Restaurant::class)->withPivot('meal_recommendation_count');
    }
}
