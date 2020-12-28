$(document).ready(function () {
    $('#apply-signature-button').click(function () {
        mxDocumentSigned(assignment);
    });
});

$(document).ready(function () {
    $('#approve-assignment-button').click(function () {
        mxAssignmentApproved(assignment);
    });
});