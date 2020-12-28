
function mxAssignmentApproved(assignment) {
    mixpanel.track('Assignment Approved', {
        'Assignment ID': assignment.id,
        'Assignment Name': assignment.name,
        'Assigned By Organization ID': assignment.assigned_by_organization_id,
        'Assigned To Organization ID': assignment.assigned_to_organization_id,
    });
    mixpanel.people.increment('Lifetime Approved Assignments');
    mixpanel.identify();
}

function mxDocumentSigned(assignment) {
    mixpanel.track('Assignment Document Signed', {
        'Assignment ID': assignment.id,
        'Assignment Name': assignment.name,
        'Assigned By Organization ID': assignment.assigned_by_organization_id,
        'Assigned To Organization ID': assignment.assigned_to_organization_id,
    });
    mixpanel.people.increment('Lifetime Signed Documents');
    mixpanel.identify();
}

function mxProgramCreated(program) {
    mixpanel.track('Program Created', {
        'Program ID': program.id,
        'Program Name': program.name,
        'Proposing Organization ID': program.proposing_organization_id,
        'Recipient Organization IDs': program.recipient_organization_ids,
        'Site IDs': program.site_ids,
        // 'Location IDs': program.location_ids,
        'Start Date': program.start_date,
        'End Date': program.end_date,
        'Start Time': program.start_time,
        'End Time': program.end_time,
        'Meeting Start Dates': program.meeting_start_dates,
        'Meeting Count': program.meeting_count
    });
    mixpanel.people.set({
        'Last Program Created': new Date(program.created_at).toISOString(),
    });
    mixpanel.people.set_once({
        'First Program Created': new Date(program.created_at).toISOString()
    });
    mixpanel.people.increment('Lifetime Programs Created');
    mixpanel.identify();
}

function mxProgramSent(program) {
    mixpanel.track('Program Sent', {
        'Program ID': program.id,
        'Program Name': program.name,
        'Proposing Organization ID': program.proposing_organization_id,
        'Recipient Organization IDs': program.recipient_organization_ids,
        'Site IDs': program.site_ids,
        // 'Location IDs': program.location_ids,
        'Start Date': new Date(program.start_date).toISOString(),
        'End Date': new Date(program.end_date).toISOString(),
        'Start Time': program.start_time,
        'End Time': program.end_time,
        'Meeting Start Dates': program.meeting_start_dates,
        'Meeting Count': program.meeting_count
    });
    mixpanel.people.set({
        'Last Program Sent': new Date().toISOString(),
    });
    mixpanel.people.set_once({
        'First Program Sent': new Date().toISOString()
    });
    mixpanel.people.increment('Lifetime Programs Sent');
    mixpanel.identify();
}

function mxProgramApproved(program) {
    mixpanel.track('Program Approved', {
        'Program ID': program.id,
        'Program Name': program.name,
        'Proposing Organization ID': program.proposing_organization_id,
        'Recipient Organization IDs': program.recipient_organization_ids,
        'Approved on Behalf of Organization IDs': program.approved_on_behalf_of_organization_ids,
        // 'Site IDs': program.site_ids,
        // 'Location IDs': program.location_ids,
        'Start Date': new Date(program.start_date).toISOString(),
        'End Date': new Date(program.end_date).toISOString(),
        'Start Time': program.start_time,
        'End Time': program.end_time,
        'Meeting Start Dates': program.meeting_start_dates,
        'Meeting Count': program.meeting_count
    });
    mixpanel.people.set({
        'Last Program Approved': new Date().toISOString(),
    });
    mixpanel.people.set_once({
        'First Program Approved': new Date().toISOString()
    });
    mixpanel.people.increment('Lifetime Programs Approved');
    mixpanel.identify();
}

function mxProgramRejected(program) {
    mixpanel.track('Program Rejected', {
        'Program ID': program.id,
        'Program Name': program.name,
        'Proposing Organization ID': program.proposing_organization_id,
        'Recipient Organization IDs': program.recipient_organization_ids,
        // 'Site IDs': program.site_ids,
        // 'Location IDs': program.location_ids,
        'Start Date': new Date(program.start_date).toISOString(),
        'End Date': new Date(program.end_date).toISOString(),
        'Start Time': program.start_time,
        'End Time': program.end_time,
        'Meeting Start Dates': program.meeting_start_dates,
        'Meeting Count': program.meeting_count,
        'Rejection Reason': program.rejection_reason
    });
    mixpanel.people.set({
        'Last Program Rejected': new Date().toISOString(),
    });
    mixpanel.people.set_once({
        'First Program Rejected': new Date().toISOString()
    });
    mixpanel.people.increment('Lifetime Programs Rejected');
    mixpanel.identify();
}

function mxTenantJoined(tenant) {
    mixpanel.track('Tenant Joined', {
        'Organization ID': tenant.organization_id,
        'Organization Name': tenant.name
    });

}

function mxAccountCreated(user) {
    mixpanel.alias(user.email);
    mixpanel.track('Account Created');
    mixpanel.people.set({
        '$email': user.email,
        '$name': user.name
    })
}

function mxLogin(user) {
    mixpanel.identify(user.email);
}
