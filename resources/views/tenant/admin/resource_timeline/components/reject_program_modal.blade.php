<div class="modal fade" id="reject-program-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5><a href="" class="program-title" id="reject-modal-title"></a></h5>
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
                        <small class="text-muted">Emailed to all program contributors.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="submit-reject-program" class="btn btn-danger">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>
