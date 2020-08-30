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
            slotDuration: { days: 1 },
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
        if (info.oldEvent.getResources()[0].id == '0_0') {
            if (!confirm("You are changing the location of an off-site program. Are you sure?")) {
                info.revert();
            }
        }
        var event = info.event;
        var resources = event.getResources();
        var resourceIds = resources.map(function(resource) { return resource.id });
        if (resourceIds[0] == '0_0') {
            alert('Please select a specific site for this program, or put it in "Site TBD"');
            info.revert();
            return;
        }
        event.setProp('title', event.title + ' -saving');
        updateLocations({
            program_id: info.event.id,
            location_ids: resourceIds
        }).then(data => {
            event.setProp('title', data.title);
            event.setExtendedProp('site_name', data.site_name);
        });
    },
    eventClick:  function(info) {
        const event = info.event;
        $('#reject-program-id').val(event.id);
        $('#approve-program-id').val(event.id);
        $('.program-title').html(event.id + ' ' + event.title);
        $('#description-of-meetings').html(event.extendedProps.description_of_meetings);
        $('#program-times').html(event.extendedProps.program_times);
        populateOverallStatus(event);
        populateContributorsTable(event);
        populateContributorActionsForm(event);
        let meetings = [];
        for (const meeting in event.extendedProps.meetings) {
            meetings.push(event.extendedProps.meetings[meeting].start_date);
        }
        $('#meetings-container').html('Meets: ' + meetings.join(', '));
        $('#proposed-at').html('Proposed At: ' + event.extendedProps.proposed_at);
        $('#ages-string').html(event.extendedProps.ages_string);
        $('#site-location').html(event.extendedProps.site_name);

        $('#program-details-modal').modal();
    },
    resourceGroupField: 'site',
    events: {!! $events !!},
    resources: {!! $resources !!},
  });

  calendar.render();
});

function populateOverallStatus(event) {
    document.getElementById('program-overall-status').innerHTML = `<div style="font-size:1.5rem" class="${event.extendedProps.status_class_string} font-weight-bold text-center">${event.extendedProps.status_string}</div>`
}

function populateContributorsTable(event) {
    let contributorRows = '';
    event.extendedProps.contributors.forEach(contributor => {
        contributorRows += getContributorRow(contributor);
    });
    document.getElementById('program-contributors-rows').innerHTML = contributorRows;
}

function getContributorRow(contributor) {
    return `<tr>
                <th>${contributor.name}</th>
                <td>${getContributorFlag(contributor)}</td>
            </tr>`;
}

function getContributorFlag(contributor) {
    return `<div class="${contributor.class_string} font-weight-bold text-center">${contributor.status_string}</div>`;
}

function populateContributorActionsForm(event) {
    if (event.extendedProps.is_fully_approved) {
        document.getElementById('approve-program-form').style.display = 'none';
    } else {
        document.getElementById('approve-program-form').style.display = 'block';
        let actionOptions = [];
        const defaultActionOptions = [
                                        `<option value="approve_all">Approve on behalf of all Contributors</option>`,
                                    ];
        event.extendedProps.contributors.forEach(contributor => {
            if (!contributor.approved_by) {
                actionOptions.push(getContributorActionOption(contributor));
            }
        });
        actionOptions = actionOptions.concat(defaultActionOptions);
        document.getElementById('program-contributor-actions').innerHTML = actionOptions;
    }
}

function getContributorActionOption(contributor) {
    return `<option value="${contributor.id}">Approve on behalf of ${contributor.name}</option>`;
}

async function updateLocations(data = {}) {
    url = "{{ route('tenant:api.programs.locations.update', tenant()->slug) }}";
    return asyncRequest(data, url);
}

async function asyncRequest(data = {}, url) {
    const response = await fetch(url, {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        redirect: 'follow',
        referrerPolicy: 'no-referrer',
        body: JSON.stringify(data)
    });
    return response.json();
}

</script>
@endsection
