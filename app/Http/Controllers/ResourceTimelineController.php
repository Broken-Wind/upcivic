<?php

namespace App\Http\Controllers;

use App\Program;
use App\Services\DemoService;
use App\Services\ResourceTimelineService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ResourceTimelineController extends Controller
{
    //
    protected $demoService;
    protected $resourcetimelineService;

    public function __construct(DemoService $demoService, ResourceTimelineService $resourcetimelineService)
    {
        $this->demoService = $demoService;
        $this->resourcetimelineService = $resourcetimelineService;
    }

    public function index()
    {
        abort_if(!tenant()->isSubscribed(), 401);
        $resources = $this->resourcetimelineService->getResources();
        $events = $this->resourcetimelineService->getEvents();

        return view('tenant.admin.resource_timeline.index', compact('resources', 'events'));
    }

    public function meetings()
    {
        abort_if(!tenant()->isSubscribed(), 401);

        $resources = $this->resourcetimelineService->getResources();
        $initialDate = Carbon::now()->startOfWeek(Carbon::SUNDAY);
        $endDate = $initialDate->copy()->addDays(7);

        $meetingEvents = $this->resourcetimelineService->getMeetingsEvents($initialDate, $endDate);

        return view('tenant.admin.resource_timeline.meetings', compact('resources', 'meetingEvents', 'initialDate'));
    }

    public function page(Request $request) {
        try {
            abort_if(!tenant()->isSubscribed(), 401);

            $initialDate = Carbon::createFromFormat('m/d/Y h:i:s A', $request->initial_date);
            $endDate = Carbon::parse($request->endDate);

            $meetingEvents = $this->resourcetimelineService->getMeetingsEvents($initialDate, $endDate);

        } catch (Exception $e) {

            return json_encode($e);
        }
        return $meetingEvents;
    }
}
