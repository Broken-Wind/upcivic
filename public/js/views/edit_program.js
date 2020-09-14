$(document).ready(function () {
    if (typeof newlyCreated !== 'undefined' && null != newlyCreated) {
        mxProgramCreated(program);
    }
    $('#send-program-button').click(function () {
        mxProgramSent(program);
    });
});
