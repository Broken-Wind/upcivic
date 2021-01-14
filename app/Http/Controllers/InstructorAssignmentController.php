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

    public function edit(InstructorAssignment $assignment)
    {
        $isOutgoingFromTenant = $assignment->assigned_by_organization_id == tenant()->organization_id;
        $routeActionString = 'tenant:admin.instructor_assignments.';
        return view('tenant.admin.assignments.edit', compact('assignment', 'isOutgoingFromTenant', 'routeActionString'));
    }

    public function destroy(InstructorAssignment $assignment)
    {
        $isOutgoingFromTenant = $assignment->assignedByOrganization->id == tenant()->organization_id;
        $redirectToOrganization = $isOutgoingFromTenant ? $assignment->assignedToOrganization->id : $assignment->assignedByOrganization->id;
        $assignment->delete();
        if ($isOutgoingFromTenant) {
            return redirect()->route('tenant:admin.assignments.to.organizations.index', [tenant()->slug, $redirectToOrganization]);
        } else {
            return redirect()->route('tenant:admin.assignments.from.organizations.index', [tenant()->slug, $redirectToOrganization]);
        }
    }
}
