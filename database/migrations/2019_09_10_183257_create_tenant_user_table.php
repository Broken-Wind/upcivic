<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants');
            $table->foreign('user_id')->references('id')->on('users');
        });

        $tenantUsers = DB::table('organization_user')->get()->map(function ($organizationUser) {
            return [

                'tenant_id' => DB::table('tenants')->where('organization_id', $organizationUser->organization_id)->first()->id,

                'user_id' => $organizationUser->user_id,

                'created_at' => Carbon\Carbon::now(),

                'updated_at' => Carbon\Carbon::now(),

            ];
        })->toArray();

        DB::table('tenant_user')->insert($tenantUsers);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organization_user');
    }
}
