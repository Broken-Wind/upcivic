<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixLocationDeletionWithMeetings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meetings', function (Blueprint $table) {
            //
            $table->dropForeign('meetings_location_id_foreign');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meetings', function (Blueprint $table) {
            //
            $table->dropForeign('meetings_location_id_foreign');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('RESTRICT');
        });
    }
}
