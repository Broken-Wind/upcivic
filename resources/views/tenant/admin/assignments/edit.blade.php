@extends('layouts.app')
@section('title', $assignment->name . ' - Assigned to ' . $assignment->assignee->name)
@section('content')
<div class="container">
    @if($isOutgoingFromTenant)
        <a href="{{ tenant()->route('tenant:admin.assignments.to.organizations.index', [$assignment->assigned_to_organization_id]) }}">
            <i class="fas fa-angle-left"></i> Back to assignments for {{ $assignment->assignedToOrganization->name }}
        </a>
    @else
        <a href="{{ tenant()->route('tenant:admin.assignments.from.organizations.index', [$assignment->assigned_by_organization_id]) }}">
            <i class="fas fa-angle-left"></i> Back to assignments from {{ $assignment->assignedByOrganization->name }}
        </a>
    @endif
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

                @if($assignment->canComplete(tenant()->organization) && !$assignment->isPending())
                    <form method="POST" action="{{ tenant()->route($routeActionString . 'complete', [$assignment]) }}" class="my-auto pt-3">
                        @csrf
                        <button type="submit" class="btn btn-primary" onClick="return confirm('Are you sure?')">Complete</button>
                    </form>
                @endif

                @if($assignment->canApprove(tenant()->organization) && !$assignment->isApproved())
                    <form method="POST" action="{{ tenant()->route($routeActionString . 'approve', [$assignment]) }}" class="my-auto pt-3">
                        @csrf
                        <button type="submit" class="btn btn-primary" onClick="return confirm('Are you sure?')">Approve</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
