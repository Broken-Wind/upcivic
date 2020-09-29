<?php

namespace App\Http\Controllers;

use App\Task;
use App\Organization;

class OrganizationOutgoingAssignmentController extends Controller
{
    public function index(Organization $organization)
    {
        return view('tenant.admin.organizations.assigned_by.index', compact('organization'));
    }

}
