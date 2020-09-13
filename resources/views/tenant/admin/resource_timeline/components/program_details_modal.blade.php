<div class="modal fade" id="program-details-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5><a href="" target="_blank" class="program-title" id="details-modal-title"></a></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body" id="modal-body">
                <input type="hidden" name="details_program_id" value="" />
                <div class="container-fluid">
                    <div class="row align-items-center mb-3">
                        <div class="col-sm-2"></div>
                        <div class="col-4 col-sm-2 text-center">
                            <i class="fas fa-fw fa-2x fa-calendar-alt text-secondary"></i>
                        </div>
                        <div class="col-8 col-sm-6">
                            <div id="description-of-meetings"></div>
                            <div id="program-times"></div>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col-sm-2"></div>
                        <div class="col-4 col-sm-2 text-center">
                            <i class="fas fa-fw fa-2x fa-map-marked-alt text-secondary"></i>
                        </div>
                        <div class="col-8 col-sm-6">
                            <div id="site-name"></div>
                            <div id="location-name"></div>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col-sm-2"></div>
                        <div class="col-4 col-sm-2 text-center">
                            <i class="fas fa-fw fa-2x fa-users text-secondary"></i>
                        </div>
                        <div class="col-8 col-sm-6">
                            <div id="ages-string"></div>
                            <div id="enrollments"></div>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                </div>
                <div class="text-right"><small class="text-muted"><em id="proposed-at"></em></small></div>
                <hr/>
                <div id="program-overall-status"> </div>
                <table class="table">
                    <tbody id="program-contributors-rows"> </tbody>
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
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="reject-program">Reject</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="approve-program" form="approve-program-form">Approve</button>
            </div>
        </div>
    </div>
</div>
