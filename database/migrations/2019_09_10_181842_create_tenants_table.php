<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('organization_id');
            $table->string('slug');
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations');
        });

        $tenants = DB::table('organizations')->get()->map(function ($organization) {
            return [

                'organization_id' => $organization->id,

                'slug' => $organization->slug,

                'created_at' => Carbon\Carbon::now(),

                'updated_at' => Carbon\Carbon::now(),

            ];
        })->toArray();

        DB::table('tenants')->insert($tenants);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenants');
    }
}
