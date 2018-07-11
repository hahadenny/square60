<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAgentConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('agents_buildings')){
        Schema::create('agents_buildings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('building_id');
            $table->integer('agent_id');
            $table->unique(array('building_id','agent_id'),'building_idx');
        });
    }
        if (!Schema::hasTable('agents_listings')){
            Schema::create('agents_listings', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('listings_id');
                $table->integer('agent_id');
                $table->unique(array('listings_id','agent_id'),'listings_idx');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //

        Schema::dropIfExists('agents_buildings');
        Schema::dropIfExists('agents_listings');
    }
}
