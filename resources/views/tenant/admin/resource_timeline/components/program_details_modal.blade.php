<div class="modal fade" id="program-details-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="program-title" id="details-modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body" id="modal-body">
                <input type="hidden" name="details_program_id" id="details-program-id" value="" />
                <div id="description-of-meetings"></div>
                <div id="site-location"></div>
                <div id="program-times"></div>
                <div id="ages-string"></div>
                <div id="contributors-container"></div>
                <div id="meetings-container"></div>
                <div id="description"></div>
                <div id="proposed-at"></div>

            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-danger" id="reject-program" data-dismiss="modal">Reject</button>

            <form action="{{ tenant()->route('tenant:admin.programs.approve') }}" method="post">
                @csrf
                <input type="hidden" name="approve_program_id" id="approve-program-id" value="" />
                <button type="submit" class="btn btn-primary" id="accept-program">Approve</button>
            </form>

            </div>
        </div>
    </div>
</div>
