function mxProgramCreated(program) {
    mixpanel.track('Program Created', {
        'Program ID': program.id,
        'Program Name': program.name,
        'Proposing Organization ID': program.proposing_organization_id,
        'Initial Recipient Organization IDs': program.recipient_organization_ids
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
        'Site IDs': program.site_ids,
        'Location IDs': program.location_ids,
        'Contributing Organization IDs': program.contributing_organization_ids
    });
}

function mxProgramApproved(program) {
    mixpanel.track('Program Approved', {
        'Program ID': program.id,
        'Program Name': program.name,
        'Site IDs': program.site_ids,
        'Location IDs': program.location_ids,
        'Contributing Organization IDs': program.contributing_organization_ids,
        'Approved on Behalf of Organization IDs': program.approved_on_behalf_of_organization_ids
    });
}

function mxProgramRejected(program) {
    mixpanel.track('Program Rejected', {
        'Program ID': program.id,
        'Program Name': program.name,
        'Site IDs': program.site_ids,
        'Location IDs': program.location_ids,
        'Contributing Organization IDs': program.contributing_organization_ids,
        'Rejection Reason': program.rejection_reason
    });
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
