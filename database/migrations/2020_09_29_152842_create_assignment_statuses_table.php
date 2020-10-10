<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assignment_id');
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->unsignedBigInteger('completed_by_user_id')->nullable();
            $table->unsignedBigInteger('approved_by_user_id')->nullable();

            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
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
        Schema::dropIfExists('assignment_statuses');
    }
}
