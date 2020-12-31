@extends('layouts.app')
@section('title', $assignment->name . ' - Assigned to ' . $assignment->assignee->name)

@push('scripts')
<script>
    var assignment = {
        'id': {{ $assignment->id }},
        'name': '{{ $assignment->name }}',
        'assigned_by_organization_id': {{ $assignment->assigned_by_organization_id }},
        'assigned_to_organization_id': {{ $assignment->assigned_to_organization_id }},
        'approved_at': '{{ $assignment->getApprovedAtAttribute() }}',
        'completed_at': '{{ $assignment->getCompletedAtAttribute() }}',
    };
</script>
<script src="{{ asset('js/views/edit_assignment.js') }}" defer></script>
@endpush

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
    @include('tenant.admin.assignments.components.controls')
    @if($assignment->isSignableDocument())
        @include('tenant.assignments.signable_documents.components.document')
    @endif
</div>
@endsection
