
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
                    {{ $assignment->name }} <i class="fas fa-info-circle" title="{{ $assignment->description }}"></i>
                    <small>
                        <div class="row">
                            <div class="ml-3 my-auto">From {{ $assignment->assignedByOrganization->name }}: </div>
                            @forelse($assignment->assigner_files as $file)
                                <a href="{{ $file->download_link }}" class="my-auto ml-1">
                                    {{ $file->filename }} <i class="fas fa-download"></i>
                                </a>
                                @if($file->canDelete(\Auth::user()))
                                    <form method="POST" action="{{ tenant()->route('tenant:admin.files.destroy', [$file]) }}" enctype="multipart/form-data" class="my-auto">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @endif
                                @if(!$loop->last), @endif
                            @empty
                            @endforelse
                        </div>
                    </small>
                    @if($assignment->isAssignedByOrganization(tenant()->organization))
                        <form method="POST" action="{{ $assignment->upload_url }}" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="files[]" id="files" multiple required>
                                    <label class="custom-file-label" for="files" aria-describedby="helpfiles">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text" id="helpfiles">Upload</button>
                                </div>
                            </div>
                        </form>
                    @endif
                    <hr>
                    <small>
                        <div class="row">
                            <div class="ml-3 my-auto"> From {{ $assignment->assignedToOrganization->name }}: </div>
                            @forelse($assignment->assignee_files as $file)
                                <a href="{{ $file->download_link }}" class="ml-1 my-auto">
                                    {{ $file->filename }} <i class="fas fa-download"></i>
                                </a>
                                @if($file->canDelete(\Auth::user()))
                                    <form method="POST" action="{{ tenant()->route('tenant:admin.files.destroy', [$file]) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger">
                                            <i class="far fa-trash-alt"></i> 
                                        </button>
                                    </form>
                                @endif
                            @if(!$loop->last), @endif
                            @empty
                            @endforelse
                        </div> 
                    </small>
                    @if($assignment->isAssignedToOrganization(tenant()->organization))
                        <form method="POST" action="{{ $assignment->upload_url }}" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="files[]" id="files" multiple required>
                                    <label class="custom-file-label" for="files" aria-describedby="helpfiles">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text" id="helpfiles">Upload</button>
                                </div>
                            </div>

                        </form>
                    @endif
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

