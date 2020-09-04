<?php

namespace App\Services;

use App\Program;
use App\Site;
use App\Template;
use App\Tenant;
use Carbon\Carbon;

class DemoService
{
    public function regenerateDemoData()
    {
        $demoProvider = $this->getDemoProviderTenant();
        $demoHost = $this->getDemoHostTenant();
        $demoSites = $this->getDemoSites();

        Program::whereHas('contributors', function ($query) use ($demoProvider, $demoHost) {
            return $query->where('organization_id', $demoProvider->organization_id)->orWhere('organization_id', $demoHost->organization_id);
        })->delete();

        $templates = Template::withoutGlobalScopes()->where('organization_id', $demoProvider->organization_id)->get();

        for ($week = 0; $week < 10; $week++) {
            for ($program = 0; $program < 10; $program++) {
                $demoSite = rand(1, 10) > 3 ? $demoSites[$program % 2] : null;
                $demoSiteId = $demoSite->id ?? null;
                $demoLocationId = (rand(1, 10) > 5 && ! empty($demoSiteId)) ? $demoSite->locations->random()->id : null;
                $startDate = Carbon::now()->next('Monday')->addWeeks($week)->toDateString();
                $template = $templates->random();
                $proposal = [
                    'start_date' => $startDate,
                    'start_time' => '09:00',
                    'recipient_organization_id' => $demoHost->organization_id,
                    'site_id' => $demoSiteId,
                    'location_id' => $demoLocationId,
                ];
                Program::fromTemplate($proposal, $template);
                if ($template->meeting_minutes == 180) {
                    $pmProposal = [
                        'start_date' => $startDate,
                        'start_time' => '13:00',
                        'recipient_organization_id' => $demoHost->organization_id,
                        'site_id' => $demoSiteId,
                        'location_id' => $demoLocationId,
                    ];
                    Program::fromTemplate($pmProposal, $template);
                }
            }
        }

        // Demo Provider has several templates:

        // User logs in as demo.host@upcivic.com
        // Button on user dashboard to regenerate demo programs
        // Demo Host & Demo Acitivity Provider are only visible to each other, and are the only organizations visible to each other
        // Validator ensures no proposals to non-demo orgs are possible from demo account
    }

    protected function getDemoProviderTenant()
    {
        return Tenant::where('slug', 'demo-provider')->firstOrFail();
    }

    protected function getDemoHostTenant()
    {
        return Tenant::where('slug', 'demo-host')->firstOrFail();
    }

    public function getDemoSites()
    {
        return Site::where('name', 'Demo Recreation Center')->orWhere('name', 'Demo Community Center')->get();
    }
}
