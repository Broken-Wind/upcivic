@extends('layouts.app')
@section('title', 'Add Task')
@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header">Add Assignable Task</div>

        <div class="card-body">
            <form method="POST" action="{{ tenant()->route('tenant:admin.tasks.store') }}">
                @csrf
                @include('shared.form_errors')

                <div class="form-group">
                    <label for="taskName">Task Name</label>
                    <input type="text"
                    class="form-control" name="name" id="taskName" placeholder="Submit Background Check Authorization" required>
                </div>

                <div class="form-group mt-3">
                <label for="uploadDocuments">Upload Documents <span class="text-muted">(optional)</span></label>
                <input type="file" class="form-control-file" name="uploadDocuments[]" id="uploadDocuments" placeholder="Background Check Authorization.pdf" aria-describedby="helpUploadDocuments" multiple>
                <small id="helpUploadDocuments" class="form-text text-muted">Upload any documents which will be needed to complete this task, such as a blank background check authorization form or an example of a valid liability insurance policy.</small>
                </div>

                <div class="form-group">
                    <label for="taskDescription">Task Description</label>
                    <textarea class="form-control" name="description" id="taskDescription" rows="3" required aria-describedby="helpTaskDescription"></textarea>
                    <small id="helpTaskDescription" class="form-text text-muted">Provide details including how to use any attached documents.</small>
                </div>

                <div class="form-check">
                    <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="assignToEntity" id="assignToOrganization" value="Organization" required>
                    This task should be assigned to organizations as a whole. (Liability insurance, tax docs)
                </label>
                </div>

                <div class="form-check mb-4">
                    <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="assignToEntity" id="assignToInstructor" value="Instructor">
                    This task should be assigned to each instructor. (Fingerprinting, TB tests)
                </label>
                </div>

                <button type="submit" class="btn btn-primary">Add Task</button>
            </form>
        </div>
    </div>
</div>
@endsection