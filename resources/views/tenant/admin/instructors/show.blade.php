@extends('layouts.app')
@section('title', 'Instructors')
@section('content')
<div class="container">
    @include('shared.form_errors')

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
                <th>&nbsp;</th>
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
                        {{ $meeting->program->name }}
                    </td>
                    <td>{{ $meeting->site->name}} {!! $meeting->getLinkedPinHtml() !!}</td>
                    <td>
                        {!! $meeting->instructor_list !!}
                    </td>
                    <td><a href="{{ tenant()->route('tenant:admin.programs.show', [$meeting->program]) }}"><i class="fas fa-fw fa-eye"></i></a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
