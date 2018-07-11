<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEstateData2Table extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('estate_data_2', function(Blueprint $table)
		{
			$table->increments('id');
			$table->boolean('active')->index('active');
			$table->integer('estate_type');
			$table->string('name', 250);
			$table->string('full_address', 1000);
			$table->string('address', 1000);
			$table->string('city', 200);
			$table->string('state', 20);
			$table->string('zip', 20);
			$table->text('images', 65535);
			$table->string('units', 20);
			$table->string('stories', 20);
			$table->string('year_built', 10)->nullable();
			$table->string('building_type', 50);
			$table->string('unit_type', 100)->default('');
			$table->integer('type_id')->index('idx_type_id');
			$table->string('neighborhood', 50);
			$table->integer('district_id')->index('idx_district_id');
			$table->string('amenities', 10000);
			$table->text('b_amenities', 65535)->nullable();
			$table->string('date', 30);
			$table->string('unit', 30);
			$table->decimal('price', 10)->index('idx_price');
			$table->float('beds')->index('idx_beds');
			$table->float('baths')->index('idx_baths');
			$table->float('sq_feet');
			$table->decimal('common_charges', 10);
			$table->decimal('monthly_taxes', 10);
			$table->decimal('maintenance', 10);
			$table->string('agent_company', 200);
			$table->string('agent_phone', 50);
			$table->text('unit_images', 65535);
			$table->text('unit_description', 65535);
			$table->integer('building_id');
			$table->timestamps();
			$table->bigInteger('views_count')->default(0)->index('views_count_idx');
			$table->text('path_for_images', 65535);
			$table->text('path_for_large', 65535)->nullable();
			$table->text('path_for_floorplans', 65535);
			$table->integer('user_id')->unsigned()->default(1);
			$table->string('apartment')->nullable();
			$table->text('boro', 65535)->nullable();
			$table->integer('room')->nullable();
			$table->text('web', 65535)->nullable();
			$table->integer('commission')->nullable();
			$table->integer('feature')->nullable();
			$table->integer('broker')->nullable();
			$table->text('floor_plan', 65535)->nullable();
			$table->text('ex_agreement', 65535)->nullable();
			$table->text('copy_licence', 65535)->nullable();
			$table->text('un_bill', 65535)->nullable();
			$table->text('feed', 65535)->nullable();
			$table->text('path_for_ex_agreement', 65535)->nullable();
			$table->text('path_for_copy_licence', 65535)->nullable();
			$table->text('path_for_un_bill', 65535)->nullable();
			$table->text('path_for_feed', 65535)->nullable();
			$table->boolean('amazon_id')->default(0);
			$table->integer('listing_id')->default(0)->unique('listing_id');
			$table->index(['baths','beds','price','type_id','district_id','id','active'], 'ef_idx_composite');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('estate_data_2');
	}

}
