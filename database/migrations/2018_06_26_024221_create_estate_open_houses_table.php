<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEstateOpenHousesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('estate_open_houses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('listing_id')->index('listing_id');
			$table->date('date');
			$table->timestamp('start_time')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->dateTime('end_time');
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
		Schema::drop('estate_open_houses');
	}

}
