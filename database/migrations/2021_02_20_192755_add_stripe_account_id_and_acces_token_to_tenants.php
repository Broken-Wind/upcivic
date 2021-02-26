<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStripeAccountIdAndAccesTokenToTenants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenants', function (Blueprint $table) {
            //
            $table->string('stripe_account_id')->nullable();
            $table->string('stripe_access_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            //
            $table->dropColumn('stripe_account_id');
            $table->dropColumn('stripe_access_token');
        });
    }
}
