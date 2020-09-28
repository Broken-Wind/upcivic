<div class="modal fade" id="task-assignments-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5><a href="" target="_blank" class="task-title" id="task-assignments-modal-title"></a></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body" id="modal-body">
                <input type="hidden" name="taskAssignmentsId" value="" />
                @forelse($organizations as $organization)
                    <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="assignedOrganizations[]" value="1" checked>
                        {{ $organization->name }}
                    </label>
                    </div>
                @empty
                @endforelse
            </div>
            <div class="modal-footer" style="">
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="approve-program">Update Assignments</button>
            </div>
        </div>
    </div>
</div>
