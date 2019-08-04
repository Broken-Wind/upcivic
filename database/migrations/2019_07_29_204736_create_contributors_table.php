<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContributorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contributors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('program_id');
            $table->unsignedBigInteger('organization_id');
            $table->string('internal_name')->nullable();
            $table->integer('invoice_amount')->nullable();
            $table->string('invoice_type')->nullable();
            $table->timestamps();

            $table->foreign('program_id')->references('id')->on('programs');
            $table->foreign('organization_id')->references('id')->on('organizations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contributors');
    }
}
