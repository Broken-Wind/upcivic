<?php

namespace App\Http\Controllers;

use App\Organization;
use App\Task;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = Organization::all();
        return view('tenant.admin.assignments.index', compact('organizations'));
    }

    // public function create()
    // {
    //     return view('tenant.admin.tasks.create');
    // }

    // public function store()
    // {
    //     dd('heyo');
    //     return;
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
