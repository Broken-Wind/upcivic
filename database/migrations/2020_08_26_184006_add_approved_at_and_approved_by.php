<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovedAtAndApprovedBy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contributors', function (Blueprint $table) {
            //
            $table->dateTime('approved_at')->nullable();

            $table->unsignedBigInteger('approved_by_user_id')->nullable();
            $table->foreign('approved_by_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contributors', function (Blueprint $table) {
            //
            $table->dropForeign('contributors_approved_by_user_id_foreign');
            $table->dropColumn('approved_by_user_id');

            $table->dropColumn('approved_at');
        });
    }
}
