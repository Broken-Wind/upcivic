<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CascadeDeleteExistingModels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('meetings', function (Blueprint $table) {
            //
            $table->dropForeign('meetings_program_id_foreign');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
        });

        Schema::table('contributors', function (Blueprint $table) {
            //
            $table->dropForeign('contributors_program_id_foreign');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');

            $table->dropForeign('contributors_organization_id_foreign');
            $table->foreign('organization_id')->references('id')->on('programs')->onDelete('cascade');
        });

        Schema::table('organization_user', function (Blueprint $table) {
            //
            $table->dropForeign('organization_user_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->dropForeign('organization_user_organization_id_foreign');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
        });

        Schema::table('templates', function (Blueprint $table) {
            //
            $table->dropForeign('templates_organization_id_foreign');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
        });

        Schema::table('locations', function (Blueprint $table) {
            //
            $table->dropForeign('locations_site_id_foreign');
            $table->foreign('site_id')->references('id')->on('sites')->onDelete('cascade');
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

        Schema::table('meetings', function (Blueprint $table) {
            //
            $table->dropForeign('meetings_program_id_foreign');
            $table->foreign('program_id')->references('id')->on('programs');
        });

        Schema::table('contributors', function (Blueprint $table) {
            //
            $table->dropForeign('contributors_program_id_foreign');
            $table->foreign('program_id')->references('id')->on('programs');

            $table->dropForeign('contributors_organization_id_foreign');
            $table->foreign('organization_id')->references('id')->on('programs');
        });

        Schema::table('organization_user', function (Blueprint $table) {
            //
            $table->dropForeign('organization_user_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');

            $table->dropForeign('organization_user_organization_id_foreign');
            $table->foreign('organization_id')->references('id')->on('organizations');
        });

        Schema::table('templates', function (Blueprint $table) {
            //
            $table->dropForeign('templates_organization_id_foreign');
            $table->foreign('organization_id')->references('id')->on('organizations');
        });

        Schema::table('locations', function (Blueprint $table) {
            //
            $table->dropForeign('locations_site_id_foreign');
            $table->foreign('site_id')->references('id')->on('sites');
        });
    }
}
