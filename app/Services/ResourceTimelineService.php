<?php

namespace App\Services;

use App\Program;
use App\Meeting;
use Carbon\Carbon;

use function GuzzleHttp\Promise\all;

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
                'resourceId' => !$siteIds->contains($program->site->id) && !empty($program->site->id) ? '0_0' : $program->resource_id,
                'title' => $program->name,
                'description_of_meetings' => $program->description_of_meetings,
                'program_times' => $program['start_time'] . '-' . $program['end_time'],
                'start' => $program->start_datetime,
                'end' => $program->end_datetime,
                // We could get the site name from the event's resource instead of passing it through here,
                // but then, for offsite programs, the details moda would display "Offsite" instead of the actual site name.
                'site_name' => $program->site->name,
                'site_address' => $program->site->address,
                'ages_string' => $program->ages_string,
                'contributors' => $contributors,
                'meetings' => $meetings,
                'min_enrollments' => $program->min_enrollments,
                'max_enrollments' => $program->max_enrollments,
                'backgroundColor' => $program->event_color,
                'borderColor' => $program->event_color,
                'proposed_at' => !empty($program->proposed_at) ? $program->proposed_at->format('n/j/Y') : 'N/A',
                'status_string' => $program->status_string,
                'status_class_string' => $program->status_class_string,
                'is_fully_approved' => $program->isFullyApproved(),
            ];
        })->values()->toJson();
    }

    public function getMeetingsEvents(Carbon $initialDate, Carbon $endDate)
    {
        $siteIds = tenant()->organization->sites->pluck('id');
        $result = [
            'meetings' => collect(),
            'programs' => collect()
        ];
        Program::with(['meetings.site', 'contributors.organization'])->get()->filter(function($program) use ($initialDate, $endDate) {
            return $program->lastMeeting()->end_datetime >= $initialDate && $program->firstMeeting()->start_datetime <= $endDate;
        })->sortBy('start_datetime')->each(function ($program) use ($siteIds, $result) {
            $contributors = $program->contributors->map(function ($contributor) {
                return [
                    'id' => $contributor->id,
                    'organization_id' => $contributor->organization_id,
                    'name' => $contributor->organization->name,
                    'approved_by' => $contributor->approver->name,
                    'class_string' => $contributor->class_string,
                    'status_string' => $contributor->status_string
                ];
            });
            $program->meetings->each(function ($meeting) use ($siteIds, $result, $program) {
                $result['meetings']->push([
                    'id' => $meeting->id,
                    'start' => $meeting->start_datetime,
                    'end' => $meeting->start_datetime,
                    'resourceId' => !$siteIds->contains($meeting->site_id) && !empty($meeting->site_id) ? '0_0' : $meeting->resource_id,
                    'title' => $program->name,
                    'groupId' => $meeting->program_id,
                    'backgroundColor' => $program->event_color,
                    'borderColor' => $program->event_color,
                ]);
            });
            $meetings = $program->meetings->map(function ($meeting) {
                return [
                    'start_date' => $meeting->start_date,
                    'site' => $meeting->site->name
                ];
            });
            $result['programs']->push([
                'id' => $program->id,
                'description_of_meetings' => $program->description_of_meetings,
                'program_times' => $program['start_time'] . '-' . $program['end_time'],
                'start' => $program->start_datetime,
                'end' => $program->end_datetime,
                'site_name' => $program->site->name,
                'site_address' => $program->site->address,
                'ages_string' => $program->ages_string,
                'meetings' => $meetings,
                'contributors' => $contributors,
                'min_enrollments' => $program->min_enrollments,
                'max_enrollments' => $program->max_enrollments,
                'proposed_at' => !empty($program->proposed_at) ? $program->proposed_at->format('n/j/Y') : 'N/A',
                'status_string' => $program->status_string,
                'status_class_string' => $program->status_class_string,
                'is_fully_approved' => $program->isFullyApproved(),
                'name' => $program->name,
                'proposing_organization_id' => $program->proposing_organization_id,
                'recipient_organization_ids' => $program->recipientContributors()->pluck('organization_id')->toJson(),
                'site_ids' => $program->meetings->pluck('site_id')->whereNotNull()->unique()->toJson(),
                'location_ids' => $program->meetings->pluck('location_id')->whereNotNull()->unique()->toJson(),
                'start_date' => $program->start_date,
                'end_date' => $program->end_date,
                'start_time' => $program->start_time,
                'end_time' => $program->end_time,
                'meeting_start_dates' => $program->meetings->pluck('start_datetime')->toJson(),
                'meeting_count' => $program->meetings->count(),
            ]);
        });
        return $result;
    }
}
