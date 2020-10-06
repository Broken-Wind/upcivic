@extends('layouts.app')
@section('title', ' Assignments')
@section('content')
<div class="container">
    <div class="card my-3">
        <div class="card-header">{{ $assignment->name }} Tasks</div>
            <div class="card-body">
            <p>{{ $assignment->description }}</p>
                <div class="col">
                    @include('tenant.admin.assignments.components.file_list', [
                        'files' => $assignment->assignee_files, 'organizationName' => $assignment->assignedToOrganization->name
                    ])

                    @include('tenant.admin.assignments.components.file_list', [
                        'files' => $assignment->assigner_files, 'organizationName' => $assignment->assignedToOrganization->name
                    ])

                </div>

                <form method="POST" action="{{ $assignment->upload_url }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-3">
                            <input type="file" class="form-control-file" name="files[]" id="files" placeholder="Background Check Authorization.pdf" aria-describedby="helpfiles" multiple required>
                        </div>
                        <div class="col-1">
                            <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                        </div>
                    </div>
                </form>

                @if($assignment->canComplete(tenant()->organization))
                    <form method="POST" action="{{ tenant()->route('tenant:admin.assignments.complete', [$assignment]) }}" class="my-auto pt-3">
                        @csrf
                        <button type="submit" class="btn btn-primary" onClick="return confirm('Are you sure?')">Complete</button>
                    </form>
                @endif

                @if($assignment->canApprove(tenant()->organization))
                    @if(!$assignment->isApproved())
                        <form method="POST" action="{{ tenant()->route('tenant:admin.assignments.approve', [$assignment]) }}" class="my-auto pt-3">
                            @csrf
                            <button type="submit" class="btn btn-primary" onClick="return confirm('Are you sure?')">Approve</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection