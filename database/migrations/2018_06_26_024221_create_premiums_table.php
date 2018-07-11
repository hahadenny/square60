<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePremiumsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('premiums', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('stripe_id', 100);
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
		Schema::drop('premiums');
	}

}
