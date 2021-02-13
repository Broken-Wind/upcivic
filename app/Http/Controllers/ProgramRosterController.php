<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Program;
use Illuminate\Http\Request;

class ProgramRosterController extends Controller
{
    //
    public function edit(Program $program)
    {
        return view('tenant.admin.programs.roster.edit', compact('program'));
    }
}
