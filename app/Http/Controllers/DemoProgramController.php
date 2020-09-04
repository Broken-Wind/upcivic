<?php

namespace App\Http\Controllers;

use App\Services\DemoService;
use Illuminate\Http\Request;

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
        return redirect()->route('tenant:admin.home', \Auth::user()->tenants()->first()->slug);
    }
}
