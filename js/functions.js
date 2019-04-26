function setEmail(what)
{
    $.ajax({
        type: "GET",
        url: 'sessionData.php',
        data: {
            data: "userEmail"
        },
        success: function(data) {
            $(what).html(data);
        }
    });
}

function setTotalScores(what)
{
    $.ajax({
        type: "GET",
        url: 'sessionData.php',
        data: {
            data: "userTotalScore"
        },
        success: function(data) {
            $(what).html(data);
        }
    });
}

function setFinishedExercise(what)
{
    $.ajax({
        type: "GET",
        url: 'sessionData.php',
        data: {
            data: "userFinishedExercises"
        },
        success: function(data) {
            $(what).html(data);
        }
    });
}

function setUserAverageScore(what)
{
    $.ajax({
        type: "GET",
        url: 'sessionData.php',
        data: {
            data: "userAverageScore"
        },
        success: function(data) {
            $(what).html(data);
        }
    });
}

function loadEnv()
{
    //Let's set the UI
    $("#loginFormInner").fadeOut("fast", function () { $("#loginLoader").fadeIn(); });

    $("#loginUsernameTxt").attr("disabled", "");
    $("#loginPasswordTxt").attr("disabled", "");

    $("#loginUsernameTxt").val("");
    $("#loginPasswordTxt").val("");

    setEmail(".userEmail");
    
    setTotalScores('.userTotalScore');
    setFinishedExercise('.userFinishedExercises');
    setUserAverageScore('.userAverageScore');

    M.toast({html: 'Login Success'});

    //Display loaded data
    setTimeout(function () {
        $("#loginLoader").fadeOut("fast", function () { $("#loginFormInner").fadeIn(); });

        $('#loginForm').fadeOut("fast", function () {
            $("#mainMenu").fadeIn("fast");

            $("#loginUsernameTxt").removeAttr("disabled");
            $("#loginPasswordTxt").removeAttr("disabled");
        });
    }, 2000);
}
