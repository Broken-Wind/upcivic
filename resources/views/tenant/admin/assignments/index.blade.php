@extends('layouts.app')
@section('title', 'Assignments')
@push('css')
<style>
    .instructor-bubble {
        /* content:attr(data-letters); */
        display:inline-block;
        font-size:1em;
        width:2.5em;
        height:2.5em;
        line-height:2.5em;
        text-align:center;
        border-radius:50%;
        vertical-align:middle;
        margin-right:.5em;
    }
</style>
@endpush
@section('content')
@include('tenant.admin.assignments.components.assignment_details_modal')
<div class="container">
    @include('shared.form_errors')
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ !$isOutgoingAssignments ? 'active' : '' }}" href="{{ tenant()->route('tenant:admin.assignments.incoming.index') }}">Tasks Assigned to {{ tenant()->name }} </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $isOutgoingAssignments ? 'active' : '' }}" href="{{ tenant()->route('tenant:admin.assignments.outgoing.index') }}">Tasks Assigned to Other Organizations</a>
        </li>
    </ul>

    <div class="card">
        <div class="card-header">Overview</div>
        <div class="card-body">

            @if($organizations->count() > 0)

                <table class="table table-striped">

                    <thead>
                        <th>Organization</th>
                        <th>Tasks Complete</th>
                        <th>Instructor Status</th>
                        <th>&nbsp;</th>
                    </thead>

                    @foreach($organizations as $organization => $assignments)
                        @include('tenant.admin.assignments.components.organization_row')
                    @endforeach

                </table>

            @else

                No assignments yet.

            @endif

        </div>
    </div>
</div>
@endsection
