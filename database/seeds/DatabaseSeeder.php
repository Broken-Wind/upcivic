<?php

use Illuminate\Database\Seeder;
use App\Organization;
use App\Site;
use App\Template;
use App\Tenant;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            DemoSeeder::class
        ]);
        $user = User::create([
            'name' => 'Greg Intermaggio',
            'email' => 'greg@upcivic.com',
            'password' => bcrypt('change!m3'),
            'email_verified_at' => \Carbon\Carbon::now(),
        ]);

        $organization = Organization::create([
            'name' => 'Exampleville Parks & Recreation',
        ]);

        $tenant = Tenant::create([
            'organization_id' => $organization->id,
            'slug' => 'example',
        ]);

        $user->joinTenant($tenant);
        $template = new Template([
            'name' => 'Example Cooking Camp',
            'description' => 'This is an example program session. Scroll down to see the age range, collaborating organizations, and schedule. If you\'d like to propose a program to another organization, you\'ll need to add a template first. Click "Templates" above, and then create your first program template!',
            'public_notes' => 'The public notes field is a great place to put important information like special materials needed or prerequisites.',
            'contributor_notes' => 'Use contributor notes to tell your partners things like room setup preferences and special accommodations required, if any.',
            'min_age' => 7,
            'max_age' => 70,
            'ages_type' => 'ages',
            'invoice_amount' => 10000,
            'invoice_type' => 'per participant',
            'meeting_minutes' => 180,
            'meeting_interval' => 1,
            'meeting_count' => 5,
            'min_enrollments' => 5,
            'max_enrollments' => 12,
        ]);

        $template->organization_id = $organization->id;
        $template->save();

        Site::create([
            'name' => 'Exampleville Community Center',
            'phone' => '555-555-5555',
            'address' => '123 Fake St. Exampleville, CA',
        ]);
    }
}
