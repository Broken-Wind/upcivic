<div class="modal fade" id="add-site-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="site-title" id="add-site-modal-title">Site</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            <div class="modal-body" id="modal-body">
                <form method="POST" action="{{ tenant()->route('tenant:admin.sites.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="site-name">Name</label>
                        <input type="text" class="form-control" name="name" id="site-name" required  placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="site-address">Address</label>
                        <input type="text" class="form-control" name="address" id="site-address" required placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="site-phone">Phone</label>
                        <input type="text" class="form-control" name="phone" id="site-phone" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="county_id">County</label>
                        <select class="form-control" name="county_id">
                            <option value="">Choose a county...</option>
                            @forelse ($counties as $county)
                                <option value="{{ $county['id'] }}">{{ $county['name'] }}</option>
                            @empty
                                <option disabled>No counties exist.</option>
                            @endforelse
                        </select>
                        <!-- <input type="text" class="form-control" name="county_id" id="site-county" required placeholder=""> -->
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Add Site</button>
                </form>
            </div>
        </div>
    </div>
</div>
