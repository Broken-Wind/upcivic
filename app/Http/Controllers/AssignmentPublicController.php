<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssignmentPublicController extends Controller
{
    //
    
    public function edit(Request $request, Assignment $assignment)
    {
        abort_if(!$request->hasValidSignature(), 401);

        $routeActionString = "tenant:admin.assignments.";

        $programs = null;
        if ($assignment->isSignableDocument()) {
            $programs = Program::whereIn('id', $assignment->signableDocument->program_ids)->get();
        }
        return view('tenant.assignments.public_edit', compact('assignment', 'programs', 'routeActionString')); 
    }
    
    public function complete(Assignment $assignment)
    {
        $assignment->complete();
        return back()->withSuccess('Marked complete!');
    }
}
