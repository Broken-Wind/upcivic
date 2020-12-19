<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Requests\StoreTask;
use App\Http\Requests\UpdateTask;
use App\Instructor;
use App\Organization;
use App\Task;
use App\Services\TaskService;
use App\SignableDocument;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(!tenant()->isSubscribed(), 401);

        $tasks = Task::all();
        $organizations = Organization::partneredWith(tenant()->organization_id)->orderBy('name')->get();
        $taskJson = $this->taskService->getIndexJson();
        return view('tenant.admin.tasks.index', compact('tasks', 'organizations', 'taskJson'));
    }

    public function create()
    {
        abort_if(!tenant()->isSubscribed(), 401);

        return view('tenant.admin.tasks.create');
    }

    public function store(StoreTask $request)
    {
        abort_if(!tenant()->isSubscribed(), 401);

        $validated = $request->validated();
        $task = Task::make([
            'name' => $validated['name'],
            'description' => $validated['description'],
            ]);
        $task->organization_id = tenant()->organization->id;
        switch ($validated['assignToEntity']) {
            case Instructor::class:
                $task->assign_to_entity = Instructor::class;
                break;
            default:
                $task->assign_to_entity = Organization::class;
                break;
        }
        switch (true) {
            case !empty($validated['isDocument']):
                $task->type = 'signable_document';
                $task->save();
                $task->signableDocument()->create([
                    'title' => $validated['documentTitle'],
                    'content' => $validated['documentContent']
                ]);
                break;
            default:
                $task->type = 'generic_assignment';
                $task->save();
                break;
        }
        if ($request->hasFile('files')) {
            foreach($validated['files'] as $document) {
                $path = Storage::putFile(File::getAdminStoragePath(), $document);
                $file = File::make([
                    'path' => $path,
                    'filename' => $document->getClientOriginalName()
                ]);
                $file->user_id = Auth::user()->id;
                $file->organization_id = tenant()->organization_id;
                $file->entity_type = Task::class;
                $file->entity_id = $task->id;
                $file->save();
            }
        }
        return redirect()->route('tenant:admin.tasks.index', [tenant()->slug])->withSuccess('Task created successfully.');
    }

    public function edit(Task $task)
    {
        //
        abort_if(!tenant()->isSubscribed(), 401);

        return view('tenant.admin.tasks.edit', compact('task'));
    }


    public function update(UpdateTask $request, Task $task)
    {
        //
        abort_if(!tenant()->isSubscribed(), 401);

        $validated = $request->validated();
        $task->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);
        if ($request->hasFile('files')) {
            foreach($validated['files'] as $document) {
                $path = Storage::putFile(File::getAdminStoragePath(), $document);
                $file = File::make([
                    'path' => $path,
                    'filename' => $document->getClientOriginalName()
                ]);
                $file->user_id = Auth::user()->id;
                $file->organization_id = tenant()->organization_id;
                $file->entity_type = Task::class;
                $file->entity_id = $task->id;
                $file->save();
            }
        }
        if ($task->type == 'signable_document') {
            $task->signableDocument->update([
                'title' => $validated['documentTitle'],
                'content' => $validated['documentContent'],
            ]);
        }

        return back()->withSuccess('Task has been updated.');
    }

    public function archive(Task $task)
    {
        //
        abort_if(!tenant()->isSubscribed(), 401);

        $task->archived_at = Carbon::now();
        $task->save();

        return redirect()->route('tenant:admin.tasks.index', tenant()['slug'])->withSuccess('Task has been archived.');
    }

    public function destroy(Task $task)
    {
        //
        abort_if(!tenant()->isSubscribed(), 401);

        $task->delete();

        return redirect()->route('tenant:admin.tasks.index', tenant()['slug'])->withSuccess('Task has been deleted.');
    }

}
