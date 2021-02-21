<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnrollmentColumnsToContributors extends Migration
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
            $table->boolean('internal_registration')->nullable();
            $table->string('enrollment_url')->nullable();
            $table->text('enrollment_instructions')->nullable();
            $table->text('enrollment_message')->nullable();
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
            $table->dropColumn('internal_registration');
            $table->dropColumn('enrollment_url');
            $table->dropColumn('enrollment_instructions');
            $table->dropColumn('enrollment_message');
        });
    }
}
