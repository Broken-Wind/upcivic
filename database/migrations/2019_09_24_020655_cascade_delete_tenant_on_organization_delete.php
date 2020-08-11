<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CascadeDeleteTenantOnOrganizationDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('tenants', function (Blueprint $table) {
            //

            $table->dropForeign('tenants_organization_id_foreign');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
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

        Schema::table('tenants', function (Blueprint $table) {
            //

            $table->dropForeign('tenants_organization_id_foreign');
            $table->foreign('organization_id')->references('id')->on('organizations');
        });
    }
}
