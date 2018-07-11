<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EstateDataAddS3Paths extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estate_data', function (Blueprint $table) {
            $table->text('path_for_images');
            $table->text('path_for_floorplans');
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
            $table->dropColumn('path_for_images');
            $table->dropIndex('path_for_floorplans');
        });
    }
}
