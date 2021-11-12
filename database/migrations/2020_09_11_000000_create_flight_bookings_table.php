<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlightBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flight_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id')->nullable();
            $table->string('user_id');
            $table->string('trace_id');
            $table->string('token_id');
            $table->string('is_lcc')->nullable();
            $table->string('flight_booking_status')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('pnr')->nullable();
            $table->string('booking_ref')->nullable();
            $table->boolean('price_changed')->nullable();
            $table->boolean('cancellation_policy')->nullable();
            $table->string('sub_domain')->nullable();
            $table->text('request_data')->nullable();
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
        Schema::dropIfExists('flight_bookings');
    }
}
