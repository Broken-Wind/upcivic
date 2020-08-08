<?php
namespace Upcivic\Services;

use Carbon\Carbon;
use Upcivic\Program;
use Upcivic\Tenant;
use Upcivic\Site;

class DemoService {
    public function regenerateDemoData()
    {
        $demoProvider = $this->getDemoProviderTenant();
        $demoHost = $this->getDemoHostTenant();
        $demoSite = $this->getDemoSite();

        Program::whereHas('contributors', function ($query) use ($demoProvider, $demoHost) {
            return $query->where('organization_id', $demoProvider->organization_id)->orWhere('organization_id', $demoHost->organization_id);
        })->delete();

        $templates = $demoProvider->organization->templates;

        for ($week=0; $week<10; $week++) {
            for ($program=0; $program<20; $program++) {
                $startDate = Carbon::now()->next('Monday')->addWeeks($week)->toDateString();
                $template = $templates->random();
                $proposal = [
                    "start_date" => $startDate,
                    "start_time" => "09:00",
                    "recipient_organization_id" => $demoHost->organization_id,
                    "site_id" => $demoSite->id
                ];
                Program::fromTemplate($proposal, $template);
                if ($template->meeting_minutes == 180) {
                    $pmTemplate = $templates->where('id', '!=', $template->id)->where('meeting_minutes', 180)->first();
                    $pmProposal = [
                        "start_date" => $startDate,
                        "start_time" => "13:00",
                        "recipient_organization_id" => $demoHost->organization_id,
                        "site_id" => $demoSite->id
                    ];
                    Program::fromTemplate($pmProposal, $pmTemplate);
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
    protected function getDemoSite()
    {
        return Site::where('name', 'Exampleville Community Center')->firstOrFail();
    }
}
