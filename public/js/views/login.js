$(document).ready(function () {
    $('#register-button').click(function () {
        var user = {
            'email': $('#email').val()
        };
        mxLogin(user);
    });
});
