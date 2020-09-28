<?php

namespace App\Http\Controllers;

use App\Task;
use App\Organization;

class OrganizationOutgoingAssignmentController extends Controller
{
    public function index(Organization $org, Task $task)
    {
        return view('tenant.admin.organizations.outgoing_assignments.index', compact('org', 'task'));
    }

}