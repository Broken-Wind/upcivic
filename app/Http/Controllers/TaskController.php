<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTask;
use App\Http\Requests\UpdateTask;
use App\Organization;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::all();
        $organizations = Organization::all();
        return view('tenant.admin.tasks.index', compact('tasks', 'organizations'));
    }

    public function create()
    {
        return view('tenant.admin.tasks.create');
    }

    public function store(StoreTask $request)
    {
        $validated = $request->validated();
        $task = Task::make([
            'name' => $validated['name'],
            'description' => $validated['description'],
            ]);
        $task->organization_id = tenant()->organization->id;
        $task->assign_to_entity = $validated['assignToEntity'];
        $task->save();
        return redirect()->route('tenant:admin.tasks.index', [tenant()->slug])->withSuccess('Task created successfully.');
    }

    public function edit(Task $task)
    {
        //

        return view('tenant.admin.tasks.edit', compact('task'));
    }


    public function update(Task $task, UpdateTask $request)
    {
        //
        $validated = $request->validated();
        $task->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            ]);

        return redirect()->route('tenant:admin.tasks.index', tenant()['slug'])->withSuccess('Task has been updated.');
    }

    public function archive(Task $task)
    {
        //

        $task->archived_at = Carbon::now();
        $task->save();

        return redirect()->route('tenant:admin.tasks.index', tenant()['slug'])->withSuccess('Task has been archived.');
    }

    public function destroy(Task $task)
    {
        //

        $task->delete();

        return redirect()->route('tenant:admin.tasks.index', tenant()['slug'])->withSuccess('Task has been deleted.');
    }

}
