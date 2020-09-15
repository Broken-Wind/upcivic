@extends('layouts.app')
@section('title', 'Schedule')
@push('scripts')
    <script>
        var showProgramUrl = "{{ tenant()->route('tenant:admin.programs.show', ['program' => 1]) }}";
        showProgramUrl = showProgramUrl.substr(0, showProgramUrl.lastIndexOf('/')+1);
        var eventColor = "{{\App\Program::STATUSES['unsent']['event_color']}}";
        var events = {!! $meetingEvents['meetings']->toJson() !!};
        var programs = {!! json_encode($meetingEvents['programs']) !!};
        var resources = {!! $resources !!};
        var initialDate = "{{ $initialDate->toDateString() }}";
        var updateLocationsUrl = "{{ route('tenant:api.programs.locations.update', tenant()->slug) }}";
        var fetchMeetingsUrl = "{{ route('tenant:api.resource_timeline_meetings.page', tenant()->slug) }}";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.2.0/main.min.js" integrity="sha256-U+VlpMlWIzzE74RY4mZL4MixQg66XWfjEWW2VUxHgcE=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/views/resource_timeline_meetings.js')}}"></script>
@endpush
@push('css')
    <style type="text/css">
        html, body {
        margin: 0;
        padding: 0;
    }

    .fc-day-grid-event > .fc-content {
        white-space: normal;
    }

    .fc-toolbar-title {
        font-size: 1.125rem !important;
    }
    
    a.fc-timeline-event.fc-h-event.fc-event.fc-event-draggable {
        border-radius: 0.25rem;
        margin: 1px;
    }

<<<<<<< HEAD
    .fc-datagrid-expander.fc-datagrid-expander-placeholder { display: none; }
=======
    .fc-datagrid-cell-main {
        max-width: 220px;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
>>>>>>> Trim and maximize use of space for long location tiles.

    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.2.0/main.css" integrity="sha256-/rB/IDulpFpJSHjrUgRHzB99AnJh3RBNrUOpF+4QIKA=" crossorigin="anonymous">
@endpush
@section('content')
@include('tenant.admin.resource_timeline.components.program_details_modal')
@include('tenant.admin.resource_timeline.components.reject_program_modal')
@include('tenant.admin.resource_timeline.components.approve_program_modal')
<div class="container">
    @include('shared.form_errors')
    <div id='calendar'></div>
</div>
@endsection
