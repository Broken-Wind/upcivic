<?php

use Illuminate\Database\Seeder;
use App\Organization;
use App\Site;
use App\Template;
use App\Tenant;
use App\User;

class DemoSeeder extends Seeder
{
    protected const TEMPLATE_NAMES = [
        'Cooking Camp (DEMO)',
        'LEGO Camp (DEMO)',
        'Soccer Camp (DEMO)',
        'Spanish Camp (DEMO)',
        'STEM Camp (DEMO)',
        'Arts & Crafts Camp (DEMO)',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $demoHostUser = User::create([
            'name' => 'Demo User',
            'email' => 'demo.host@upcivic.com',
            'password' => bcrypt('123demo'),
            'email_verified_at' => \Carbon\Carbon::now(),
            ]);
        $demoHostOrg = Organization::create([
            'name' => 'Demo Host',
        ]);
        $demoHostTenant = Tenant::create([
            'organization_id' => $demoHostOrg->id,
            'slug' => 'demo-host',
        ]);
        $demoHostUser->joinTenant($demoHostTenant);
        $this->seedDemoSites($demoHostOrg);

        $demoProviderUser = User::create([
            'name' => 'Demo User',
            'email' => 'demo.activity.provider@upcivic.com',
            'password' => bcrypt('123demo'),
            'email_verified_at' => \Carbon\Carbon::now(),
        ]);
        $demoProviderOrg = Organization::create([
            'name' => 'Demo Activity Provider',
        ]);
        $demoProviderTenant = Tenant::create([
            'organization_id' => $demoProviderOrg->id,
            'slug' => 'demo-provider',
        ]);
        $demoProviderUser->joinTenant($demoProviderTenant);

        $this->generateTemplatesFor($demoProviderOrg);
    }

    protected function generateTemplatesFor(Organization $demoProviderOrg)
    {
        for ($i = 0; $i < count(self::TEMPLATE_NAMES); $i++) {
            $template = new Template([
                'name' => self::TEMPLATE_NAMES[$i],
                'description' => 'This is an example program session. Scroll down to see the age range, collaborating organizations, and schedule. If you\'d like to propose a program to another organization, you\'ll need to add a template first. Click "Templates" above, and then create your first program template!',
                'public_notes' => 'The public notes field is a great place to put important information like special materials needed or prerequisites.',
                'contributor_notes' => 'Use contributor notes to tell your partners things like room setup preferences and special accommodations required, if any.',
                'min_age' => 7,
                'max_age' => 70,
                'ages_type' => 'ages',
                'invoice_amount' => rand(10, 50) * 1000, // Amount in cents- $100-$500 in increments of $10
                'invoice_type' => 'per participant',
                'meeting_minutes' => $i & 1 ? 420 : 180,
                'meeting_interval' => 1,
                'meeting_count' => 5,
                'min_enrollments' => 5,
                'max_enrollments' => 12,
            ]);
            $template->organization_id = $demoProviderOrg->id;
            $template->save();
        }
    }

    protected function seedDemoSites($demoHostOrg)
    {
        $rec = factory(Site::class)->states('demoRecCenter')->create(['name' => 'Demo Recreation Center']);
        $com = factory(Site::class)->states('demoCommunityCenter')->create(['name' => 'Demo Community Center']);
        $demoHostOrg->sites()->attach($rec);
        $demoHostOrg->sites()->attach($com);
    }
}
