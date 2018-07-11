<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEstateDataImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('estate_data_images', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('es_listing_id');
			$table->text('unit_path', 65535);
			$table->text('agent_path', 65535);
			$table->text('unit_thumb', 65535);
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
		Schema::drop('estate_data_images');
	}

}
