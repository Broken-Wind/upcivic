<?php
namespace Upcivic\Services;

use Upcivic\Program;

class ResourceTimelineService {
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
                    'title' => $location->name
                ];
            })->prepend([
                'id' => '0_' . $site->id,
                'site' => $site->name,
                'title' => 'Location TBD'
            ]);
        })->flatten(1)->prepend([
            'id' => 0,
            'site' => 'Site TBD',
            'title' => ' '
        ]);
    }
    public function getEvents()
    {
        return Program::with(['meetings.site', 'contributors.organization'])->get()->sortBy('start_datetime')->map(function ($program) {
            return [
                'resourceId' => $program->location_id,
                'title' => $program->internal_name,
                'start' => $program->start_datetime,
                'end' => $program->end_datetime
            ];
        })->values()->toJson();
    }
}
