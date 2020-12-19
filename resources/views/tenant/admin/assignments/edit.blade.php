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
    @if($assignment->isSignableDocument())
        @include('tenant.admin.assignments.components.controls')
        @include('tenant.assignments.signable_documents.components.document')
    @else
        @include('tenant.assignments.generic_assignments.components.controls')
    @endif
</div>
@endsection
