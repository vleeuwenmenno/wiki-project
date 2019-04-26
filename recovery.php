<?php
    session_start();
    include('vars.php');

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require './phpMailer/Exception.php';
    require './phpMailer/PHPMailer.php';
    require './phpMailer/SMTP.php';
?>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<!-- Compiled and minified CSS -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
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

        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <body>
<?php
    if (isset($_GET['email']))
    {
        $recoveryStr = genStr(196);
        $email = $_GET['email'];
        $sql = "UPDATE `members` SET `recoverySecret` = '$recoveryStr' WHERE `members`.`email` = '$email';";

        if(!$result = $con->query($sql))
        {
            die('Error while attempting to execute this query (' . $con->error . ') (' . $sql . ')');
        }

        $mail = new PHPMailer(true);   

        try 
        {
            //Server settings
            //$mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = $_CFG["smtpHost"];
            $mail->SMTPAuth = true;
            $mail->Username = $_CFG["smtpUser"];
            $mail->Password = $_CFG["smtpPass"];
            $mail->SMTPSecure = $_CFG["smtpSecure"];
            $mail->Port = $_CFG["smtpPort"];

            //Recipients
            $mail->setFrom($_CFG["smtpUser"], $_CFG["siteName"] .' Mailer');
            $mail->addAddress($email, "");

            //Content
            $mail->isHTML(true);
            $mail->Subject =    $_CFG["siteName"] . ' - Account activation';
            $mail->Body    = 'You requested a password reset for your account, please follow the link to continue <a href="https://' . $_CFG["siteName"] . '/recovery.php?rk=' . $recoveryStr . '">link</a><br /><br />If this wasn\'t you then please ignore this email.';
            $mail->AltBody = 'You requested a password reset for your account, please follow the link to continue https://' . $_CFG["siteName"] . '/recovery.php?rk=' . $recoveryStr . '<br /><br />If this wasn\'t you then please ignore this email.';

            $mail->send();
 
            if(!$resultTwo = $con->query($sql))
            {
                ?>
                <script>
                    $(document).ready(function() {

                        M.toast({html: 'We failed to deliver you the email :('});
                    });
                </script>
                <?php
                echo '{ "status": "error", "cause": "' . urlencode($con->error) . '" }';
            }
        } 
        catch (Exception $e) 
        {
            ?>
                <script>
                    $(document).ready(function() {

                        M.toast({html: 'We failed to deliver you the email :('});
                    });
                </script>
            <?php
            echo '{ "status": "error-mailing", "cause": "' . urlencode($e) . '" }';
        }
        ?>
        <div class="containerlr" id="loginForm">
            <div class="right-half">
                <hgroup>
                    <h1><?php echo $_CFG["siteName"]; ?></h1>
                    <h5 id="loginFormTitle">Reset your password</h5>
                </hgroup>
                <div id="newPasswordForm">
                    <form class="credentialForm" style="width: 420px !important;">
                        <i class="large material-icons">check</i>
                        <h6>We'll send you an email with a reset link now.</h6>
                    </form>
                </div>
        <?php
    }
    else if (isset($_GET['rk']))
    {
        $secret = $_GET['rk'];

        if (empty($secret)) // Make sure we don't allow empty secrets!
            die();

        //Check if code is correct
        $sql = "SELECT * FROM members WHERE recoverySecret = '$secret';";

        if(!$result = $con->query($sql))
        {
            die('Error while attempting to execute this query (' . $con->error . ') (' . $sql . ')');
        }

        while($row = $result->fetch_assoc())
        {
        ?>
        <div class="containerlr" id="loginForm">
            <div class="right-half">
                <hgroup>
                    <h1><?php echo $_CFG["siteName"]; ?></h1>
                    <h5 id="loginFormTitle">Hi <?php echo $row['username']; ?>, let's create a new password.</h5>
                </hgroup>
                <div id="newPasswordForm">
                    <form class="credentialForm">
                        <div class="input-field col s6">
                            <input id="regPasswordTxt" type="password" class="validate">
                            <label for="regPasswordTxt">Password</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="regPasswordConfTxt" type="password" class="validate">
                            <label for="regPasswordConfTxt">Confirm</label>
                        </div>
                        <div class="input-field col s12">
                            <a class="waves-effect waves-light btn blue" id="resetBtn">Reset</a>
                            <a class="waves-effect waves-light btn blue lighten-1" href="index.php">Cancel</a>
                        </div>
                        <script>
                            $(document).ready(function() {
                                $("#resetBtn").on("click", function() {
                                    alert("Not Implemented!");
                                });
                            });
                        </script>
                    </form>
                </div>
        <?php
        }
    }
    else
    {
        ?>
        <div class="containerlr" id="loginForm">
            <div class="right-half">
              <hgroup>
                  <h1><?php echo $_CFG["siteName"]; ?></h1>
                  <h5 id="loginFormTitle">Please enter your email to send a reset link</h5>
              </hgroup>
              <form class="credentialForm">
                  <div id="loginFormInner">
                      <div class="input-field col s6">
                          <input id="recoveryEmailTxt" type="text" class="validate">
                          <label for="recoveryEmailTxt">Email</label></label>
                      </div>
                      <div class="g-recaptcha" data-sitekey="6Lf7Oj4UAAAAAAgGQCRZYC2y0PuAvfQ3qkkzkuJy"></div>
                      <div class="input-field col s12">
                          <a class="waves-effect waves-light btn blue" id="resetBtn">Request</a>
                          <a class="waves-effect waves-light btn blue lighten-1" href="index.php">Cancel</a>
                      </div>
                      <script>
                        function isCaptchaChecked() {
                            return grecaptcha && grecaptcha.getResponse().length !== 0;
                        }

                        $(document).ready(function() {
                            $("#resetBtn").on("click", function() {
                                if (isCaptchaChecked()) 
                                {
                                    if ($("#recoveryEmailTxt").val() != "")
                                    {
                                        window.location = "recovery.php?email=" + encodeURI($("#recoveryEmailTxt").val());
                                    }
                                    else
                                        M.toast({html: 'Enter a valid email.'});
                                }
                                else
                                    M.toast({html: 'Confirm that you are not a robot'});
                            });
                        });
                    </script>
                  </div>
                  <div class="sk-folding-cube" id="loginLoader" hidden>
                      <div class="sk-cube1 sk-cube"></div>
                      <div class="sk-cube2 sk-cube"></div>
                      <div class="sk-cube4 sk-cube"></div>
                      <div class="sk-cube3 sk-cube"></div>
                  </div>
              </form>
        <?php
    }
?>
            </div>
        </div>
    </body>
</html>
