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
    @if($isOutgoingFromTenant)
        <a class="btn btn-primary mb-4" href="{{ tenant()->route('tenant:admin.assignments.create') }}">Assign a Task</a>
    @endif
    <div class="card">
        <div class="card-header">
            @if($isOutgoingFromTenant)
                Outgoing Assignments
            @else
                Incoming Assignments
            @endif
        </div>
        <div class="card-body">

            @if($organizations->count() > 0)

                <table class="table table-striped">

                    <thead>
                    @if($isOutgoingFromTenant)
                        <th>Assigned To</th>
                    @else
                        <th>Assigned By</th>
                    @endif
                        <th>Organization Tasks</th>
                        <th>Instructor Tasks</th>
                        <th>&nbsp;</th>
                    </thead>

                    @foreach($organizations as $organization)
                        @if($isOutgoingFromTenant)
                            @include('tenant.admin.assignments.components.organization_row', [
                                'assignments' => tenant()->organization->unapprovedAssignmentsTo($organization),
                                'instructors' => $organization->instructorsAssignedTo(tenant()->organization),
                                'assignerOrganization' => tenant()->organization
                            ])
                        @else
                            @include('tenant.admin.assignments.components.organization_row', [
                                'assignments' => tenant()->organization->unapprovedAssignmentsBy($organization),
                                'instructors' => $organization->instructorsAssignedBy(tenant()->organization),
                                'assignerOrganization' => $organization
                            ])
                        @endif
                    @endforeach

                </table>

            @else
            <div class="alert alert-info">
                @if($isOutgoingFromTenant)
                    You haven't assigned any tasks yet. Assign tasks to a partner <a href="{{ tenant()->route('tenant:admin.tasks.index') }}">here.</a>
                @else
                    No organizations have assigned you any tasks yet. If an organization you partner with assigns you a task, it will appear here.
                @endif
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
