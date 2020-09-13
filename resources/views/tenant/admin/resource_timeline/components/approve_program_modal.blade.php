<div class="modal fade" id="approve-program-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5><a href="" class="program-title" id="approve-modal-title"></a></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            <form action="{{ tenant()->route('tenant:admin.programs.approve') }}" method="post">
                @csrf
                <input type="hidden" name="approve_program_id" id="approve-program-id" value="" />
                <div class="modal-body" id="modal-body">
                    <div class="form-group">
                        <label for="approval-next-steps">Next steps content</label>
                        <textarea rows="3" class="form-control" name="proposal_next_steps" id="proposal-next-steps" form="approve-program-form">{{ tenant()->proposal_next_steps }}</textarea>
                        <small class="text-muted">Emailed to all program contributors.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="submit-approve-program" form="approve-program-form" class="btn btn-primary">Confirm Approval</button>
                </div>
            </form>
        </div>
    </div>
</div>
