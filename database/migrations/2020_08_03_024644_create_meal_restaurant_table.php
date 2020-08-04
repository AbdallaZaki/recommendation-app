<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealRestaurantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_restaurant', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurant_id');
            $table->unsignedBigInteger('meal_id');
            $table->integer('meal_recommendation_count');
            $table->decimal('price', 5, 2);
            $table->foreign('restaurant_id')->references('id')->on('restaurants');
            $table->foreign('meal_id')->references('id')->on('meals');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::create('meal_restaurant', function (Blueprint $table) {
           
            $table->dropForeign(['restaurant_id','meal_id']);

            Schema::dropIfExists('meal_restaurant');
        });
       
    }
}
