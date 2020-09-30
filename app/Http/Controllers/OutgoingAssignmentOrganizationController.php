<?php

namespace App\Http\Controllers;

use App\Task;
use App\Organization;

class OutgoingAssignmentOrganizationController extends Controller
{
    public function index(Organization $organization)
    {
        $isOutgoingFromTenant = true;
        return view('tenant.admin.assignments.organizations.index', compact('organization', 'isOutgoingFromTenant'));
    }

}
