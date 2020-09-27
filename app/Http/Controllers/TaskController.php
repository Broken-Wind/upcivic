<?php

namespace App\Http\Controllers;

use App\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = [
            'Assigned staff must provide negative TB test result',
            'Sign a contract',
            'Provide a local business license',
            'Provide liability insurance policy',
            'Provide an affidavit'
        ];
        return view('tenant.admin.tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tenant.admin.tasks.create');
    }

    public function store()
    {
        dd('heyo');
        return;
    }

    public function edit(Task $task)
    {
        //

        return view('tenant.admin.tasks.edit', compact('template'));
    }

    public function destroy(Task $task)
    {
        //

        $task->delete();

        return redirect()->route('tenant:admin.tasks.index', tenant()['slug'])->withSuccess('Task has been deleted.');
    }

}
