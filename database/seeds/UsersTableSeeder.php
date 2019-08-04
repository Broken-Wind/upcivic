<?php

use Illuminate\Database\Seeder;

use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::create([

            'name' => 'Greg Intermaggio',

            'email' => 'greg@techsplosion.org',

            'password' => bcrypt('12341234'),

            'email_verified_at' => \Carbon\Carbon::now(),

        ]);
    }
}
