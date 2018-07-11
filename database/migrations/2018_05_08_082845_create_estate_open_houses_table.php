<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstateOpenHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estate_open_houses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('listing_id');
            $table->date('date');
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->integer('appointment')->nullable();
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
        Schema::dropIfExists('estate_open_houses');
    }
}
