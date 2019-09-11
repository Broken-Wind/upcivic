<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdministratorsFromExistingUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        $people = DB::table('users')->get()->map(function ($user) {

            $exploded = explode(' ', $user->name);

            $firstName = $exploded[0];

            $lastName = array_last($exploded);

            return [

                'first_name' => $firstName,

                'last_name' => $lastName,

                'email' => $user->email,

                'created_at' => Carbon\Carbon::now(),

                'updated_at' => Carbon\Carbon::now(),

            ];

        })->toArray();

        DB::table('people')->insert($people);


        $administrators = DB::table('tenant_user')->get()->map(function ($tenantUser) {

            $user = DB::table('users')->find($tenantUser->user_id);

            $personId = DB::table('people')->where('email', $user->email)->first()->id;

            return [

                'organization_id' => DB::table('tenants')->find($tenantUser->tenant_id)->organization_id,

                'person_id' => $personId,

                'created_at' => Carbon\Carbon::now(),

                'updated_at' => Carbon\Carbon::now(),

            ];

        })->toArray();

        DB::table('administrators')->insert($administrators);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        DB::table('people')->truncate();

        DB::table('administrators')->truncate();

    }
}
