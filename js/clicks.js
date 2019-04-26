
$('#showRegisterBtn').click(function () {
    $('#loginForm').fadeOut("fast", function () {
        $('#registerForm').fadeIn("fast");
    });
});

$('#hideRegisterBtn').click(function () {
    $('#registerForm').fadeOut("fast", function () {
        $('#loginForm').fadeIn("fast");
    });
});

function mainMenuBtn_Click()
{
    setEmail(".userEmail");
    
    setTotalScores('.userTotalScore');
    setFinishedExercise('.userFinishedExercises');
    setUserAverageScore('.userAverageScore');
    
    $("#pageBackgroundImage").attr("src", "img/bg1.jpg");
    
    $('#levelOverview').fadeOut("fast", function () {
        $('#levelOverviewContent').html("");
        $("#mainMenu").fadeIn("fast");
    });
}

$("#mainMenuBtn").click(mainMenuBtn_Click);

function loginBtn_Click() {
    $("#loginFormInner").fadeOut("fast", function () { $("#loginLoader").fadeIn(); });

    if ($('#loginUsernameTxt').val() != "")
    {
        if ($('#loginPasswordTxt').val() != "")
        {
            $.ajax({
                url: "login.php",
                type: "get",
                data: {
                    userName: $('#loginUsernameTxt').val(),
                    password: sha256($('#loginPasswordTxt').val())
                },
                success: function(data) {
                    var response = JSON.parse(data);
                    console.log(response);
                    if (response['login'] == "ok")
                    {
                        loadEnv();
                        window.location = "index.php";
                    }
                    else  if (response['login'] == "ok_not_activated")
                    {
                        $("#loginLoader").fadeOut("fast", function () { $("#loginFormInner").fadeIn(); });
                        M.toast({html: 'Account isn\'t active yet, check email.'});
                        $('#loginUsernameTxt').focus();
                        $("#loginLoader").hide();
                    }
                    else if (response['login'] == "fail")
                    {
                        $("#loginLoader").fadeOut("fast", function () { $("#loginFormInner").fadeIn(); });
                        M.toast({html: 'Username or password is incorrect.'});
                        $('#loginUsernameTxt').focus();
                    }
                    else
                    {
                        $("#loginLoader").fadeOut("fast", function () { $("#loginFormInner").fadeIn(); });
                        M.toast({html: 'Ouch, something went wrong trying to login'});
                    }
                },
                error: function(xhr) {
                    $("#loginLoader").fadeOut("fast", function () { $("#loginFormInner").fadeIn(); });
                    M.toast({html: 'Ouch, something went wrong trying to login'});
                    console.log(xhr);
                }
            });
        }
        else
        {
            $("#loginLoader").fadeOut("fast", function () { $("#loginFormInner").fadeIn(); });
            M.toast({html: 'Please enter your password before clicking login.'});
        }
    }
    else
    {
        $("#loginLoader").fadeOut("fast", function () { $("#loginFormInner").fadeIn(); });
        M.toast({html: 'Please enter your credentials before clicking login.'});
    }
}

$('#loginBtn').click(loginBtn_Click);

function registerBtn_Click() {
    var response = grecaptcha.getResponse();

    if(response.length == 0)
    {
        M.toast({html: 'Please confirm that you are not a robot.'});
    }
    else
    {
        if ($('#regPasswordTxt').val() == $('#regPasswordConfTxt').val())
        {
            if ($('#regEmailTxt').val() != "")
            {
                if ($('#regEmailTxt').val() != "")
                {
                    $.ajax({
                        url: "register.php",
                        type: "get",
                        data: {
                            email: encodeURIComponent($('#regEmailTxt').val()),
                            userName: $('#regUsernameTxt').val(),
                            password: sha256($('#regPasswordConfTxt').val())
                        },
                        success: function(data) {
                            var response = JSON.parse(data);
                            console.log(response);
                            if (response['status'] == "ok")
                            {
                                $('#registerForm').fadeOut("fast", function () {
                                    $('#loginForm').fadeIn("fast", function () {
                                        $("#loginUsernameTxt").focus();
                                    });
                                });

                                M.toast({html: 'Your account has been created succesfully.'});
                                M.toast({html: 'Please check your email for the activation link.'});

                                $('#regEmailTxt').val("");
                                $('#regUsernameTxt').val("");
                                $('#regPasswordTxt').val("");
                                $('#regPasswordConfTxt').val("");

                                grecaptcha.reset();
                            }
                            else if (response['status'] == "username_email_used")
                            {
                                M.toast({html: 'Sorry, this username or email is already in use.'});
                                $('#regUsernameTxt').focus();
                            }
                            else
                            {
                                M.toast({html: 'Ouch, something went wrong. Please try again later.'});
                            }
                        },
                        error: function(xhr) {
                            M.toast({html: 'Ouch, something went wrong. Please try again later.'});
                            console.log(xhr);
                        }
                    });
                }
                else
                {
                    M.toast({html: 'Please enter your desired username'});
                }
            }
            else
            {
                M.toast({html: 'Please enter your email'})
            }
        }
        else
        {
            M.toast({html: 'Passwords do not match, please enter it again'})
            $('#regPasswordConfTxt').val('');
        }
    }
}

$('#registerBtn').click(registerBtn_Click);

function logoutBtn_Click()
{
    $("#loginFormTitle").html("Please login to continue");
    $.ajax({type: "get", url: "logout.php", success: function() {
        $('#mainMenu').fadeOut("fast", function () {
            $("#loginForm").fadeIn("fast");

            $("#loginUsernameTxt").removeAttr("disabled");
            $("#loginPasswordTxt").removeAttr("disabled");
        });
    }});
}

$('#logoutBtn').click(logoutBtn_Click);
