<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAgentsBuildingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('agents_buildings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('building_id');
			$table->integer('agent_id')->index('agent_id');
			$table->timestamps();
			$table->unique(['building_id','agent_id'], 'building_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('agents_buildings');
	}

}
