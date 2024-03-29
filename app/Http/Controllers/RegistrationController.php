<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tenant;
use App\Program;

class RegistrationController extends Controller
{
    //
    public function index()
    {
        return view('tenant.programs.index');
    }

    public function show(Program $program)
    {
        return view('tenant.programs.show', compact('program'));
    }
}
