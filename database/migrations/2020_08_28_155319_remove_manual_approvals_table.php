<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveManualApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('manual_approvals');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::create('manual_approvals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('contributor_id');

            $table->foreign('person_id')->references('id')->on('people');
            $table->foreign('contributor_id')->references('id')->on('contributors');
        });
    }
}
