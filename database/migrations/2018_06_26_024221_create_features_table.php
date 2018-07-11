<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFeaturesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('features', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('stripe_id', 100);
			$table->integer('listing_id')->index('listing_id');
			$table->integer('user_id')->index('user_id');
			$table->integer('renew');
			$table->string('period', 20);
			$table->decimal('amount', 10)->nullable();
			$table->dateTime('ends_at')->nullable();
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
		Schema::drop('features');
	}

}
