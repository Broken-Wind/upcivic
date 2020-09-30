<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Organization;

class OutgoingAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Tenant outgoing assignments are incoming assignments to the other organization
        $organizations = Organization::partneredWith(tenant()->organization_id)->whereHas('incomingAssignments')->with('incomingAssignments')->get();
        $isOutgoingFromTenant = true;
        return view('tenant.admin.assignments.index', compact('organizations', 'isOutgoingFromTenant'));
    }

    // public function create()
    // {
    //     return view('tenant.admin.tasks.create');
    // }

    // public function edit(Task $task)
    // {
    //     //

    //     return view('tenant.admin.tasks.edit', compact('template'));
    // }

    // public function destroy(Task $task)
    // {
    //     //

    //     $task->delete();

    //     return redirect()->route('tenant:admin.tasks.index', tenant()['slug'])->withSuccess('Task has been deleted.');
    // }

}
