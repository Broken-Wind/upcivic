@push('css')
    <style>
        @font-face {
            font-family: Otto;
            src: url({{ asset('fonts/Otto.ttf') }}) format("truetype");
            font-weight: 400; // use the matching font-weight here ( 100, 200, 300, 400, etc).
            font-style: normal; // use the matching font-style here
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
        .signature {
            font-family: Otto, Times, serif;
            font-size: 36px;
        }
        .page-break {
            page-break-after: always;
        }
        table {
            width: 100%;
        }
        th {
            text-align: left;
            padding: 5px;
        }
        td {
            padding: 5px;
        }
    </style>
@endpush

@include('shared.form_errors')

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
            <form method="POST" action="{{ $assignment->upload_url }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-3">
                        <input type="file" class="form-control-file" name="files[]" id="files" placeholder="Background Check Authorization.pdf" aria-describedby="helpfiles" multiple required>
                    </div>
                    <div class="col-1">
                        <button type="submit" class="btn btn-secondary btn-sm">Upload</button>
                    </div>
                </div>
            </form>
        @endif

        @if(!$assignment->isPending() && !$assignment->isApproved())
            <form method="POST" action="{{ tenant()->route('tenant:assignments.tasks.complete', [$assignment]) }}" class="my-auto pt-3">
                @csrf
                <button type="submit" class="btn btn-primary" onClick="return confirm('Are you sure?')">Complete</button>
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