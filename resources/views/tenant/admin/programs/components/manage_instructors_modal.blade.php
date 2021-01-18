<div class="modal fade" id="manage-instructors-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 id="manage-instructors-title">Manage Instructors</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>

            <div class="modal-body" id="modal-body">
                @php
                    $program = tenant()->organization->programs->first();
                @endphp
                <div id="manage-instructors-program-summary"></div>
                <hr>

                <form id="update-program-instructors-form" action="" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="instructorId">Select an Instructor</label>
                                <select class="form-control" name="instructor_id" id="instructorId">
                                    @foreach ( $instructors as $instructor )
                                        <option value="{{ $instructor['id'] }}">{{ $instructor['first_name'] . " " . $instructor['last_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="action">Select an Action</label>
                                <select class="form-control" name="action" id="action">
                                    <option value="add_selected">Add to selected meetings</option>
                                    <option value="remove_selected">Remove from selected meetings</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <table class="table table-sm table-striped" id="manage-instructors-meetings">
                    </table>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
