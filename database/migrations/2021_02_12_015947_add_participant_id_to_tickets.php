<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParticipantIdToTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('participant_id')->nullable();
            $table->foreign('participant_id')->references('id')->on('participants')->onDelete('SET NULL');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            //
            $table->dropForeign('tickets_participant_id_foreign');
        });
    }
}
