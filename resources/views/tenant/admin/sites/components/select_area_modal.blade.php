<div class="modal fade" id="select-area-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 id="select-area-modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            <div class="modal-body" id="modal-body">
                <form method="POST" id="update-site-area-form" action="">
                    @csrf
                    @forelse($areas as $area)
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input area-radio" name="area_id" value="{{ $area->id }}">
                                {{ $area->name }}
                            </label>
                        </div>
                    @empty
                        No areas found.
                    @endforelse

                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input area-radio" name="area_id" value="">
                            Other/Unspecified Area
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">Update Area</button>
                </form>
            </div>
        </div>
    </div>
</div>
