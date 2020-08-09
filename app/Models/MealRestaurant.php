<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MealRestaurant extends Pivot
{
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
}
