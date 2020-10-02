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
            {{-- @include('tenant.admin.assignments.organizations.components.assignment_list', [
                'assignments' => $isOutgoingFromTenant ? $organization->incomingAssignments : $organization->outgoingAssignments,
                'completeRouteString' => 'tenant:admin.assignments.complete',
                'approveRouteString' => 'tenant:admin.assignments.approve'
            ]) --}}
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">{{ $organization->name }} Instructor Tasks</div>
        <div class="card-body">
            @if(!$isOutgoingFromTenant)
                <form method="POST" action="{{ tenant()->route('tenant:admin.instructors.store') }}">
                    @csrf
                    <input type="hidden" name="assign_to_organization_ids[]" value="{{ $organization->id }}">
                    <div class="form-group">
                        <label for="first-name">First Name</label>
                        <input type="text" class="form-control" name="first_name" required placeholder="Paul">
                    </div>
                    <div class="form-group">
                        <label for="last-name">Last Name</label>
                        <input type="text" class="form-control" name="last_name" required placeholder="Smith">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email"required placeholder="p.smith@fakeville.gov">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" name="phone" placeholder="415-555-5555">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Add Instructor</button>
                </form>
            @endif
            @forelse($instructors as $instructor)
                <h5 class="card-title text-muted mt-4">{{ $instructor->name }}'s Assignments</h5>
                @include('tenant.admin.assignments.organizations.components.assignment_list', [
                    'assignments' => $instructor->incomingAssignments,
                    'completeRouteString' => 'tenant:admin.instructor_assignments.complete',
                    'approveRouteString' => 'tenant:admin.instructor_assignments.approve'
                ])
            @empty
                No instructors assigned yet.
            @endforelse
        </div>
    </div>
</div>
@endsection
