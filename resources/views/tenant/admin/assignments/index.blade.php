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
    <div class="card">
        <div class="card-header">Task Assignments</div>
        <div class="card-body">

            @if($organizations->count() > 0)

                <table class="table table-striped">

                    <thead>
                        <th>Organization</th>
                        <th>Tasks Complete</th>
                        <th>Instructor Status</th>
                        <th>&nbsp;</th>
                    </thead>

                    @foreach($organizations as $organization)

                        <tr>

                            <td>{{ $organization->name }}</td>

                            <td>
                                <div class="alert-danger text-center organization-status" data-organization-id="1">
                                    1 of 3
                                </div>
                            </td>

                            <td class="">
                                <span class="instructor-bubble alert-danger" title="Calin Furau">CF</span>
                                <span class="instructor-bubble alert-warning" title="Greg Intermaggio">GI</span>
                                <span class="instructor-bubble alert-success" title="Netta Ravid">NR</span>
                            </td>

                            <td class="text-right">
                                <a href="">
                                    <i class="far fa-edit mr-2"></i>
                                </a>
                            </td>

                        </tr>

                    @endforeach

                </table>

            @else

                No assignments yet.

            @endif

        </div>
    </div>
</div>
@endsection
