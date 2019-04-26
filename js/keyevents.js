$(document).ready(function() {
    $('#loginUsernameTxt').keypress(function (e) {
        if (e.which == 13)
        {
            loginBtn_Click();
        }
    });

    $('#loginPasswordTxt').keypress(function (e) {
        if (e.which == 13)
        {
            loginBtn_Click();
        }
    });
});
