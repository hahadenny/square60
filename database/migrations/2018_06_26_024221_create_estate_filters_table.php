<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEstateFiltersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('estate_filters', function(Blueprint $table)
		{
			$table->integer('estate_id')->primary();
			$table->boolean('f_354')->default(0)->index('idx_f_354');
			$table->boolean('f_356')->default(0)->index('idx_f_356');
			$table->boolean('f_357')->default(0)->index('idx_f_357');
			$table->boolean('f_361')->default(0)->index('idx_f_361');
			$table->boolean('f_362')->default(0)->index('idx_f_362');
			$table->boolean('f_363')->default(0)->index('idx_f_363');
			$table->boolean('f_365')->default(0)->index('idx_f_365');
			$table->boolean('f_367')->default(0)->index('idx_f_367');
			$table->boolean('f_379')->default(0)->index('idx_f_379');
			$table->boolean('f_380')->default(0)->index('idx_f_380');
			$table->boolean('f_381')->default(0)->index('idx_f_381');
			$table->integer('f_395')->default(0)->index('f_395');
			$table->integer('f_394')->default(0)->index('f_394');
			$table->integer('f_393')->default(0)->index('f_393');
			$table->integer('f_392')->default(0)->index('f_392');
			$table->integer('f_391')->default(0)->index('f_391');
			$table->integer('f_390')->default(0)->index('f_390');
			$table->integer('f_389')->default(0)->index('f_389');
			$table->integer('f_388')->default(0)->index('f_388');
			$table->integer('f_387')->default(0)->index('f_387');
			$table->integer('f_386')->default(0)->index('f_386');
			$table->integer('f_385')->default(0)->index('f_385');
			$table->integer('f_384')->default(0)->index('f_384');
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
		Schema::drop('estate_filters');
	}

}
