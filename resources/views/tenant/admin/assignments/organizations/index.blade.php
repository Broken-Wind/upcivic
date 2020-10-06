@extends('layouts.app')
@section('title', ' Assignments')
@section('content')
<div class="container">
    @if($isOutgoingFromTenant)
        <a href="{{ tenant()->route('tenant:admin.assignments.outgoing.index') }}">
            <i class="fas fa-angle-left"></i> Back to Outgoing Assignments
        </a>
    @else
        <a href="{{ tenant()->route('tenant:admin.assignments.incoming.index') }}">
            <i class="fas fa-angle-left"></i> Back to Incoming Assignments
        </a>
    @endif
    @include('shared.form_errors')
    <div class="card my-3">
        <div class="card-header">{{ $organization->name }} Tasks</div>
        <div class="card-body">
            @include('tenant.admin.assignments.organizations.components.assignment_list', [
                'assignments' => $isOutgoingFromTenant ? $organization->incomingAssignments : $organization->outgoingAssignments,
                'editRouteString' => 'tenant:admin.assignments.edit',
            ])
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">{{ $organization->name }} Instructor Tasks</div>
        <div class="card-body">
            @forelse($instructors as $instructor)
                <h5 class="card-title text-muted mt-4">{{ $instructor->name }}'s Assignments</h5>
                @include('tenant.admin.assignments.organizations.components.assignment_list', [
                    'assignments' => $instructor->incomingAssignments,
                    'editRouteString' => 'tenant:admin.instructor_assignments.edit',
                ])
            @empty
                No instructors assigned yet.
            @endforelse
        </div>
    </div>
</div>
@endsection
