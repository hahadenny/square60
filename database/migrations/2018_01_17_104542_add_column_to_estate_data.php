<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToEstateData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estate_data', function (Blueprint $table) {
            $table->integer('user_id')->default('1')->unsigned();
            $table->integer('apartment');
            $table->text('boro');
            $table->integer('room');
            $table->text('web');
            $table->integer('commission');
            $table->integer('feature');
            $table->integer('broker');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estate_data', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('apartment');
            $table->dropColumn('boro');
            $table->dropColumn('room');
            $table->dropColumn('web');
            $table->dropColumn('commission');
            $table->dropColumn('feature');
            $table->dropColumn('broker');
        });
    }
}
