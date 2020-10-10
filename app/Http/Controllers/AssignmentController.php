<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    //
    public function complete(Assignment $assignment)
    {
        $assignment->complete(Auth::user());
        return back()->withSuccess('Marked complete!');
    }
    public function approve(Assignment $assignment)
    {
        $assignment->approve(Auth::user());
        return back()->withSuccess('Marked complete!');
    }

    public function edit(Assignment $assignment)
    {
        $isOutgoingFromTenant = $assignment->assigned_by_organization_id == tenant()->organization_id;
        $routeActionString = 'tenant:admin.assignments.';
        return view('tenant.admin.assignments.edit', compact('assignment', 'isOutgoingFromTenant', 'routeActionString'));
    }

}
