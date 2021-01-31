<?php

namespace App\Http\Controllers;

use App\Program;
use App\Services\DemoService;
use App\Services\ResourceTimelineService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Purify\Facades\Purify;

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
        $resources = Purify::clean($this->resourcetimelineService->getResources());
        $events = Purify::clean($this->resourcetimelineService->getEvents());

        return view('tenant.admin.resource_timeline.index', compact('resources', 'events'));
    }

    public function meetings()
    {
        abort_if(!tenant()->isSubscribed(), 401);

        $resources = $this->resourcetimelineService->getResources();

        $user = Auth::user();
        $userInitialDate = Carbon::parse($user->calendar_initial_date);
        if (!empty($userInitialDate)) {
            $initialDate = $userInitialDate;
        } else {
            $initialDate = Carbon::now()->startOfWeek(Carbon::SUNDAY);
        }
        $endDate = $initialDate->copy()->addDays(7);

        $meetings = Purify::clean($this->resourcetimelineService->getMeetingsEvents($initialDate, $endDate)['meetings']->toJson());
        $programs = Purify::clean($this->resourcetimelineService->getMeetingsEvents($initialDate, $endDate)['programs']->toJson());
        // dd($meetingEvents);
        return view('tenant.admin.resource_timeline.meetings', compact('resources', 'meetings', 'programs', 'initialDate', 'userInitialDate'));
    }

    public function page(Request $request) {

        try {
            abort_if(!tenant()->isSubscribed(), 401);

            $initialDate = Carbon::parse($request->initial_date);

            $user = Auth::user();
            $user->calendar_initial_date = $initialDate;
            $user->save();

            $endDate = Carbon::parse($request->end_date);

            $meetingEvents = $this->resourcetimelineService->getMeetingsEvents($initialDate, $endDate);

        } catch (Exception $e) {

            return json_encode($e);
        }
        return $meetingEvents;
    }
}
