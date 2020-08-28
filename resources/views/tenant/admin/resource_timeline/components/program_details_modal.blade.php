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
                <div id="site-location"></div>
                <div id="program-times"></div>
                <div id="ages-string"></div>
                <div id="meetings-container"></div>
                <div id="description"></div>
                <div id="proposed-at"></div>

                <table class="table table-sm mt-4">
                    <thead>
                        <tr>
                            <th>
                                <h3 style="font-size:1.5rem">Overall Status</h3>
                            </th>
                            <td>
                                <div id="program-overall-status">
                                </div>
                            </td>
                        </tr>
                    </thead>
                    <tbody id="program-contributors-rows">
                        <tr>
                            <th>Demo Host</th>
                            <td>
                                <div class="alert-danger font-weight-bold text-center">Pending Approval</div>
                            </td>
                        </tr>
                        <tr>
                            <th>Demo Activity Provider</th>
                            <td>
                                <div class="alert-warning font-weight-bold text-center" title="Marked approved on 8/28/20 by Greg Intermaggio">Marked Approved</div>
                            </td>
                        </tr>
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