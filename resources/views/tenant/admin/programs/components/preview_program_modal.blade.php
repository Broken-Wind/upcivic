<div class="modal fade" id="preview-program-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="organization-title" id="organization-modal-title">Proposal Email Preview</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            <div class="modal-body" id="modal-body">
                <iframe src="{{ tenant()->route('tenant:admin.programs.proposal_preview', [$program]) }}" style="width: 100%; min-height: 600px; height: 100%"></iframe>
                <form method="POST" action="{{ tenant()->route('tenant:admin.programs.send', [$program]) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary" id="send-program-button">Send</button>
                </form>
            </div>
        </div>
    </div>
</div>
