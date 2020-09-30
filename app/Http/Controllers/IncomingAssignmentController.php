<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\Organization;

class IncomingAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = Assignment::with('assignedByOrganization')->where('assigned_by_organization_id', '!=', tenant()->organization_id)->get()->groupBy(function ($assignment, $key) {
            return $assignment->assignedByOrganization->name;
        });
        $isOutgoingAssignments = false;
        return view('tenant.admin.assignments.index', compact('organizations', 'isOutgoingAssignments'));
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
