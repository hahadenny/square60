<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFiltersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('filters', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('label');
			$table->boolean('general')->default(0);
			$table->integer('parent_id')->default(0);
			$table->string('type');
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
		Schema::drop('filters');
	}

}
