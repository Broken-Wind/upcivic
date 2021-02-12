<?php

use App\Program;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StoreMaxEnrollmentsAsTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Program::all()->each(function ($program) {
            $program->setMaxEnrollments($program->getAttributes()['max_enrollments']);
            $program->setEnrollments($program->getAttributes()['enrollments']);
        });

        Schema::table('programs', function (Blueprint $table) {
            //
            $table->dropColumn('enrollments');
        });

        Schema::table('programs', function (Blueprint $table) {
            //
            $table->dropColumn('max_enrollments');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('programs', function (Blueprint $table) {
            //
            $table->integer('enrollments');
            $table->integer('max_enrollments');
        });

        Program::all()->each(function ($program) {
            $program->update([
                'enrollments' => $program->enrollments,
                'max_enrollments' => $program->max_enrollments,
            ]);
        });


    }
}
