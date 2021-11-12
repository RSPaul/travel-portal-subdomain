<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCruisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cruises', function (Blueprint $table) {
            $table->id();
            $table->string('sub_domain');
            $table->string('name');
            $table->text('short_description');
            $table->text('description');
            $table->string('currency')->nullable();
            $table->string('price');
            $table->text('main_image');
            $table->text('images')->nullable();
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('cruises');
    }
}
