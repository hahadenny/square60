<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPathForDocumentsToEstateRentals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estate_rentals', function (Blueprint $table) {
            $table->text('feed')->after('un_bill');
            $table->text('path_for_ex_agreement');
            $table->text('path_for_copy_licence');
            $table->text('path_for_un_bill');
            $table->text('path_for_feed');
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
            $table->dropColumn('feed');
            $table->dropColumn('path_for_ex_agreement');
            $table->dropColumn('path_for_copy_licence');
            $table->dropColumn('path_for_un_bill');
            $table->dropColumn('path_for_feed');
        });
    }
}
