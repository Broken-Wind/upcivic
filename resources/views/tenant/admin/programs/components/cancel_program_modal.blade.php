<div class="modal fade" id="cancel-program-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5>Cancel #{{ "{$program->id} - {$program->name} at {$program->site->name}" }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            <div class="modal-body" id="modal-body">
                <div class="alert alert-danger text-center">
                    <h4><i class="fas fa-fw fa-exclamation-triangle "></i> WARNING: <br>
                    Refunds are not automatically issued.</h4>
                    You must manually issue refunds via your <a href="https://dashboard.stripe.com/search?query=program_id%3A{{ $program->id }}" target="_blank">Stripe dashboard.</a>
                </div>

                <form method="POST" action="{{tenant()->route('tenant:admin.programs.destroy', [$program])}}" id="delete-program">
                    @csrf
                    @method('DELETE')
                    <div class="form-group">
                      <label for="cancellation_message">Cancellation Message</label>
                      <textarea class="form-control" name="cancellation_message" id="cancellation_message" rows="3"></textarea>
                      <small>This will be included in cancellation emails sent to participants and contributing organizations.</small>
                    </div>
                    <button type="submit" class="btn btn-danger ml-1" onClick="return confirm('Are you sure you want to cancel this program? This cannot be undone.')">Confirm Cancellation</button>
                </form>
            </div>

        </div>
    </div>
</div>
