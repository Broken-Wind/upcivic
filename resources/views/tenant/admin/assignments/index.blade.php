@extends('layouts.app')
@section('title', ($isOutgoingFromTenant ? 'Outgoing' : 'Incoming') . ' Assignments')
@push('css')
<style>
    .instructor-bubble {
        /* content:attr(data-letters); */
        display:inline-block;
        font-size:1em;
        width:2.2em;
        height:2.2em;
        line-height:2.2em;
        text-align:center;
        border-radius:50%;
        vertical-align:middle;
        margin-right:.1em;
    }
    .organization-rectangle {
        /* content:attr(data-letters); */
        display:inline-block;
        line-height:1.6em;
        text-align:center;
        vertical-align:middle;
        margin-right:.1em;
        padding-left:.2em;
        padding-right:.2em;
    }
</style>
@endpush
@section('content')
@include('tenant.admin.assignments.components.assignment_details_modal')
<div class="container">
    @include('shared.form_errors')
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ !$isOutgoingFromTenant ? 'active' : '' }}" href="{{ tenant()->route('tenant:admin.assignments.incoming.index') }}">Incoming Assignments</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $isOutgoingFromTenant ? 'active' : '' }}" href="{{ tenant()->route('tenant:admin.assignments.outgoing.index') }}">Outgoing Assignments</a>
        </li>
    </ul>

    <div class="card">
        <div class="card-body">

            @if($organizations->count() > 0)

                <table class="table table-striped">

                    <thead>
                    @if($isOutgoingFromTenant)
                        <th>Assigned To</th>
                    @else
                        <th>Assigned By</th>
                    @endif
                        <th>Organization Status</th>
                        <th>Instructor Statuses</th>
                        <th>&nbsp;</th>
                    </thead>

                    @foreach($organizations as $organization)
                        @if($isOutgoingFromTenant)
                            @include('tenant.admin.assignments.components.organization_row', [
                                'assignments' => tenant()->organization->assignmentsTo($organization),
                                'instructors' => $organization->instructorsAssignedTo(tenant()->organization),
                                'assignerOrganization' => tenant()->organization
                            ])
                        @else
                            @include('tenant.admin.assignments.components.organization_row', [
                                'assignments' => tenant()->organization->assignmentsBy($organization),
                                'instructors' => $organization->instructorsAssignedBy(tenant()->organization),
                                'assignerOrganization' => $organization
                            ])
                        @endif
                    @endforeach

                </table>

            @else

                No assignments yet.

            @endif

        </div>
    </div>
</div>
@endsection
