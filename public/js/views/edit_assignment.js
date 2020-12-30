$(document).ready(function () {
    $('#apply-signature-button').click(function () {
        mxAssignmentDocumentSigned(assignment);
    });
});

$(document).ready(function () {
    $('#approve-assignment-button').click(function () {
        mxAssignmentApproved(assignment);
    });
});

$(document).ready(function () {
    $('#complete-assignment-button').click(function () {
        mxAssignmentCompleted(assignment);
    });
});

$(document).ready(function () {
    $('#delete-assignment-button').click(function () {
        mxAssignmentDeleted(assignment);
    });
});

$(document).ready(function () {
    $('#assign-task-button').click(function () {
        mxTaksAssigned(task);
    });
});