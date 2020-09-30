@extends('layouts.app')
@section('title', ' Assignments')
@section('content')
<div class="container">
    @if($isOutgoingFromTenant)
        <a href="{{ tenant()->route('tenant:admin.assignments.outgoing.index') }}">
            <i class="fas fa-angle-double-left"></i> Back to Outgoing Assignments
        </a>
    @else
        <a href="{{ tenant()->route('tenant:admin.assignments.incoming.index') }}">
            <i class="fas fa-angle-double-left"></i> Back to Incoming Assignments
        </a>
    @endif
    @include('shared.form_errors')
    <div class="card my-3">
        <div class="card-header">{{ $organization->name }} Tasks</div>
        <div class="card-body">
            @include('tenant.admin.assignments.organizations.components.assignment_list', ['assignments' => $isOutgoingFromTenant ? $organization->incomingAssignments : $organization->outgoingAssignments])
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">{{ $organization->name }} Instructor Tasks</div>
        <div class="card-body">
            @include('tenant.admin.assignments.organizations.components.assignment_list', ['assignments' => $isOutgoingFromTenant ? $organization->incomingAssignments : $organization->outgoingAssignments])
            {{-- <h5 class="card-title text-muted">Ilene Reese</h5>
            @forelse($organization->incomingAssignments as $assignment)
                @include('tenant.admin.organizations.components.assignment_list_row', ['assignment' => $assignment])
            @empty
                No tasks assigned yet.
            @endforelse
            <h5 class="card-title pb-1 text-muted">Rudy Morin</h5>
            @forelse($organization->incomingAssignments as $assignment)
                @include('tenant.admin.organizations.components.assignment_list_row', ['assignment' => $assignment])
            @empty
                No tasks assigned yet.
            @endforelse --}}
        </div>
    </div>
</div>
@endsection
