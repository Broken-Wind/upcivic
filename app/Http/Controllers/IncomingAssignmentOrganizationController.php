<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Instructor;
use App\InstructorAssignment;
use App\Task;
use App\Organization;

class IncomingAssignmentOrganizationController extends Controller
{
    public function index(Organization $organization)
    {
        $isOutgoingFromTenant = false;
        $instructors = Instructor::assignedToOrganization($organization->id)->with('assignments.parentAssignment')->get();
        return view('tenant.admin.assignments.organizations.index', compact('organization', 'isOutgoingFromTenant', 'instructors'));
    }

}
