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
        return tenant()->organization->sites->map(function ($site) {
            return $site->locations->map(function ($location) {
                return [
                    'id' => $location->id,
                    'site' => $location->site->name,
                    'title' => $location->event_title,
                ];
            })->prepend([
                'id' => '0_'.$site->id,
                'site' => $site->name,
                'title' => 'Location TBD',
            ]);
        })->prepend([[
            'id' => 0,
            'site' => 'Site TBD',
            'title' => ' ',
        ], [
            'id' => '0_0',
            'site' => 'Offsite',
            'title' => ' ',
        ]])->flatten(1);
    }

    public function getEvents()
    {
        $siteIds = tenant()->organization->sites->pluck('id');
        return Program::with(['meetings.site', 'contributors.organization'])->get()->sortBy('start_datetime')->map(function ($program) use ($siteIds) {
            $contributors = $program->contributors->map(function ($contributor) {
                return [
                    'id' => $contributor->id,
                    'name' => $contributor->organization->name,
                    'approved_by' => $contributor->approver->name,
                    'class_string' => $contributor->class_string,
                    'status_string' => $contributor->status_string
                ];
            });
            $meetings = $program->meetings->map(function ($meeting) {
                return [
                    'start_date' => $meeting->start_date,
                    'site' => $meeting->site->name
                ];
            });
            return [
                'id' => $program->id,
                'resourceId' => !$siteIds->contains($program->site->id) && !empty($program->site->id) ? '0_0' : $program->location_id,
                'title' => $program->timeline_title,
                'description_of_meetings' => $program->description_of_meetings,
                'program_times' => $program['start_time'] . '-' . $program['end_time'],
                'start' => $program->start_datetime,
                'end' => $program->end_datetime,
                // We could get the site name from the event's resource instead of passing it through here,
                // but then, for offsite programs, the details moda would display "Offsite" instead of the actual site name.
                'site_name' => $program->site->name,
                'ages_string' => $program->ages_string,
                'contributors' => $contributors,
                'meetings' => $meetings,
                'min_enrollments' => $program->min_enrollments,
                'max_enrollments' => $program->max_enrollments,
                'backgroundColor' => $program->event_color,
                'borderColor' => $program->event_color,
                'proposed_at' => !empty($program->proposed_at) ? $program->proposed_at->format('m/d/Y g:ia') : 'N/A',
                'status_string' => $program->status_string,
                'status_class_string' => $program->status_class_string,
                'is_fully_approved' => $program->isFullyApproved(),
            ];
        })->values()->toJson();
    }
}
