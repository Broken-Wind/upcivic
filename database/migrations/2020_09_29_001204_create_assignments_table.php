<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('assign_to_entity');
            $table->unsignedBigInteger('task_id')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('assigned_by_organization_id');
            $table->unsignedBigInteger('assigned_to_organization_id');

            $table->foreign('assigned_by_organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('assigned_to_organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignments');
    }
}
