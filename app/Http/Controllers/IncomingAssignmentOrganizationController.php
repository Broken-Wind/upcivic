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
        $instructors = tenant()->organization->instructors()->assignedToOrganization($organization->id)->with(['incomingAssignments.parentAssignment', 'incomingAssignments.assignedByOrganization'])->get();
        return view('tenant.admin.assignments.organizations.index', compact('organization', 'isOutgoingFromTenant', 'instructors'));
    }

}
