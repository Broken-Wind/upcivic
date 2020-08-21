<?php

namespace App\Services;

use App\Program;

class ResourceTimelineService
{
    protected $demoService;

    public function __construct(DemoService $demoService)
    {
        $this->demoService = $demoService;
    }

    public function getResources()
    {
        return $this->demoService->getDemoSites()->map(function ($site) {
            return $site->locations->map(function ($location) {
                return [
                    'id' => $location->id,
                    'site' => $location->site->name,
                    'title' => $location->name,
                ];
            })->prepend([
                'id' => '0_'.$site->id,
                'site' => $site->name,
                'title' => 'Location TBD',
            ]);
        })->flatten(1)->prepend([
            'id' => 0,
            'site' => 'Site TBD',
            'title' => ' ',
        ]);
    }

    public function getEvents()
    {
        return Program::with(['meetings.site', 'contributors.organization'])->get()->sortBy('start_datetime')->map(function ($program) {
            $otherContributors = $program->contributors->where('organization_id', '!=', tenant()->organization_id)->map(function ($contributor) {
                return [
                    'organization_id' => $contributor->organization_id,
                    'name' => $contributor->organization->name,
                ];
            });
            $meetings = $program->meetings->map(function ($meeting) {
                return [
                    'start_date' => $meeting->start_date,
                    'end_date' => $meeting->end_date,
                    'start_time' => $meeting->start_time,
                    'end_time' => $meeting->end_time,
                    'site' => $meeting->site->name
                ];
            });
            return [
                'id' => $program->id,
                'resourceId' => $program->location_id,
                'title' => $program->internal_name . " at " . $program->site->name,
                'description_of_meetings' => $program->description_of_meetings,
                'program_times' => $program['start_time'] . '-' . $program['end_time'],
                'start' => $program->start_datetime,
                'end' => $program->end_datetime,
                'min_age' => $program->min_age,
                'max_age' => $program->max_age,
                'ages_type' => $program->ages_type,
                'other_contributors' => $otherContributors,
                'meetings' => $meetings
            ];
        })->values()->toJson();
    }
}
