<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DemoService;

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
    }
}
