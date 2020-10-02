
<table class="table table-striped">
    <thead>
        <tr>
            <td>Task</td>
            <td>Status</td>
            <td>Actions</td>
        </tr>
    </thead>
    <tbody>
        @forelse($assignments as $assignment)
            <tr>
                <td>
                    <div class="row">
                        <div class="col-12">
                            {{ $assignment->name }} <i class="fas fa-info-circle" title="{{ $assignment->description }}"></i>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <small>
                                From {{ $assignment->assignedByOrganization->name }}:
                                @forelse($assignment->assigner_files as $file)
                                    <a href="{{ $file->download_link }}">
                                        {{ $file->filename }}
                                    </a>
                                    @if($file->canDelete(\Auth::user()))
                                        <form method="POST" action="{{ tenant()->route('tenant:admin.files.destroy', [$file]) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if(!$loop->last), @endif
                                @empty
                                @endforelse
                                @if($assignment->isAssignedByOrganization(tenant()->organization))
                                    <form method="POST" action="{{ $assignment->upload_url }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" class="form-control-file" name="files[]" id="files" placeholder="Background Check Authorization.pdf" aria-describedby="helpfiles" multiple required>
                                        <button type="submit" class="btn btn-primary">Upload</button>
                                    </form>
                                @endif
                                <br /><br /><br />
                                From {{ $assignment->assignedToOrganization->name }}:
                                @forelse($assignment->assignee_files as $file)
                                    <a href="{{ $file->download_link }}">
                                        {{ $file->filename }}
                                    </a>
                                    @if($file->canDelete(\Auth::user()))
                                        <form method="POST" action="{{ tenant()->route('tenant:admin.files.destroy', [$file]) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                @if(!$loop->last), @endif
                                @empty
                                @endforelse
                                @if($assignment->isAssignedToOrganization(tenant()->organization))
                                    <form method="POST" action="{{ $assignment->upload_url }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" class="form-control-file" name="files[]" id="files" placeholder="Background Check Authorization.pdf" aria-describedby="helpfiles" multiple required>
                                        <button type="submit" class="btn btn-primary">Upload</button>
                                    </form>
                                @endif
                                <i class="fas fa-plus-circle fa-sm" data-toggle="tooltip" title="Upload required document"></i>
                            </small>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="{{ $assignment->class_string }} p-1 font-weight-bold text-center">
                        {{ $assignment->status_string }}
                    </div>
                </td>
                <td>
                    @if($assignment->canComplete(tenant()->organization))
                    <form method="POST" action="{{ tenant()->route($completeRouteString, [$assignment]) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary" onClick="return confirm('Are you sure?')">Complete</button>
                    </form>
                    @endif
                    @if($assignment->canApprove(tenant()->organization))
                        <form method="POST" action="{{ tenant()->route($approveRouteString, [$assignment]) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary" onClick="return confirm('Are you sure?')">Approve</button>
                        </form>
                    @endif
                    <i class="far fa-bell mr-2"></i>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">
                    No tasks assigned yet.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

