
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('reject-program').addEventListener('click', function () {
        $('#reject-program-modal').modal();
    });

  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    schedulerLicenseKey: '0970509849-fcs-1598830799',
    themeSystem: 'bootstrap',
    timeZone: 'UTC',
    buttonText: {
        today: 'Today'
    },
    editable: false, // don't allow event dragging
    eventResourceEditable: true, // except for between resources
    eventColor: eventColor,
    initialView: 'resourceTimelineDay',
    initialDate: initialDate,
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
            resource_ids: resourceIds
        }).then(data => {
            event.setProp('title', data.title);
            event.setExtendedProp('site_name', data.site_name);
        });
    },
    eventClick:  function(info) {
        const event = info.event;
        $('#reject-program-id').val(event.id);
        $('#approve-program-id').val(event.id);
        $('.program-title').html('#' + event.id + ' - ' + event.title);
        $('.program-title').attr('href', showProgramUrl + event.id);
        $('#description-of-meetings').html(event.extendedProps.description_of_meetings);
        $('#program-times').html(event.extendedProps.program_times);
        populateOverallStatus(event);
        populateContributorsTable(event);
        populateContributorActionsForm(event);
        let meetings = [];
        for (const meeting in event.extendedProps.meetings) {
            meetings.push(event.extendedProps.meetings[meeting].start_date);
        }
        $('#description-of-meetings').prop('title', 'Meets: ' + meetings.join(', '));
        $('#proposed-at').html('Proposed on: ' + event.extendedProps.proposed_at);
        $('#ages-string').html(event.extendedProps.ages_string);
        $('#enrollments').html('Min/Max: ' + event.extendedProps.min_enrollments + '/' + event.extendedProps.max_enrollments);
        $('#site-name').html(event.extendedProps.site_name);
        $('#site-name').prop('title', event.extendedProps.site_address);
        var resources = event.getResources();
        $('#location-name').html(resources[0].title);

        $('#program-details-modal').modal();
    },
    resourceGroupField: 'site',
    events: events,
    resources: resources,
  });

  calendar.render();
});

function populateOverallStatus(event) {
    document.getElementById('program-overall-status').innerHTML = `<div style="font-size:1.5rem" class="${event.extendedProps.status_class_string} font-weight-bold text-center mb-3">${event.extendedProps.status_string}</div>`
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
        document.getElementById('approve-program').style.display = 'none';
    } else {
        document.getElementById('approve-program-form').style.display = 'block';
        document.getElementById('approve-program').style.display = 'block';
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
    url = updateLocationsUrl;
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
