<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAgentsListingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('agents_listings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('listings_id');
			$table->integer('agent_id')->index('agent_id');
			$table->timestamps();
			$table->unique(['listings_id','agent_id'], 'listings_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('agents_listings');
	}

}
