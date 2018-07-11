<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBuildingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('buildings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('building_id')->unique('building_idx');
			$table->string('building_address');
			$table->string('building_name');
			$table->string('building_city');
			$table->string('building_state');
			$table->string('building_zip');
			$table->integer('building_units');
			$table->string('building_stories');
			$table->integer('building_build_year');
			$table->text('building_amenities', 65535);
			$table->text('building_description', 65535);
			$table->text('path_for_building_images_on_s3', 65535);
			$table->text('building_images', 65535);
			$table->timestamps();
			$table->integer('b_listing_type')->default(1);
			$table->integer('building_type')->nullable();
			$table->integer('building_district_id')->nullable();
			$table->integer('name_label')->nullable()->default(0);
			$table->integer('amazon_id')->nullable()->default(0);
			$table->string('neighborhood')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('buildings');
	}

}
