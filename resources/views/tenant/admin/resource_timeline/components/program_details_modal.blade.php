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
                <input type="hidden" name="details_program_id" value="" />
                <div id="description-of-meetings"></div>
                <div id="program-times"></div>
                <div id="site-location"></div>
                <div id="ages-string"></div>
                <div id="meetings-container"></div>
                <div id="proposed-at" class="mt-3"></div>

                <hr/>
                <table class="table">
                    <thead>
                        <tr>
                            <div id="program-overall-status">
                            </div>
                        </tr>
                    </thead>
                    <tbody id="program-contributors-rows">
                    </tbody>
                </table>
                <form method="POST" action="{{ tenant()->route('tenant:admin.programs.approve') }}" id="approve-program-form" name="approve-program-form">
                    @csrf
                    <input type="hidden" name="approve_program_id" id="approve-program-id" value="" />
                    <div class="form-group">
                      <select class="form-control form-control-sm" name="contributor_id" id="program-contributor-actions"></select>
                    </div>
                </form>
            </div>

            <div class="modal-footer" style="">
                <button type="button" class="btn btn-danger" id="reject-program" data-dismiss="modal">Reject</button>
                <button type="submit" class="btn btn-primary" id="approve-program" form="approve-program-form">Update</button>
            </div>
        </div>
    </div>
</div>
