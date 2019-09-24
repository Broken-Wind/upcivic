<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CascadeDeleteTenantUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('tenant_user', function (Blueprint $table) {
            //

            $table->dropForeign('tenant_user_tenant_id_foreign');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');

            $table->dropForeign('tenant_user_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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

        Schema::table('tenant_user', function (Blueprint $table) {
            //

            $table->dropForeign('tenant_user_tenant_id_foreign');
            $table->foreign('tenant_id')->references('id')->on('tenants');

            $table->dropForeign('tenant_user_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');

        });
    }
}
