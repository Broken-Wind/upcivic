<?php

namespace Upcivic\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Upcivic\Program;
use Upcivic\Services\DemoService;
use Upcivic\Services\CalendarService;

class CalendarController extends Controller
{
    //
    protected $demoService;
    protected $calendarService;
    public function __construct(DemoService $demoService, CalendarService $calendarService)
    {
        $this->demoService = $demoService;
        $this->calendarService = $calendarService;
    }
    public function index()
    {
        $resources = $this->calendarService->getResources();
        $events = $this->calendarService->getEvents();
        return view('tenant.admin.programs.calendar', compact('resources', 'events'));
    }
}
