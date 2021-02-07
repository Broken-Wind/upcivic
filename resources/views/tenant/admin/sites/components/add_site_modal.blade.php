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
                    @if($areas->isNotEmpty())
                        <div class="form-group">
                            <label for="county_id">Area</label>
                            <select class="form-control" name="area_id">
                                <option value="">Choose an area...</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area['id'] }}">{{ $area['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <button type="submit" class="btn btn-primary mb-2">Add Site</button>
                </form>
            </div>
        </div>
    </div>
</div>
