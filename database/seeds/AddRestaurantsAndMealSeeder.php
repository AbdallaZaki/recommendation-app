<?php

use Illuminate\Database\Seeder;
use App\Models\Meal;
use App\Models\Restaurant;

class AddRestaurantsAndMealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        //seed restaurants 

        $restaurants = [];
        
        for ($i=0; $i < 10; $i++) {

            $location = generateRandomLocation([
                config('map_settings.center_lat'),
                config('map_settings.center_lan')
            ], config('map_settings.search_radius'));
            
            $restaurant = [
                'restaurant_name' => "restaurant ".chr(rand(97,122)).rand(100,1000),              
                'successful_orders' => rand(50,1000),           
                'customer_recommendation_count' =>rand(50,1000), 
                'latitude' => $location['x'],                  
                'longitude' => $location['y']   
            ];
            
            $restaurants[] = Restaurant::create($restaurant);

        }
        

        // seed meals 
        $meals = [];

        for ($i=0; $i < 10; $i++) {

            $meal = [
                'meal_name' => "meal ".chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)),              
            ];
            
            $meals[] = Meal::create($meal);
        }

        
        // add restaurant to meal relation 

        foreach ($restaurants as $restaurant) {

            foreach ($meals as $meal) {

                $date = date('Y-m-d H:i:s'); 

                \DB::table('meal_restaurant')->insert([
                    'restaurant_id' => $restaurant->id,
                    'meal_id' =>  $meal->id,
                    'meal_recommendation_count' => rand(100,1000),
                    'price' => round(lcg_value()*rand(100,500),2),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
                
        }

        
        
    }
}
