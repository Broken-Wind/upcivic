<?php

namespace Upcivic\Http\Controllers;

use Illuminate\Http\Request;
use Upcivic\Services\DemoService;

class DemoProgramController extends Controller
{
    public function __construct(DemoService $demoService)
    {
        $this->demoService = $demoService;
    }
    //
    public function store()
    {
        $this->demoService->regenerateDemoData();
        return back();
    }
}
