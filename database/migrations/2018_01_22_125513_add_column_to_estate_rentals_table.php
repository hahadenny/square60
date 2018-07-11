<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToEstateRentalsTable extends Migration
{
    public function up()
    {
        Schema::table('estate_rentals', function (Blueprint $table) {
            $table->text('path_for_images');
            $table->text('path_for_floor_plan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estate_rentals', function (Blueprint $table) {
            $table->dropColumn('path_for_images');
            $table->dropColumn('path_for_floor_plan');
        });
    }
}
