<div class="modal fade" id="add-organization-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="program-title" id="reject-modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            <form action="{{ tenant()->route('tenant:admin.programs.reject') }}" method="post">
                @csrf
                <input type="hidden" name="reject_program_id" id="reject-program-id" value="" />
                <div class="modal-body" id="modal-body">
                    <div class="form-group">
                    <label for="rejection-reason">Rejection Reason</label>
                    <textarea class="form-control" name="rejection_reason" id="rejection-reason" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>
