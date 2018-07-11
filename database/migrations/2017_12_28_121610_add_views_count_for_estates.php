<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddViewsCountForEstates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estate_data', function (Blueprint $table) {
            $table->bigInteger('views_count');
            $table->index('views_count','views_count_idx');
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
            $table->dropColumn('views_count');
            $table->dropIndex('views_count_idx');
        });
    }
}
