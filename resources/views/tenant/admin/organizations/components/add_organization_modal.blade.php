<div class="modal fade" id="add-organization-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="organization-title" id="organization-modal-title">Organization</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            <div class="modal-body" id="modal-body">
                <form method="POST" action="{{ tenant()->route('tenant:admin.organizations.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="organization-name">Name</label>
                        <input type="text" class="form-control" name="name" id="organization-name" required  placeholder="Fakeville Community Center">
                    </div>
                    <div class="form-group">
                        <label for="administrator-first-name">Contact Person First Name</label>
                        <input type="text" class="form-control" name="administrator[first_name]" id="administrator-first-name" required placeholder="Paul">
                    </div>
                    <div class="form-group">
                        <label for="administrator-last-name">Contact Person Last Name</label>
                        <input type="text" class="form-control" name="administrator[last_name]" id="administrator-last-name" required placeholder="Smith">
                    </div>
                    <div class="form-group">
                        <label for="administrator-email">Email</label>
                        <input type="text" class="form-control" name="administrator[email]" id="administrator-email" required placeholder="p.smith@fakeville.gov">
                    </div>
                    <div class="form-group">
                        <label for="administrator-title">Title</label>
                        <input type="text" class="form-control" name="administrator[title]" id="administrator-title"  placeholder="Recreation Supervisor">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Add Organization</button>
                </form>
            </div>
        </div>
    </div>
</div>
