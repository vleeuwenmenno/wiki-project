<?php
    include ('vars.php');
    session_start();
?>
<?php if (isset($_GET['secret'])) { 

    $sql = "SELECT * FROM `members` WHERE activateSecret='" . $_GET['secret'] . "'";

    if(!$result = $con->query($sql))
    {
        die('Error while attempting to execute this query (' . $con->error . ') (' . $sql . ')');
    }

    $return = array();
    $i = 0;

    while($row = $result->fetch_assoc())
    {
        $sql = "UPDATE `members` SET `activateSecret` = '' WHERE `activateSecret` = '" . $_GET['secret'] . "';";
        if(!$result = $con->query($sql))
            die('{ "status": "error" }');
        else
            die('{ "status": "ok" }');
    }

    echo '{ "status": "error" }';
} else { ?>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!-- Compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
            crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <!-- Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>

        <!-- JQuery -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <!-- Define our own stylesheets -->
        <link rel="stylesheet" href="./css/login-page.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flexboxgrid/6.3.1/flexboxgrid.css">

        <!-- Define our own scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha256/0.9.0/sha256.min.js"></script>

        <?php if ($_CFG["allowRegister"] == true) { ?>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <?php } ?>
    </head>
    <body>
        <div class="containerlr" id="loginForm">
          <div class="right-half">
            <hgroup>
                <h1><?php echo $_CFG["siteName"]; ?></h1>
                <h5 id="loginFormTitle">Account activation</h5>
            </hgroup>
            <form class="credentialForm" id="activationForm">
                <div class="sk-folding-cube" id="loginLoader">
                    <div class="sk-cube1 sk-cube"></div>
                    <div class="sk-cube2 sk-cube"></div>
                    <div class="sk-cube4 sk-cube"></div>
                    <div class="sk-cube3 sk-cube"></div>
                </div>
            </form>
          </div>
        </div>
        <?php if (isset($_GET['s'])) { ?>
        <script>
            $(document).ready(function() {
                $.ajax({
                    url: "activate.php",
                    type: "get",
                    data: {
                        secret: '<?php echo $_GET['s']; ?>'
                    },
                    success: function(data) 
                    {
                        var response = JSON.parse(data);
                        console.log(response);
                        if (response['status'] == "ok")
                        {
                            $("#activationForm").html('\
                            <div class="input-field col s12">\
                                Your account has been activated succesfully, you can now login.<br /><br />\
                                <a class="waves-effect waves-light btn blue" href="index.php">Login</a>\
                            </div>');
                        }
                        else
                        {
                            $("#activationForm").html('\
                            <div class="input-field col s12">\
                                Hmm, something broke try again...<br /><br />\
                                <a class="waves-effect waves-light btn blue" href="index.php">Login</a>\
                            </div>');
                        }
                    },
                    error: function(xhr) 
                    {
                        $("#activationForm").html('\
                            <div class="input-field col s12">\
                                Hmm, something broke try again...<br /><br />\
                                <a class="waves-effect waves-light btn blue" href="index.php">Login</a>\
                            </div>');
                    }
                });
            });
        </script>
        <?php } ?>
    </body>
</html>
<?php } ?>