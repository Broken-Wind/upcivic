<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Task;
use App\Organization;

class IncomingAssignmentOrganizationController extends Controller
{
    public function index(Organization $organization)
    {
        $isOutgoingFromTenant = false;
        return view('tenant.admin.assignments.organizations.index', compact('organization', 'isOutgoingFromTenant'));
    }

}
