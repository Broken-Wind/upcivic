<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnrollmentUrlAndInstructionsToOrganizations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            //
            $table->string('enrollment_url')->nullable();
            $table->text('enrollment_instructions')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            //
            $table->dropColumn('enrollment_url');
            $table->dropColumn('enrollment_instructions');
        });
    }
}
