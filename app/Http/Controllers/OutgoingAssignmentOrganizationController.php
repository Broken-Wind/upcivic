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
        $instructors = $organization->instructors()->assignedToOrganization(tenant()->organization_id)->get();
        return view('tenant.admin.assignments.organizations.index', compact('organization', 'isOutgoingFromTenant', 'instructors'));
    }

}
