<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('internal_name')->nullable();
            $table->text('description');
            $table->smallInteger('min_age');
            $table->smallInteger('max_age');
            $table->string('ages_type');
            $table->integer('invoice_amount');
            $table->string('invoice_type');
            $table->smallInteger('meeting_minutes');
            $table->smallInteger('meeting_interval');
            $table->smallInteger('meeting_count');
            $table->unsignedBigInteger('organization_id');
            $table->timestamps();

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
        Schema::dropIfExists('templates');
    }
}
