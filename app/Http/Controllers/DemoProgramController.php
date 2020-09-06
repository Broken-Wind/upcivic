<?php

namespace App\Http\Controllers;

use App\Services\DemoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemoProgramController extends Controller
{
    public function __construct(DemoService $demoService)
    {
        $this->demoService = $demoService;
    }

    //
    public function store()
    {
        abort_if(!Auth::user()->canGenerateDemoData(), 401);
        $this->demoService->regenerateDemoData();
        return redirect()->route('tenant:admin.home', \Auth::user()->tenants()->first()->slug);
    }
}
