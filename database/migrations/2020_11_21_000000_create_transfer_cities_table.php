<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_cities', function (Blueprint $table) {
            $table->id();
            $table->string('city_id');
            $table->string('city_name');
            $table->string('city_code');
            $table->string('station_id')->nullable();
            $table->string('station_name')->nullable();
            $table->string('country_name')->nullable();
            $table->string('country_code')->nullable();
            $table->string('port_name')->nullable();
            $table->string('port_id')->nullable();
            $table->string('port_destination')->nullable();
            $table->string('airport_code')->nullable();
            $table->string('airport_name')->nullable();
            $table->integer('type');
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
        Schema::dropIfExists('transfer_cities');
    }
}
