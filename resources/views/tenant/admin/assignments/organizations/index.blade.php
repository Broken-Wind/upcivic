@extends('layouts.app')
@section('title', ' Assignments')
@section('content')
@include('tenant.admin.assignments.organizations.components.instructors_assignment_modal')
@include('tenant.admin.instructors.components.add_instructor_modal')
<div class="container">
    @if($isOutgoingFromTenant)
        <a href="{{ tenant()->route('tenant:admin.assignments.outgoing.index') }}">
            <i class="fas fa-angle-left"></i> Back to all Outgoing Assignments
        </a>
    @else
        <a href="{{ tenant()->route('tenant:admin.assignments.incoming.index') }}">
            <i class="fas fa-angle-left"></i> Back to all Incoming Assignments
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
                <span class="text-muted pr-2"> Assign the <strong>instructors</strong> required to complete compliance tasks for {{$organization->name}}</span>
            @endforelse
            <hr>
            
            @if(!$isOutgoingFromTenant)
                <a href="#" class="btn btn-secondary" data-toggle="modal" data-target="#instructors-assignment-modal">
                    Assign Instructors
                </a>
                <p><small id="add-instructor" class="text-muted">Select the instructor you'd like to assign this assignment to. Can't find the instructor you'd like? <a href="" data-toggle="modal" data-target="#add-instructor-modal">Add an instructor</a></small></p>
            @endif
        </div>
    </div>
</div>
@endsection
