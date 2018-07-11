<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('label');
            $table->boolean('general')->default(0);
            $table->integer('parent_id')->default(0);
            $table->string('type');
            $table->timestamps();
        });

        Schema::create('filters_data', function (Blueprint $table) {
            $table->increments('filter_data_id');
            $table->string('filter_id');
            $table->string('value');
            $table->integer('parent_id')->default(0);
            $table->index(['filter_id', 'parent_id']);
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
        Schema::dropIfExists('filters');
        Schema::dropIfExists('filters_data');
    }
}
