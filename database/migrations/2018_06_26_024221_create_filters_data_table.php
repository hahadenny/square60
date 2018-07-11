<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFiltersDataTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('filters_data', function(Blueprint $table)
		{
			$table->increments('filter_data_id');
			$table->string('filter_id');
			$table->string('value');
			$table->integer('parent_id')->default(0);
			$table->integer('district_id')->nullable();
			$table->integer('sub_district_id')->default(0);
			$table->timestamps();
			$table->index(['filter_id','parent_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('filters_data');
	}

}
