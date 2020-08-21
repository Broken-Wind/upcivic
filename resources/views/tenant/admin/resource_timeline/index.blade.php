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
<div class="modal fade" id="fullCalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-title"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modal-body">
            <div id="description-of-meetings"></div>
            <div id="program-times"></div>
            <div id="contributors-container"></div>
            <div id="meetings-container"></div>
            <div id="description"></div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
<div id='calendar'></div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.2.0/main.css" integrity="sha256-/rB/IDulpFpJSHjrUgRHzB99AnJh3RBNrUOpF+4QIKA=" crossorigin="anonymous">
<script type="application/javascript" src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@5.2.0/main.min.js" integrity="sha256-U+VlpMlWIzzE74RY4mZL4MixQg66XWfjEWW2VUxHgcE=" crossorigin="anonymous"></script>
<script type="application/javascript">
document.addEventListener('DOMContentLoaded', function() {
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
        $('#modal-title').html(event.title);
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
        $('#fullCalModal').modal();
    },
    resourceGroupField: 'site',
    events: {!! $events !!},
    resources: {!! $resources !!},
  });

  calendar.render();
});

</script>
@endsection
