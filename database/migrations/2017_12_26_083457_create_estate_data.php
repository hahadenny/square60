<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstateData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('estate_data');
        Schema::create('estate_data', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active');
            $table->integer('estate_type');// sell/rental/commercical
            $table->string('name',250);
            $table->string('full_address',1000);
            $table->string('address',1000);
            $table->string('city',200);
            $table->string('state',20);
            $table->string('zip',20);
            $table->text('images');
            $table->string('units',20);
            $table->string('stories',20);
            $table->string('year_built',10);
            $table->string('building_type',50);
            $table->integer('type_id');
            $table->string('neighborhood',50);
            $table->integer('district_id');
            $table->string('amenities',10000);
            $table->string('date',30);
            $table->string('unit',30);
            $table->decimal('price',10,2);
            $table->float('beds');
            $table->float('baths');
            $table->float('sq_feet');
            $table->decimal('common_charges',10,2);
            $table->decimal('monthly_taxes',10,2);
            $table->decimal('maintenance',10,2); //TODO what in this column ?
            $table->string('agent_company',200);
            $table->string('agent_phone',50);
            $table->text('unit_images');
            $table->text('unit_description');
            $table->integer('building_id');
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
        Schema::dropIfExists('estate_data');
    }
}
