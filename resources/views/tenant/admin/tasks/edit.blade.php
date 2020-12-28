@extends('layouts.app')
@section('title', 'Edit Task')
@include('tenant.admin.tasks.components.document_head_content')
@section('content')
<div class="container">
    <a href="{{ tenant()->route('tenant:admin.tasks.index') }}">
        <i class="fas fa-angle-left pb-3"></i> Back to Tasks
    </a>
    @include('shared.form_errors')
    <div class="card mb-4">
        <div class="card-header">Edit Assignable Task</div>

        <div class="card-body">
            <form method="POST" id="updateTask" action="{{ tenant()->route('tenant:admin.tasks.update', [$task]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
            </form>
            <form method="POST" action="{{ tenant()->route('tenant:admin.tasks.archive', ['task' => $task]) }}" id="archiveTask">
                @csrf
            </form>

            <div class="form-group">
                <label for="taskName">Task Name</label>
                <input type="text" form="updateTask"
                class="form-control" name="name" id="taskName" value="{{ $task->name }}" placeholder="Background Check" required>
            </div>
            <div class="form-group">
                <label for="taskDescription">Task Instructions</label>
                <textarea class="form-control" form="updateTask" name="description" id="taskDescription" rows="3" required aria-describedby="helpTaskDescription">{{ $task->description }}</textarea>
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
                    @include('tenant.admin.tasks.components.document_editor', ['form' => 'updateTask'])
                </div>
            @endif

            <div class="form-group mt-3">
                <label for="files">Additional Documents <span class="text-muted">(optional)</span></label>
                <input type="file" form="updateTask" class="form-control-file" name="files[]" id="files" placeholder="Background Check Authorization.pdf" aria-describedby="helpFiles" multiple>
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
            None<br>
            @endforelse
            <div class="row">
                <div class="col mt-3">
                    <button type="submit" form="updateTask" class="btn btn-primary">Update</button>
                    <button type="submit" form="archiveTask" class="btn btn-secondary">Archive</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
