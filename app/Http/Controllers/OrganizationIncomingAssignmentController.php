<?php

namespace App\Http\Controllers;

use App\Task;
use App\Organization;

class OrganizationIncomingAssignmentController extends Controller
{
    public function index(Organization $organization)
    {
        return view('tenant.admin.organizations.incoming_assignments.index', compact('organization'));
    }

}
