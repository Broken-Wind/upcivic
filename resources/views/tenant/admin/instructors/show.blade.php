@extends('layouts.app')
@section('title', 'Instructors')
@section('content')
<div class="container">
    @include('shared.form_errors')
    <p class="lead">
        {{ $instructor->name }}'s Schedule
    </p>

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ request('show_all') != true ? 'active' : '' }}" href="{{ tenant()->route('tenant:admin.instructors.show', [$instructor]) }}">Upcoming Meetings</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('show_all') == true ? 'active' : '' }}" href="{{ tenant()->route('tenant:admin.instructors.show', [$instructor]) }}?show_all=1">All Meetings</a>
        </li>
    </ul>
    <table class="table text-center">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Program</th>
                <th>Site</th>
                <th>Staff</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($meetings as $meeting)
                <tr>
                    <td>
                        {{ $meeting['start_date'] }}{{ $meeting['start_date'] != $meeting['end_date'] ? '-' . $meeting['end_date'] : '' }}
                        <br />
                        <small ckass="text-muted">Meeting #{{ $meeting->sequence }} of {{ $meeting->total_meetings }}</small>
                    </td>
                    <td>{{ $meeting['start_time'] . "-" . $meeting['end_time'] }}</td>
                    <td>
                        <a href="{{ tenant()->route('tenant:admin.programs.show', ['program' => $meeting->program->id]) }}">
                            #{{ $meeting->program['id'] }} - {{ $meeting->program['internal_name'] }}
                        </a>
                    </td>
                    <td>{{ $meeting->site->name}} {!! $meeting->getLinkedPinHtml() !!}</td>
                    <td>
                        @include('tenant.admin.programs.components.instructor_linked_list', ['instructors' => $meeting->instructors])
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
