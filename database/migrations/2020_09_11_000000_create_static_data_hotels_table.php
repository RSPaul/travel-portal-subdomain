<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaticDataHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('static_data_hotels', function (Blueprint $table) {
            $table->id();
            $table->string('hotel_name')->nullable();
            $table->string('hotel_code');
            $table->integer('city_id');
            $table->longText('hotel_facilities')->nullable();
            $table->longText('attractions')->nullable();
            $table->longText('hotel_description')->nullable();
            $table->longText('hotel_images')->nullable();
            $table->longText('hotel_location')->nullable();
            $table->longText('hotel_address')->nullable();
            $table->longText('hotel_contact')->nullable();
            $table->longText('hotel_time')->nullable();
            $table->longText('hotel_type')->nullable();
            $table->boolean('data_updated')->default(0);
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
        Schema::dropIfExists('static_data_hotels');
    }
}
