<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToEstateDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estate_data', function (Blueprint $table) {
            $table->text('floor_plan')->nullable();
            $table->text('ex_agreement')->nullable();
            $table->text('copy_licence')->nullable();
            $table->text('un_bill')->nullable();
            $table->text('feed')->nullable();
            $table->text('path_for_ex_agreement')->nullable();
            $table->text('path_for_copy_licence')->nullable();
            $table->text('path_for_un_bill')->nullable();
            $table->text('path_for_feed')->nullable();
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
            $table->dropColumn('floor_plan');
            $table->dropColumn('ex_agreement');
            $table->dropColumn('copy_licence');
            $table->dropColumn('un_bill');
            $table->dropColumn('feed');
            $table->dropColumn('path_for_ex_agreement');
            $table->dropColumn('path_for_copy_licence');
            $table->dropColumn('path_for_un_bill');
            $table->dropColumn('path_for_feed');
        });
    }
}
