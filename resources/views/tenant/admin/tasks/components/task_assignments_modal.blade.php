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
                <form id="storeAssignments" method="POST" action="{{ tenant()->route('tenant:admin.assignments.store') }}">
                    @csrf
                    <input type="hidden" name="assignTaskId" id="assignTaskId" value="" />
                    @forelse($organizations as $organization)
                        <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input assignment-checkbox" name="assignToOrganizationIds[]" value="{{ $organization->id }}">
                            {{ $organization->name }}
                        </label>
                        </div>
                    @empty
                    @endforelse
                </form>
            </div>
            <div class="modal-footer" style="">
                <button type="submit" class="btn btn-primary" form="storeAssignments" id="approve-program">Update Assignments</button>
            </div>
        </div>
    </div>
</div>
