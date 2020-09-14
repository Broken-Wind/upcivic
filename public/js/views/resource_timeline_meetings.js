
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('approve-program').addEventListener('click', function () {
      $('#approve-program-modal').modal();
  });

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
            program_id: info.event.groupId,
            resource_ids: resourceIds
        }).then(data => {
            const program = programs.find(program => program.id == event.groupId);
            event.setProp('title', data.title);
            program.site_name = data.site_name;
        });
    },

    eventClick:  function(info) {
        const event = info.event;
        const program = programs.find(program => program.id == event.groupId);
        $('#reject-program-id').val(event.groupId);
        $('#approve-program-id').val(event.groupId);
        $('.program-title').html('#' + event.groupId + ' - ' + event.title);
        $('.program-title').attr('href', showProgramUrl + event.groupId);
        $('#description-of-meetings').html(program.description_of_meetings);
        $('#program-times').html(program.program_times);
        populateOverallStatus(program);
        populateContributorsTable(program);
        populateContributorActionsForm(program);
        let meetings = [];
        for (const meeting in program.meetings) {
            meetings.push(program.meetings[meeting].start_date);
        }
        $('#description-of-meetings').prop('title', 'Meets: ' + meetings.join(', '));
        $('#proposed-at').html('Proposed on: ' + program.proposed_at);
        $('#ages-string').html(program.ages_string);
        $('#enrollments').html('Min/Max: ' + program.min_enrollments + '/' + program.max_enrollments);
        $('#site-name').html(program.site_name);
        $('#site-name').prop('title', program.site_address);
        var resources = event.getResources();
        $('#location-name').html(resources[0].title);

        $('#program-details-modal').modal();
    },
    resourceGroupField: 'site',
    events: events,
    resources: resources,
  });

  calendar.render();

  $('#approve-program').click(function () {
        let program = programs.find(program => program.id == $('#approve-program-id').val());
        const contributorId = $('#program-contributor-actions').val();
        let contributorIds;
        if (isNaN(contributorId)) {
            contributorIds = JSON.parse(program.recipient_organization_ids);
            contributorIds.push(program.proposing_organization_id)
        } else {
            contributorIds = [program.contributors.find(contributor => contributor.id == contributorId).organization_id];
        }
        program.approved_on_behalf_of_organization_ids = contributorIds;
        mxProgramApproved(program);
  });

  $('#submit-reject-program').click(function () {
        let program = programs.find(program => program.id == $('#reject-program-id').val());
        program.rejection_reason = $('#rejection-reason').val();
        mxProgramRejected(program);
  });

  $('.fc-next-button, .fc-prev-button, .fc-today-button').click(function() {
      fetchMeetings({
          initial_date: calendar.view.currentStart.toISOString(),
          end_date: calendar.view.currentEnd.toISOString()
      }).then(data => {
          programs = data.programs;
          var eventSources = calendar.getEventSources();
          var len = eventSources.length;
          for (var i = 0; i < len; i++) {
              eventSources[i].remove();
          }
          calendar.addEventSource(data.meetings);
          calendar.render();
      });
  });
});

function populateOverallStatus(program) {
    document.getElementById('program-overall-status').innerHTML = `<div style="font-size:1.5rem" class="${program.status_class_string} font-weight-bold text-center mb-3">${program.status_string}</div>`
}

function populateContributorsTable(program) {
    let contributorRows = '';
    program.contributors.forEach(contributor => {
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

function populateContributorActionsForm(program) {
    if (program.is_fully_approved) {
        document.getElementById('approve-program-form').style.display = 'none';
        document.getElementById('approve-program').style.display = 'none';
    } else {
        document.getElementById('approve-program-form').style.display = 'block';
        document.getElementById('approve-program').style.display = 'block';
        let actionOptions = [];
        const defaultActionOptions = [
                                        `<option class="approval-option" value="approve_all">Approve on behalf of all Contributors</option>`,
                                    ];
        program.contributors.forEach(contributor => {
            if (!contributor.approved_by) {
                actionOptions.push(getContributorActionOption(contributor));
            }
        });
        if (program.contributors.length > 2) {
            actionOptions = actionOptions.concat(defaultActionOptions);
        }
        document.getElementById('program-contributor-actions').innerHTML = actionOptions;
    }
}

function getContributorActionOption(contributor) {
    return `<option class="approval-option" value="${contributor.id}">Approve on behalf of ${contributor.name}</option>`;
}

async function updateLocations(data = {}) {
    url = updateLocationsUrl;
    return asyncRequest(data, url);
}

async function fetchMeetings(data = {}) {
    url = fetchMeetingsUrl;
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
