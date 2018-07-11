<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAgentInfosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('agent_infos', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('last_name');
			$table->string('first_name');
			$table->string('photo');
			$table->string('photo_url');
			$table->string('company');
			$table->string('web_site');
			$table->string('office_phone');
			$table->string('fax');
			$table->text('description', 65535);
			$table->timestamps();
			$table->integer('external_id')->nullable()->index();
			$table->string('logo_path', 1000)->nullable();
			$table->string('full_name', 250)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('agent_infos');
	}

}
