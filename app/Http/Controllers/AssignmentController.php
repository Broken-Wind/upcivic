<?php

namespace App\Http\Controllers;

use App\Organization;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = Organization::partneredWith(tenant()->organization_id)->orderBy('name')->with(['incomingAssignments', 'outgoingAssignments'])->get();
        return view('tenant.admin.assignments.index', compact('organizations'));
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
