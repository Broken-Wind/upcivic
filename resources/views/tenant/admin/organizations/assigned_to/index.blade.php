@extends('layouts.app')
@section('title', ' Assignments')
@section('content')
<div class="container">
    @include('shared.form_errors')
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="#">Tasks assigned to {{ $organization->name }}</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ tenant()->route('tenant:admin.organizations.assigned_by.index', [$organization->id]) }}">Tasks assigned to {{ tenant()->name }} </a>
        </li>
    </ul>
    <div class="card my-3">
        <div class="card-header"><strong>General</strong></div>
        <div class="card-body">
            @forelse($organization->incomingAssignments as $assignment)
                @include('tenant.admin.organizations.components.assignment_list_row', ['assignment' => $assignment])
            @empty
                No tasks assigned yet.
            @endforelse
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header"><strong>Instructors</strong></div>
        <div class="card-body">
            @forelse($organization->incomingAssignmentsForInstructors as $assignment)
                @include('tenant.admin.organizations.components.assignment_list_row', ['assignment' => $assignment])
            @empty
                No tasks assigned yet.
            @endforelse
        </div>
    </div>
</div>
@endsection
