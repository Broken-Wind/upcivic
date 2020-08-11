<?php

namespace Upcivic\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Upcivic\Program;
use Upcivic\Services\DemoService;
use Upcivic\Services\ResourceTimelineService;

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
        $resources = $this->resourcetimelineService->getResources();
        $events = $this->resourcetimelineService->getEvents();

        return view('tenant.admin.programs.resource_timeline', compact('resources', 'events'));
    }
}
