<?php

namespace App\Http\Controllers;

use App\Instructor;
use App\Task;
use App\Organization;

class OutgoingAssignmentOrganizationController extends Controller
{
    public function index(Organization $organization)
    {
        $isOutgoingFromTenant = true;
        $instructors = Instructor::assignedToOrganization(tenant()->organization_id)->get();
        return view('tenant.admin.assignments.organizations.index', compact('organization', 'isOutgoingFromTenant', 'instructors'));
    }

}
