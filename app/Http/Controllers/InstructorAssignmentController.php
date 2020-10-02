<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\InstructorAssignment;
use Illuminate\Support\Facades\Auth;

class InstructorAssignmentController extends Controller
{
    //
    public function complete(InstructorAssignment $assignment)
    {
        $assignment->complete(Auth::user());
        return back()->withSuccess('Marked complete!');
    }
    public function approve(InstructorAssignment $assignment)
    {
        $assignment->approve(Auth::user());
        return back()->withSuccess('Marked complete!');
    }

}
