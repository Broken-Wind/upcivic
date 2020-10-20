<div class="modal fade" id="add-instructor-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="instructor-title" id="instructor-modal-title">Instructor</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            <div class="modal-body" id="modal-body">
                <form method="POST" action="{{ tenant()->route('tenant:admin.instructors.store') }}">
                    @csrf
                    <input type="hidden" name="assign_to_organization_id" value="{{ isset($organization) ? $organization->id : '' }}">
                    <div class="form-group">
                        <label for="first-name">First Name</label>
                        <input type="text" class="form-control" name="first_name" required placeholder="Paul">
                    </div>
                    <div class="form-group">
                        <label for="last-name">Last Name</label>
                        <input type="text" class="form-control" name="last_name" required placeholder="Smith">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email"required placeholder="p.smith@fakeville.gov">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" name="phone" placeholder="415-555-5555">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Add Instructor</button>
                </form>
            </div>
        </div>
    </div>
</div>
