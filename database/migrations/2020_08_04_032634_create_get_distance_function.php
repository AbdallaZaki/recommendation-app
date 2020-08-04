<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGetDistanceFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "
        DROP FUNCTION IF EXISTS getDistance; 
        CREATE FUNCTION `getDistance`(`lat1` VARCHAR(200), `lng1` VARCHAR(200), `lat2` VARCHAR(200), `lng2` VARCHAR(200)) RETURNS varchar(10) CHARSET utf8
        begin
            declare distance varchar(10);

            set distance = (select (6371 * acos( 
                            cos( radians(lat2) ) 
                        * cos( radians( lat1 ) ) 
                        * cos( radians( lng1 ) - radians(lng2) ) 
                        + sin( radians(lat2) ) 
                        * sin( radians( lat1 ) )
                            ) ) as distance); 

            if(distance is null)
            then
            return '';
            else 
            return distance;
            end if;   
        END;";

        \DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::unprepared('DROP FUNCTION IF EXISTS getDistance;');
    }
}
