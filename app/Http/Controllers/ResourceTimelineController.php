<?php

namespace App\Http\Controllers;

use App\Program;
use App\Services\DemoService;
use App\Services\ResourceTimelineService;
use Carbon\Carbon;
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
}
