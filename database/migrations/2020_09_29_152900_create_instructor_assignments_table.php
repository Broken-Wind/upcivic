<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstructorAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructor_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_assignment_id');
            $table->unsignedBigInteger('instructor_id');
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->unsignedBigInteger('completed_by_user_id')->nullable();
            $table->unsignedBigInteger('approved_by_user_id')->nullable();

            $table->foreign('parent_assignment_id')->references('id')->on('assignments')->onDelete('cascade');
            $table->foreign('instructor_id')->references('id')->on('instructors')->onDelete('cascade');
            $table->foreign('completed_by_user_id')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('approved_by_user_id')->references('id')->on('users')->onDelete('SET NULL');
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
        Schema::dropIfExists('instructor_assignments');
    }
}
