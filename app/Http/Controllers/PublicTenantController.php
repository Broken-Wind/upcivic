<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Tenant;
use Illuminate\Http\Request;

class PublicTenantController extends Controller
{
    //
    public function index()
    {
        return redirect()->route('tenant:admin.resource_timeline.index', tenant()->slug);
    }
}
