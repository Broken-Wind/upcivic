
document.addEventListener('DOMContentLoaded', function() {
    $('.manageInstructorsButton').on('click', function (event) {
        $('#manage-instructors-program-summary').html('Loading...');
        $('#manage-instructors-meetings').html('');
        let program;
        getProgram(
            $(this).data('program-id')
        ).then(program => {
            $('#manage-instructors-program-summary').html(getManageInstructorsProgramSummaryHtml(program));
            $('#manage-instructors-meetings').html(getManageInstructorsMeetingsHtml(program));
        });

        // #{{ $program['id'] }} - {{ $program['name'] }} at {{ $program['site']['name'] }}<br/>
        // {{ $program['description_of_meetings'] }}
        // {{ $program['start_time'] }}-{{ $program['end_time'] }}<br/>
    });
});

function getManageInstructorsProgramSummaryHtml(program) {
    return `#${program.id} - ${program.name} at ${program.site}<br />
    ${program.description_of_meetings}<br />
    ${program.start_time}-${program.end_time}<br />
    ${program.contributors}`;
}

function getManageInstructorsMeetingsHtml(program) {
    let meetingRows = '';
    program.meetings.forEach(meeting => {
        meetingRows += getMeetingRowHtml(meeting);
    })
    return meetingRows;
}

function getMeetingRowHtml(meeting) {
    return `<tr>
        <td>
            <input name="" id="" type="checkbox" value="checkedValue" aria-label="Text for screen reader">
        </td>
        <td>
            ${meeting.start_date}${meeting.start_date != meeting.end_date ? '-' . $meeting.end_date : '' }
        </td>
        <td>${meeting.start_time}-${meeting.end_time}</td>
        <td>${meeting.site}</td>
        <td>
            ${meeting.instructor_list}
        </td>
    </tr>`
}

async function getProgram(programId) {
    let data = {
        'program_id': programId
    }
    let url = getProgramUrl;
    return asyncRequest(data, url)
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
