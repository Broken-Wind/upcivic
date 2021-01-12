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
            @include('tenant.admin.assignments.components.public_file_list', [
                'files' => $assignment->assignee_files, 'organizationName' => $assignment->assignedToOrganization->name
            ])

            @include('tenant.admin.assignments.components.public_file_list', [
                'files' => $assignment->assigner_files, 'organizationName' => $assignment->assignedByOrganization->name
            ])
        </div>

        @if($assignment->canUpload(tenant()->organization))
            <hr/>
            <form method="POST" action="{{ tenant()->route('tenant:assignments.public.upload', [$assignment]) }}" enctype="multipart/form-data">
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

        @if(!$assignment->isPending() && !$assignment->isApproved())
            <form method="POST" action="{{ tenant()->route('tenant:assignments.public.complete', [$assignment]) }}" class="my-auto pt-3">
                @csrf
                <button type="submit" class="btn btn-primary" onClick="return confirm('Are you sure?')">Mark as Complete</button>
            </form>
        @endif

        @if(!Auth::check())
            <form action="{{ route('login') }}" class="my-auto pt-5">
                <p>If your organization assigned this task, please log in to manage it. </p>
                <input type="submit" class="btn btn-primary" value="Log In" />
            </form>
        @endif

    </div>
</div>