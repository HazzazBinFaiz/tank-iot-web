<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSensorReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('timestamp');
            $table->float('distance_cm');
            $table->float('water_percent');
            $table->float('air_temp');
            $table->float('air_humidity');
            $table->float('water_temp');
            $table->float('tds');
            $table->boolean('pump_on');
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
        Schema::dropIfExists('sensor_readings');
    }
}
