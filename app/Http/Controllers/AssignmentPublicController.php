<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssignmentPublicController extends Controller
{
    //
    public function complete(Assignment $assignment)
    {
        $assignment->complete();
        return back()->withSuccess('Marked complete!');
    }
}
