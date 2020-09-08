$(document).ready(function () {
    $('#register-button').click(function () {
        var user = {
            'email': $('#email').val(),
            'name': $('#name').val()
        };
        mxAccountCreated(user);
    });
});
