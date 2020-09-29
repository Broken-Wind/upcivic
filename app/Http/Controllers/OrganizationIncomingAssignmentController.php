<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Task;
use App\Organization;

class OrganizationIncomingAssignmentController extends Controller
{
    public function index(Organization $organization)
    {
        return view('tenant.admin.organizations.assigned_to.index', compact('organization'));
    }

}
