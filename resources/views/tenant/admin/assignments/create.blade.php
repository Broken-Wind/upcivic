@extends('layouts.app')
@section('title', 'Add Task')
@include('tenant.admin.tasks.components.document_head_content')
@section('content')
<div class="container">
    @include('tenant.admin.tasks.components.create_task_modal')
    @include('shared.form_errors')
    <div class="card mb-4">
        <div class="card-header">Assign a Task</div>

        <div class="card-body">
            @if($tasks->count() > 0)

                @if(tenant()->organization->partners->count() > 0)
                    <form method="GET" action="{{ tenant()->route('tenant:admin.assignments.review') }}" enctype="multipart/form-data" id="createOrUpdateTask">

                        <div class="form-group">
                            <label for="taskName">Task</label>
                            <select class="form-control" name="task_id" id="taskSelector">
                                @foreach($tasks as $task)
                                    <option value="{{ $task->id }}"{{ request()->has('task_id') && request()->task_id == $task->id ? ' selected' : '' }}>{{ $task->name }}</option>
                                @endforeach
                            </select>
                            <small id="add-task" class="text-muted">Can't find the task you'd like? <a href="" data-toggle="modal" data-target="#create-task-modal">Add a task template</a></small>
                        </div>
                            <div class="form-group">
                                <label for="taskName">Assign to Organizations:</label>
                                @foreach(tenant()->organization->partners as $organization)
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="organization_ids[]" value="{{ $organization->id }}">
                                            {{ $organization->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <button type="submit" id="submit" class="btn btn-primary">Review</button>
                    </form>
                @else
                    You must have an upcoming program with an organization to assign a task to them. Please <a href="{{ tenant()->route('tenant:admin.programs.create') }}">propose a program</a> to another organization.
                @endif
            @else
                In order to assign a task, you'll first need to <a href="" data-toggle="modal" data-target="#create-task-modal">Add a task template</a>.
            @endif
        </div>
    </div>
</div>
@endsection

