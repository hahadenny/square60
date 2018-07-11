<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /**
         * building_id	address	building_name	building_city
         * building_state	building_zip	building_units
         * building_stories	building_build_year	building_amenities

         */
        if (!Schema::hasTable('buildings')){
            Schema::create('buildings', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('building_id');
                $table->string('building_address');
                $table->string('building_name');
                $table->string('building_city');
                $table->string('building_state');
                $table->string('building_zip');
                $table->integer('building_units');
                $table->string('building_stories');
                $table->integer('building_build_year');
                $table->text('building_amenities');
                $table->text('building_description');
                $table->text('path_for_building_images_on_s3');
                $table->text('building_images');
                $table->timestamps();

                $table->unique('building_id','building_idx');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //

        Schema::dropIfExists('buildings');

    }
}
