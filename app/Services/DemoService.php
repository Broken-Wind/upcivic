<?php

namespace App\Services;

use App\Program;
use App\Site;
use App\Template;
use App\Tenant;
use App\User;
use Carbon\Carbon;

class DemoService
{
    public function regenerateDemoData()
    {
        $demoProvider = $this->getDemoProviderTenant();
        $demoProviderUser = $this->getDemoProviderUser();
        $demoHost = $this->getDemoHostTenant();
        $demoHostUser = $this->getDemoHostUser();
        $demoSites = $this->getDemoSites();

        Program::whereHas('contributors', function ($query) use ($demoProvider, $demoHost) {
            return $query->where('organization_id', $demoProvider->organization_id)->orWhere('organization_id', $demoHost->organization_id);
        })->delete();

        $templates = Template::withoutGlobalScopes()->where('organization_id', $demoProvider->organization_id)->get();

        for ($week = 0; $week < 4; $week++) {
            for ($program = 0; $program < config('app.demo_programs_qty'); $program++) {
                $demoSite = rand(1, 10) > 3 ? $demoSites->random() : null;
                $demoSiteId = $demoSite->id ?? null;
                $demoLocationId = is_object($demoSite) ? $demoSite->locations->pluck('id')->random() : null;
                $demoLocationId = (rand(1, 10) > 5 && ! empty($demoSiteId)) ? $demoSite->locations->random()->id : null;
                $startDate = Carbon::now()->startOfWeek()->addWeeks($week)->toDateString();
                $template = $templates->random();
                $proposal = [
                    'start_date' => $startDate,
                    'start_time' => '09:00',
                    'recipient_organization_id' => $demoHost->organization_id,
                    'proposing_organization_id' => $demoProvider->organization_id,
                    'proposed_at' => Carbon::now(),
                    'site_id' => $demoSiteId,
                    'location_id' => $demoLocationId,
                ];
                $amProgram = Program::fromTemplate($proposal, $template);
                $amContributor = $amProgram->contributors()->where('organization_id', $demoProvider->organization_id)->firstOrFail();
                $demoProviderUser->approveProgramForContributor($amProgram, $amContributor);
                if (rand(0, 10) < 5) {
                    $amHostContributor = $amProgram->contributors()->where('organization_id', $demoHost->organization_id)->firstOrFail();
                    $demoHostUser->approveProgramForContributor($amProgram, $amHostContributor);
                }
                if ($template->meeting_minutes == 180) {
                    $pmProposal = [
                        'start_date' => $startDate,
                        'start_time' => '13:00',
                        'recipient_organization_id' => $demoHost->organization_id,
                        'proposing_organization_id' => $demoProvider->organization_id,
                        'proposed_at' => Carbon::now(),
                        'site_id' => $demoSiteId,
                        'location_id' => $demoLocationId,
                    ];
                    $pmProgram = Program::fromTemplate($pmProposal, $template);
                    $pmContributor = $pmProgram->contributors()->where('organization_id', $demoProvider->organization_id)->firstOrFail();
                    $demoProviderUser->approveProgramForContributor($pmProgram, $pmContributor);
                    if (rand(0, 10) < 9) {
                        $pmHostContributor = $pmProgram->contributors()->where('organization_id', $demoHost->organization_id)->firstOrFail();
                        $demoHostUser->approveProgramForContributor($pmProgram, $pmHostContributor);
                    }
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

    protected function getDemoProviderUser()
    {
        return User::where('email', 'demo.activity.provider@upcivic.com')->firstOrFail();
    }

    protected function getDemoHostUser()
    {
        return User::where('email', 'demo.host@upcivic.com')->firstOrFail();
    }

    protected function getDemoHostTenant()
    {
        return Tenant::where('slug', 'demo-host')->firstOrFail();
    }

    public function getDemoSites()
    {
        return $this->getDemoHostTenant()->organization->sites;
    }
}
