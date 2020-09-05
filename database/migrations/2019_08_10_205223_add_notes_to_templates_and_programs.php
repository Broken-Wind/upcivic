<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotesToTemplatesAndPrograms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('templates', function (Blueprint $table) {
            //
            $table->text('public_notes')->nullable();
            $table->text('contributor_notes')->nullable();
        });

        Schema::table('programs', function (Blueprint $table) {
            //
            $table->text('public_notes')->nullable();
            $table->text('contributor_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('templates', function (Blueprint $table) {
            //
            $table->dropColumn('public_notes');
            $table->dropColumn('contributor_notes');
        });

        Schema::table('programs', function (Blueprint $table) {
            //
            $table->dropColumn('public_notes');
            $table->dropColumn('contributor_notes');
        });
    }
}
