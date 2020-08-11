<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

class CreateMissingAdministratorsFromExistingUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $administratorEmails = DB::table('people')->get()->pluck('email');

        $people = DB::table('users')->whereNotIn('email', $administratorEmails)->get()->map(function ($user) {
            $exploded = explode(' ', $user->name);

            $firstName = $exploded[0];

            $lastName = count($exploded) > 1 ? Arr::last($exploded) : '';

            return [

                'first_name' => $firstName,

                'last_name' => $lastName,

                'email' => $user->email,

                'created_at' => Carbon\Carbon::now(),

                'updated_at' => Carbon\Carbon::now(),

            ];
        })->toArray();

        DB::table('people')->insert($people);

        $administrators = collect();

        DB::table('tenant_user')->get()->each(function ($tenantUser) use ($administratorEmails, $administrators) {
            $user = DB::table('users')->find($tenantUser->user_id);

            if ($administratorEmails->contains($user->email)) {
                return;
            }

            $personId = DB::table('people')->where('email', $user->email)->first()->id;

            $administrators->push([

                'organization_id' => DB::table('tenants')->find($tenantUser->tenant_id)->organization_id,

                'person_id' => $personId,

                'created_at' => Carbon\Carbon::now(),

                'updated_at' => Carbon\Carbon::now(),

            ]);
        });

        $administrators = $administrators->toArray();

        DB::table('administrators')->insert($administrators);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
