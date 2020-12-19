@extends('layouts.app')
@section('title', 'Add Task')
@push('css')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush
@push('scripts')
    <!-- Include the Quill library -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js" type="application/javascript"></script>

    <!-- Initialize Quill editor -->
    <script type="application/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var quill = new Quill('#editor', {
            theme: 'snow'
        });
        document.getElementById('submit').addEventListener('click', function (event) {
            var documentContent = document.getElementById('documentContent');
            var editorElement = document.getElementById('editor');
            documentContent.innerHTML = editorElement.children[0].innerHTML;
        });
    });
    </script>
@endpush
@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header">Add Assignable Task</div>

        <div class="card-body">
            <form method="POST" action="{{ tenant()->route('tenant:admin.tasks.store') }}" enctype="multipart/form-data">
                @csrf
                @include('shared.form_errors')

                <div class="form-group">
                    <label for="taskName">Task Name</label>
                    <input type="text"
                    class="form-control" name="name" id="taskName" placeholder="Submit Background Check Authorization" required>
                </div>

                <div class="form-group mt-3">
                    <label for="files">Upload Documents <span class="text-muted">(optional)</span></label>
                    <input type="file" class="form-control-file" name="files[]" id="files" placeholder="Background Check Authorization.pdf" aria-describedby="helpFiles" multiple>
                    <small id="helpFiles" class="form-text text-muted">Upload any documents which will be needed to complete this task, such as a blank background check authorization form or an example of a valid liability insurance policy.</small>
                </div>

                <div class="form-group">
                    <label for="taskDescription">Task Description</label>
                    <textarea class="form-control" name="description" id="taskDescription" rows="3" required aria-describedby="helpTaskDescription"></textarea>
                    <small id="helpTaskDescription" class="form-text text-muted">Provide details including how to use any attached documents.</small>
                </div>

                <div class="form-check">
                    <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="assignToEntity" id="assignToOrganization" value="{{ \App\Organization::class }}" required>
                    This task should be assigned to organizations as a whole. (Liability insurance, tax docs)
                </label>
                </div>

                <div class="form-check mb-4">
                    <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="assignToEntity" id="assignToInstructor" value="{{ \App\Instructor::class }}">
                    This task should be assigned to each instructor. (Fingerprinting, TB tests)
                </label>
                </div>

                <div class="form-check">
                  <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" name="isDocument" id="isDocument" value="1" onClick="$('.documentContainer').toggle()">
                    Create a custom signable document for this task
                  </label>
                </div>

                <!-- Create the editor container -->
                <div class="documentContainer" style="display:none;">
                    <div class="form-group">
                        <label for="documentTitle">Document Title</label>
                        <input type="text"
                        class="form-control" name="documentTitle" id="documentTitle" aria-describedby="helpId" placeholder="Letter of Agreement">
                    </div>
                    <div class="form-group">
                        <label for="editor">Document Contents</label>
                        <div id="editor"></div>
                        <textarea name="documentContent" id="documentContent" style="display:none;"></textarea>
                    </div>
                </div>
                <button type="submit" id="submit" class="btn btn-primary">Add Task</button>
            </form>
        </div>
    </div>
</div>
@endsection
