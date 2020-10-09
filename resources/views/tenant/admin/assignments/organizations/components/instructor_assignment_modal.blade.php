<div class="modal fade" id="instructor-assignment-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5><a href="" target="_blank" class="instructor-assignment-title" id="instructor-assignment-modal-title"></a>Assign Instructors</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body" id="modal-body">
                <form id="updateInstructorsAssignment" method="POST" action="{{tenant()->route('tenant:admin.organizations.assigned_instructors.mass_update', [$organization->id])}}">
                    @csrf
                    @forelse(tenant()->organization->instructors as $instructor)
                        <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input assignment-checkbox" name="assignInstructorIds[]" value="{{ $instructor->id }}" {{ $instructor->isAssignedTo($organization) ? "checked" : "" }}>
                            {{ $instructor->first_name }}
                            {{ $instructor->last_name }}
                        </label>
                        </div>
                    @empty
                    @endforelse
                </form>
            </div>
            <div class="modal-footer" style="">
                <button type="submit" class="btn btn-primary" form="updateInstructorsAssignment" id="update-instructors">Update Assignments</button>
            </div>
        </div>
    </div>
</div>
