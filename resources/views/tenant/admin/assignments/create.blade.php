@extends('layouts.app')
@section('title', 'Add Task')
@include('tenant.admin.tasks.components.document_head_content')
@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header">Assign a Task</div>

        <div class="card-body">
            <form method="GET" action="{{ tenant()->route('tenant:admin.assignments.review') }}" enctype="multipart/form-data" id="createOrUpdateTask">
                @include('shared.form_errors')

                <div class="form-group">
                    <label for="taskName">Task</label>
                    <select class="form-control" name="task_id" id="taskSelector">
                        @foreach($tasks as $task)
                            <option value="{{ $task->id }}">{{ $task->name }}</option>
                        @endforeach
                    </select>
                    <small id="add-task" class="text-muted">Can't find the task you'd like? <a href="" data-toggle="modal" data-target="#add-task-modal">Add a task </a></small>
                </div>
                <div class="form-group">
                    <label for="taskName">Assign to Organizations:</label>
                    @forelse(tenant()->organization->partners as $organization)
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="organization_ids[]" value="{{ $organization->id }}">
                                {{ $organization->name }}
                            </label>
                        </div>
                    @empty
                        No partners found.
                    @endforelse
                </div>

                <button type="submit" id="submit" class="btn btn-primary">Review</button>
            </form>
        </div>
    </div>
</div>
@endsection

