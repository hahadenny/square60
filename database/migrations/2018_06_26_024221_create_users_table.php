<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name');
			$table->string('email');
			$table->string('password');
			$table->string('phone')->nullable();
			$table->string('remember_token', 100)->nullable();
			$table->timestamps();
			$table->string('stripe_id', 100)->nullable();
			$table->text('card_brand', 65535)->nullable();
			$table->integer('card_last_four')->nullable();
			$table->dateTime('trial_ends_at')->nullable();
			$table->string('ipaddr', 30)->nullable();
			$table->string('location');
			$table->integer('premium')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
