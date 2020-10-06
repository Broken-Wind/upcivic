@extends('layouts.app')
@section('title', ' Assignments')
@section('content')
<div class="container">
    @if($isOutgoingFromTenant)
        <a href="{{ tenant()->route('tenant:admin.assignments.outgoing.organizations.index', [$assignment->assigned_to_organization_id]) }}">
            <i class="fas fa-angle-left"></i> Back to assignments for {{ $assignment->assignedToOrganization->name }}
        </a>
    @else
        <a href="{{ tenant()->route('tenant:admin.assignments.incoming.organizations.index', [$assignment->assigned_by_organization_id]) }}">
            <i class="fas fa-angle-left"></i> Back to assignments from {{ $assignment->assignedByOrganization->name }} 
        </a>
    @endif
    <div class="card my-3">
        <div class="card-header">{{ $assignment->name }} Tasks 
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

                @if($assignment->canComplete(tenant()->organization))
                    <form method="POST" action="{{ tenant()->route($routeActionString . 'complete', [$assignment]) }}" class="my-auto pt-3">
                        @csrf
                        <button type="submit" class="btn btn-primary" onClick="return confirm('Are you sure?')">Complete</button>
                    </form>
                @endif

                @if($assignment->canApprove(tenant()->organization))
                    @if(!$assignment->isApproved())
                        <form method="POST" action="{{ tenant()->route($routeActionString . 'approve', [$assignment]) }}" class="my-auto pt-3">
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