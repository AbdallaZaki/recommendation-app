<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecommendationRestaurantsSearchTest extends TestCase
{

    /**
     * search restaurant with right data 
     *
     * @return void
     */
    public function testSearchWithRightData()
    {
        $this->visit('/')
        ->type('meal', 'meal_name')
        ->type('30.012647,31.210113', 'map_coordinates')
        ->press('search')
        ->seePageIs('/search?map_coordinates=30.012647%2C31.210113&meal_name=meal');
       
    }

    /**
     * search restaurant with wrong data 
     *
     * @return void
     */
    public function testSearchWithWrongData()
    {
        $this->visit('/')
        ->type('meal', 'meal_name')
        ->type('30.012647_31.210113', 'map_coordinates')
        ->press('search')
        ->seePageIs('/');
       
    }

    /**
     * search restaurant with valid result returned 
     *
     * @return void
     */
    public function testSearchWithValidResultRetrned()
    {
        $this->visit('/')
        ->type('meal', 'meal_name')
        ->type('30.012647,31.210113', 'map_coordinates')
        ->press('search')
        ->see('Restaurant Name');
    }
}
