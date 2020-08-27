@extends('layouts.app')
@section('head.additional')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style type="text/css">
    html, body {
    margin: 0;
    padding: 0;
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
    eventColor: '{{\App\Program::EVENT_UNAPPROVED_COLOR}}',
    initialView: 'resourceTimelineDay',
    initialDate: '{{ \Carbon\Carbon::now()->toDateString() }}',
    resourcesInitiallyExpanded: true,
    businessHours: {
        // days of week. an array of zero-based day of week integers (0=Sunday)
        daysOfWeek: [ 1, 2, 3, 4, 5 ], // Monday - Thursday
    },
    resourceAreaHeaderContent: 'Locations',
    views: {
        resourceTimelineDay: {
            type: 'resourceTimeline',
            duration: { weeks: 1 },
            buttonText: 'timeline',
            slotDuration: { days: 0 },
            slotLabelInterval: { days: 1 },
            slotMinWidth: 100,
            slotLabelFormat: [
                { month: 'long', year: 'numeric' }, // top level of text
                { month: 'numeric', day: 'numeric' } // lower level of text
            ],
            resourceAreaWidth: '20%',
        }
    },
    eventDrop: function(info) {
        // if (!confirm("Are you sure about this change?")) {
        //     info.revert();
        // }
        var event = info.event;
        var resources = event.getResources();
        var resourceIds = resources.map(function(resource) { return resource.id });
        event.setProp('title', event.title + ' -saving');
        updateLocations({
            program_id: info.event.id,
            location_ids: resourceIds
        }).then(data => {
            event.setProp('title', data.title);
            console.log(data); // JSON data parsed by `data.json()` call
        });
        console.log(resourceIds);
    },
    eventClick:  function(info) {
        const event = info.event;
        $('#reject-program-id').val(event.id);
        $('#approve-program-id').val(event.id);
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

async function updateLocations(data = {}) {
  // Default options are marked with *
  url = "{{ route('tenant:api.programs.locations.update', 'demo-host') }}";
  const response = await fetch(url, {
    method: 'POST', // *GET, POST, PUT, DELETE, etc.
    mode: 'cors', // no-cors, *cors, same-origin
    cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
    credentials: 'same-origin', // include, *same-origin, omit
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    redirect: 'follow', // manual, *follow, error
    referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
    body: JSON.stringify(data) // body data type must match "Content-Type" header
  });
  return response.json(); // parses JSON response into native JavaScript objects
}

</script>
@endsection
