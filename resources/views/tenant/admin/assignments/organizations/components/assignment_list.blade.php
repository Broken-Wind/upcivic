<container>
    @forelse($assignments as $assignment)
        <div class="col-12">
            <div class="row">
                <div class="my-auto mr-1">From {{ $assignment->assignedByOrganization->name }}: </div>
                @forelse($assignment->assigner_files as $file)
                    <a href="{{ $file->download_link }}" class="ml-1 my-auto">
                        {{ $file->filename }} <i class="fas fa-download"></i>
                    </a>
                    @if($file->canDelete(\Auth::user()))
                        <form method="POST" action="{{ tenant()->route('tenant:admin.files.destroy', [$file]) }}" enctype="multipart/form-data" class="my-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger" onClick="return confirm('Are you sure?')">
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
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="files[]" id="files" aria-describedby="helpfiles" multiple required>
                            <label class="custom-file-label" for="files">Choose file</label>
                        </div>
                        <div class="input-group-append">
                            <button type="submit" class="input-group-text" id="helpfiles">Upload</button>
                        </div>
                    </div>
                </form>
                @endif
            </div>
        </div>

        <div class="col-12">
            <div class="row">
                <div class="my-auto mr-1"> From {{ $assignment->assignedToOrganization->name }}: </div>
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

                @if($assignment->isAssignedToOrganization(tenant()->organization))
                    <form method="POST" action="{{ $assignment->upload_url }}" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="files[]" id="files" aria-describedby="helpfiles" multiple required>
                                <label class="custom-file-label" for="files">Choose file</label>
                            </div>
                            <div class="input-group-append">
                                <button type="submit" class="input-group-text" id="helpfiles">Upload</button>
                            </div>
                        </div>

                    </form>
                @endif
            </div>
        </div>
                        
        <div class="col-12 my-2">
            <div class="row">
                <label class="my-auto mr-2"> Status: </label>

                <div class="{{ $assignment->class_string }} p-1 font-weight-bold text-center mr-2 px-3">
                    {{ $assignment->status_string }}
                </div>

                @if($assignment->canComplete(tenant()->organization))
                    @if(!$assignment->isApproved())
                        <form method="POST" action="{{ tenant()->route($completeRouteString, [$assignment]) }}" class="my-auto">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm" onClick="return confirm('Are you sure?')">Complete</button>
                        </form>
                    @endif
                @endif

                @if($assignment->canApprove(tenant()->organization))
                    @if(!$assignment->isApproved())
                        <form method="POST" action="{{ tenant()->route($approveRouteString, [$assignment]) }}" class="my-auto">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm" onClick="return confirm('Are you sure?')">Approve</button>
                        </form>
                    @endif
                @endif

            </div>
        </div>

        <hr/>
    @empty
        No tasks assigned yet.
    @endforelse
</container>