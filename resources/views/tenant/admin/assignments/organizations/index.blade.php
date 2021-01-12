@extends('layouts.app')
@section('title', 'Assignments ' . ($isOutgoingFromTenant ? 'to ' : 'from ') . $organization->name)
@section('content')
@include('tenant.admin.assignments.organizations.components.instructor_assignment_modal')
@include('tenant.admin.instructors.components.add_instructor_modal')
<div class="container">
    @if($isOutgoingFromTenant)
        <a href="{{ tenant()->route('tenant:admin.assignments.outgoing.index') }}">
            <i class="fas fa-angle-left"></i> Back to all <strong>outgoing</strong> assignments
        </a>
    @else
        <a href="{{ tenant()->route('tenant:admin.assignments.incoming.index') }}">
            <i class="fas fa-angle-left"></i> Back to all <strong>incoming</strong> assignments
        </a>
    @endif
    @include('shared.form_errors')
    <div class="card my-3">
        <div class="card-header">Tasks Assigned {{ $isOutgoingFromTenant ? 'to' : 'by' }} {{ $organization->name }}</div>
        <div class="card-body">
            @include('tenant.admin.assignments.organizations.components.assignment_list', [
                'assignments' => $isOutgoingFromTenant ? $organization->incomingAssignments : $organization->outgoingAssignments,
                'editRouteString' => 'tenant:admin.assignments.edit',
            ])
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">Instructors Assigned {{ $isOutgoingFromTenant ? 'by' : 'to' }} {{ $organization->name }}</div>
        <div class="card-body">
            @forelse($instructors as $instructor)
                <h5 class="card-title text-muted mt-4">{{ $instructor->name }}'s Assignments</h5>
                @if($isOutgoingFromTenant)
                    @include('tenant.admin.assignments.organizations.components.assignment_list', [
                        'assignments' => $instructor->incomingAssignmentsFrom(tenant()->organization),
                        'editRouteString' => 'tenant:admin.instructor_assignments.edit',
                    ])
                @else
                    @include('tenant.admin.assignments.organizations.components.assignment_list', [
                        'assignments' => $instructor->incomingAssignmentsFrom($organization),
                        'editRouteString' => 'tenant:admin.instructor_assignments.edit',
                    ])
                @endif
            @empty
                @if($isOutgoingFromTenant)
                    @if($organization->hasIncomingAssignmentsForInstructors())
                        <div class="alert alert-danger">{{ $organization->name }} has not assigned any instructors yet.</div>
                    @else
                        <div class="alert alert-warning">You have not assigned any instructor tasks to {{ $organization->name }}, and they have not assigned any instructors yet.</div>
                    @endif
                @else
                    @if($organization->hasOutgoingAssignmentsForInstructors())
                        <div class="alert alert-danger">{{ $organization->name }} has assigned one or more tasks for your instructors to complete. Please assign any instructors that will teach for {{ $organization->name }}.</div>
                    @else
                        <div class="alert alert-warning">No instructor tasks have been assigned by {{ $organization->name }} yet.</div>
                    @endif
                @endif
            @endforelse
            <hr>

            @if(!$isOutgoingFromTenant)
                <a href="#" class="btn btn-secondary" data-toggle="modal" data-target="#instructor-assignment-modal">
                    Assign/Unassign Instructors
                </a>
                <p><small id="add-instructor" class="text-muted">Select the instructor you'd like to assign this assignment to. Can't find the instructor you'd like? <a href="" data-toggle="modal" data-target="#add-instructor-modal">Add an instructor</a></small></p>
            @endif
        </div>
    </div>
</div>
@endsection
