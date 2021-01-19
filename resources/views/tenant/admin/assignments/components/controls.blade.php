<div class="card my-3">
    <div class="card-header">
        <strong>
            {{ $assignment->name }}
        </strong>
        <span class="text-muted">
            - Assigned to {{ $assignment->assignee->name }}
        </span>
        <span class="{{ $assignment->class_string }} p-1 font-weight-bold text-center px-3 ml-3">
            {{ $assignment->status_string }}
        </span>
    </div>

    <div class="card-body">
        <p>{{ $assignment->description }}</p>
        <div class="col">
            @include('tenant.admin.assignments.components.file_list', [
                'files' => $assignment->assignee_files, 'organizationName' => $assignment->assignedToOrganization->name
            ])

            @include('tenant.admin.assignments.components.file_list', [
                'files' => $assignment->assigner_files, 'organizationName' => $assignment->assignedByOrganization->name
            ])
        </div>

        @if($assignment->canUpload(tenant()->organization))
            <hr/>
            <form method="POST" action="{{ $assignment->upload_url }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-3">
                        <input type="file" style="overflow:hidden" class="form-control-file mt-1" name="files[]" id="files" placeholder="Background Check Authorization.pdf" aria-describedby="helpfiles" multiple required>
                    </div>
                    <div class="col-1">
                        <button type="submit" class="btn btn-secondary">Upload</button>
                    </div>
                </div>
            </form>
            <hr/>
        @endif


        {{-- If we do not separate the forms from the buttons here, each button will appear on a new line.
        I think having them on one line looks better and is more consistent with our style throughout the app. --}}
        @if($assignment->canComplete(tenant()->organization) && !$assignment->isPending())
            <form method="POST" action="{{ tenant()->route($routeActionString . 'complete', [$assignment]) }}" class="my-auto pt-1" id="complete">
                @csrf
            </form>
        @endif
        @if($assignment->canApprove(tenant()->organization) && !$assignment->isApproved())
            <form method="POST" action="{{ tenant()->route($routeActionString . 'approve', [$assignment]) }}" class="my-auto pt-1" id="approve">
                @csrf
            </form>
        @endif
        @if($assignment->canDelete(tenant()->organization))
            <form method="POST" action="{{ tenant()->route($routeActionString . 'destroy', [$assignment]) }}" class="my-auto pt-1" id="delete">
                @method('DELETE')
                @csrf
            </form>
        @endif

        @if($assignment->canComplete(tenant()->organization) && !$assignment->isPending())
            <button id="complete-assignment-button" type="submit" class="btn btn-primary mr-1" onClick="return confirm('Are you sure you want to mark this as complete?')" form="complete">Mark as Complete</button>
        @endif
        @if($assignment->canApprove(tenant()->organization) && !$assignment->isApproved())
            <button id="approve-assignment-button" type="submit" class="btn btn-primary mr-1" onClick="return confirm('Are you sure you want to mark this as approved?')" form="approve">Mark as Approved</button>
        @endif
        @if($assignment->canDelete(tenant()->organization))
            <button id="delete-assignment-button" type="submit" class="btn btn-danger" onClick="return confirm('Are you sure you want to permanently delete this assignment?')" form="delete">Delete</button>
        @endif

    </div>
</div>
