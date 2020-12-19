@extends('layouts.app')
@section('title', 'Edit Task')
@include('tenant.admin.tasks.components.document_head_content')
@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header">Edit Assignable Task</div>

        <div class="card-body">
            <form method="POST" id="createOrUpdateTask" action="{{ tenant()->route('tenant:admin.tasks.update', [$task]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('shared.form_errors')
            </form>
            <form method="POST" action="{{ tenant()->route('tenant:admin.tasks.archive', ['task' => $task]) }}" id="archiveTask">
                @csrf
            </form>

            <div class="form-group">
                <label for="taskName">Task Name</label>
                <input type="text" form="createOrUpdateTask"
                class="form-control" name="name" id="taskName" value="{{ $task->name }}" placeholder="Submit Background Check Authorization" required>
            </div>

            <div class="form-group mt-3">
                <label for="files">Upload Documents <span class="text-muted">(optional)</span></label>
                <input type="file" form="createOrUpdateTask" class="form-control-file" name="files[]" id="files" placeholder="Background Check Authorization.pdf" aria-describedby="helpFiles" multiple>
                <small id="helpFiles" class="form-text text-muted">Upload any documents which will be needed to complete this task, such as a blank background check authorization form or an example of a valid liability insurance policy.</small>
            </div>

            @forelse($task->files as $file)
                <a href="{{ $file->download_link }}">{{ $file->filename }}</a>
                @if($file->canDelete(\Auth::user()))
                    <form method="POST" id="delete_file_{{ $file->id }}" action="{{ tenant()->route('tenant:admin.files.destroy', [$file]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('DELETE')
                        <button type="submit" form="delete_file_{{ $file->id }}" class="btn btn-danger">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </form>
                @endif
            @empty
            None
            @endforelse
            <div class="form-group">
                <label for="taskDescription">Task Description</label>
                <textarea class="form-control" form="createOrUpdateTask" name="description" id="taskDescription" rows="3" required aria-describedby="helpTaskDescription">{{ $task->description }}</textarea>
                <small id="helpTaskDescription" class="form-text text-muted">Provide details including how to use any attached documents.</small>
            </div>

            <div class="form-check">
                <label class="form-check-label">
                <input type="radio" class="form-check-input" name="assignToEntity" id="assignToOrganization" value="{{ \App\Organization::class }}" disabled  {{ $task->assign_to_entity == \App\Organization::class ? 'checked' : '' }}>
                This task should be assigned to organizations as a whole. (Liability insurance, tax docs)
            </label>
            </div>

            <div class="form-check mb-4">
                <label class="form-check-label">
                <input type="radio" class="form-check-input" name="assignToEntity" id="assignToInstructor" value="{{ \App\Intructor::class }}" disabled {{ $task->assign_to_entity == \App\Instructor::class ? 'checked' : '' }}>
                This task should be assigned to each instructor. (Fingerprinting, TB tests)
            </label>
            </div>

            @if($task->type == 'signable_document')
                <div class="documentContainer">
                    @include('tenant.admin.tasks.components.document_editor')
                </div>
            @endif

            <button type="submit" id="submit" form="createOrUpdateTask" class="btn btn-primary">Update Task</button>
            <button type="submit" form="archiveTask" class="btn btn-secondary">Archive Task</button>
        </div>
    </div>
</div>
@endsection
