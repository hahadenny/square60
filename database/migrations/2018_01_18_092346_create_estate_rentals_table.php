<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstateRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estate_rentals', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('active')->default('0');
            $table->string('name');
            $table->smallInteger('district_id');
            $table->string('district');
            $table->string('street_address');
            $table->smallInteger('apartment');
            $table->string('boro');
            $table->integer('ny_zip');
            $table->smallInteger('type_id');
            $table->string('type');
            $table->smallInteger('size');
            $table->smallInteger('bed');
            $table->smallInteger('bath');
            $table->smallInteger('total_room');
            $table->text('description');
            $table->string('web_link');
            $table->double('rent');
            $table->tinyInteger('fees');
            $table->tinyInteger('broker');
            $table->string('amenities_id', 10000);
            $table->string('year_built');
            $table->text('images');
            $table->text('floor_plan');
            $table->text('ex_agreement');
            $table->text('copy_licence');
            $table->text('un_bill');
            $table->tinyInteger('feature');
            $table->integer('user_id')->default('1')->unsigned();
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
        Schema::dropIfExists('estate_rentals');
    }
}
