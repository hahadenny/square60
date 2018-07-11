<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeEstatesFilters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('estate_filters');

        Schema::create('estate_filters', function (Blueprint $table) {
            $table->integer('estate_id');
            $filters = array(
                351,
                352,
                353,
                354,
                355,
                356,
                357,
                358,
                359,
                360,
                361,
                362,
                363,
                364,
                365,
                366,
                367,
                368,
                369,
                370,
                371,
                372,
                373,
                374,
                375,
                376,
                377,
                378,
                379,
                380,
                381,
            );
            foreach ($filters as $fId){
                $table->boolean('f_'.$fId);
            }
            $indexArr = array();
            foreach ($filters as $fId){
                $name = 'f_'.$fId;
                $indexArr[$name]= $name;
                $table->index($name,'idx_'.$name);
            }
            //$table->index(array_keys($indexArr),'filters_composite_idx');

            $table->primary('estate_id');
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
        Schema::dropIfExists('estate_filters');
    }
}
