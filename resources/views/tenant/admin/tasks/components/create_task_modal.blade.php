<div class="modal fade" id="create-task-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5>Add Assignable Task</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body" id="modal-body">
                <form method="POST" action="{{ tenant()->route('tenant:admin.tasks.store') }}" enctype="multipart/form-data" id="createOrUpdateTask">
                    @csrf
                    <div class="form-group">
                        <label for="taskName">Task Name</label>
                        <input type="text"
                        class="form-control" name="name" id="taskName" placeholder="Background Check" required>
                    </div>

                    <div class="form-group">
                        <label for="taskDescription">Task Instructions</label>
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
                    <small id="helpTaskDescription" class="form-text text-muted">Signable documents can only be created for tasks assigned to organizations. They may not be created for instructor tasks.</small>

                    <!-- Create the editor container -->
                    <div class="documentContainer" style="display:none;">
                        @include('tenant.admin.tasks.components.document_editor')
                    </div>

                    <div class="form-group mt-3">
                        <label for="files">Additional Documents <span class="text-muted">(optional)</span></label>
                        <input type="file" class="form-control-file" name="files[]" id="files" placeholder="Background Check Authorization.pdf" aria-describedby="helpFiles" multiple>
                    </div>
                    <button type="submit" id="submit" class="btn btn-primary">Add Task</button>
                </form>
            </div>
        </div>
    </div>
</div>
