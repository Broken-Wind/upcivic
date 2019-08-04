<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('program_id');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('site_id')->nullable();
            $table->dateTime('start_datetime')->index();
            $table->dateTime('end_datetime')->index();
            $table->string('note')->nullable();
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('site_id')->references('id')->on('sites');
            $table->foreign('program_id')->references('id')->on('programs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meetings');
    }
}
