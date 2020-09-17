@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header">Create Assignable Task</div>

        <div class="card-body">

            @include('shared.form_errors')

            <div class="form-group">
                <label for="task-name">Task Name</label>
                <input type="text"
                class="form-control" name="task-name" id="task-name" aria-describedby="taskName" placeholder="Submit Background Check Authorization">
            </div>

            <div class="form-group">
                <label for="task-description">Task Description</label>
                <textarea class="form-control" name="task-description" id="task-description" rows="3"></textarea>
            </div>

            <div class="form-group mt-3">
              <label for="upload-documents">Upload Documents</label>
              <input type="file" class="form-control-file" name="upload-documents" id="upload-documents" placeholder="Background Check Authorization.pdf" aria-describedby="uploadDocuments" multiple>
              <small id="uploadDocuments" class="form-text text-muted">Upload any documents which will be needed to complete this task, such as a blank background check authorization form or an example of a valid liability insurance policy.</small>
            </div>

            <div class="form-check">
                <label class="form-check-label">
                <input type="radio" class="form-check-input" name="" id="" value="checkedValue" checked>
                This task should be assigned to organizations as a whole. (Liability insurance, tax docs)
              </label>
            </div>

            <div class="form-check">
                <label class="form-check-label">
                <input type="radio" class="form-check-input" name="" id="" value="checkedValue">
                This task should be assigned to each instructor. (Fingerprinting, TB tests)
              </label>
            </div>

            <button type="button" class="btn btn-primary">Create Assignable Task</button>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">Upload Liability Insurance</div>

        <div class="card-body">
            <strong>Requesting Organization:</strong> City of San Mateo <br>
            <strong>Description:</strong> Please have each of your instructors sign and upload a background check authorization form at least 1 full week prior to their first date at camp.
            <br><br>
            <div class="form-group">
                <label for="add-coment">Add a Comment</label>
                <div class="input-group">
                    <textarea class="form-control" aria-label="With textarea"></textarea>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-secondary"><i class="fas fa-fw fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>
            <div class="alert alert-info">
                That's it! Thanks! <br>
                <small class="text-muted">3 minutes ago by Angela Sakkos, City of San Mateo</small>
            </div>

            <div class="alert alert-info">
                I think this is the right form. Let me know. <br>
                <small class="text-muted">20 minutes ago by Greg Intermaggio, Techsplosion</small>
            </div>

            <div class="form-group">
              <label for="supporting-documents">Supporting Documents</label>
              <li><a href="">Jeremey's Background Check Authorization.pdf</a> <button type="button" class="btn btn-sm btn-danger">X</button></li>
              <li><a href="">Greg's Background Check Authorization.pdf</a> <button type="button" class="btn btn-sm btn-danger">X</button></li>
              <li><a href="">Susan's Background Check Authorization.pdf</a> <button type="button" class="btn btn-sm btn-danger">X</button></li>
              <input type="file" class="form-control-file" name="supporting-documents" id="supporting-documents" placeholder="Background Check Authorization.pdf">
            </div>
            <button type="button" class="btn btn-primary">Mark Complete</button>
        </div>
    </div>
</div>
@endsection
