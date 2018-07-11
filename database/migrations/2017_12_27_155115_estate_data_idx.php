<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EstateDataIdx extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estate_data', function (Blueprint $table) {

            $table->index('type_id','idx_type_id');
            $table->index('district_id','idx_district_id');
            $table->index('baths','idx_baths');
            $table->index('beds','idx_beds');
            $table->index('price','idx_price');
            $table->index(['baths','beds','price','type_id','district_id','id'],'ef_idx_composite');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
