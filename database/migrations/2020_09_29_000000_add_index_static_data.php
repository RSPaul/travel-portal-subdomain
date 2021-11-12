<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexStaticData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('static_data_hotels', function (Blueprint $table) {
            $table->index('hotel_code');
            $table->index('city_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('static_data_hotels', function (Blueprint $table)
        {
            $table->dropIndex(['hotel_code']);
        });
    }
}
