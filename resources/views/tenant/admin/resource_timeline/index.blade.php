@extends('layouts.app')
@section('head.additional')
<style type="text/css">
    html, body {
    margin: 0;
    padding: 0;
    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 14px;
  }

  #calendar {
    max-width: 1100px;
    margin: 40px auto;
  }

  .fc-day-grid-event > .fc-content {
      white-space: normal;
  }
</style>
@endsection
@section('content')
@include('tenant.admin.resource_timeline.components.program_details_modal')
@include('tenant.admin.resource_timeline.components.reject_program_modal')
<div id='calendar'></div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.2.0/main.css" integrity="sha256-/rB/IDulpFpJSHjrUgRHzB99AnJh3RBNrUOpF+4QIKA=" crossorigin="anonymous">
<script type="application/javascript" src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.2.0/main.min.js" integrity="sha256-U+VlpMlWIzzE74RY4mZL4MixQg66XWfjEWW2VUxHgcE=" crossorigin="anonymous"></script>
<script type="application/javascript">
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('reject-program').addEventListener('click', function () {
        $('#reject-program-modal').modal();
    });
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    timeZone: 'UTC',
    editable: false, // don't allow event dragging
    eventResourceEditable: true, // except for between resources
    initialView: 'resourceTimelineDay',
    initialDate: '{{ \Carbon\Carbon::now()->next('monday')->toDateString() }}',
    resourcesInitiallyExpanded: false,
    businessHours: {
        // days of week. an array of zero-based day of week integers (0=Sunday)
        daysOfWeek: [ 1, 2, 3, 4, 5 ], // Monday - Thursday
    },
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'resourceTimelineDay,resourceTimeGridDay'
    },
    resourceAreaHeaderContent: 'Locations',
    views: {
        resourceTimelineDay: {
            type: 'resourceTimeline',
            duration: { weeks: 12 },
            buttonText: 'timeline',
            slotDuration: { days: 1 },
            slotLabelInterval: { days: 1 },
            slotMinWidth: 100,
            slotLabelFormat: [
                { month: 'long', year: 'numeric' }, // top level of text
                { month: 'numeric', day: 'numeric' } // lower level of text
            ],
        }
    },
    eventClick:  function(info) {
        const event = info.event;
        $('#reject-program-id').val(event.id);
        $('.program-title').html(event.title);
        $('#description-of-meetings').html(event.extendedProps.description_of_meetings);
        $('#program-times').html(event.extendedProps.program_times);
        let contributors = [];
        for (const contributor in event.extendedProps.other_contributors) {
            contributors.push(event.extendedProps.other_contributors[contributor].name);
        }
        $('#contributors-container').html('With ' + contributors.join(', '));
        let meetings = [];
        for (const meeting in event.extendedProps.meetings) {
            meetings.push(event.extendedProps.meetings[meeting].start_date);
        }
        $('#meetings-container').html('Meets: ' + meetings.join(', '));
        $('#program-details-modal').modal();
    },
    resourceGroupField: 'site',
    events: {!! $events !!},
    resources: {!! $resources !!},
  });

  calendar.render();
});

</script>
@endsection
