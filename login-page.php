<?php
    include ('vars.php');
    session_start();
?>
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
                    <h1>
                        <?php echo $_CFG["siteName"]; ?>
                    </h1>
                    <h5 id="loginFormTitle">Please login to continue</h5>
                </hgroup>
                <form class="credentialForm">
                    <div id="loginFormInner">
                        <div class="input-field col s6">
                            <input id="loginUsernameTxt" type="text" class="validate">
                            <label for="loginUsernameTxt">Username or Email</label>
                            </label>
                        </div>
                        <div class="input-field col s6">
                            <input id="loginPasswordTxt" type="password" class="validate">
                            <label for="loginPasswordTxt">Password</label>
                        </div>
                        <a class="forgot-pw" href="recovery.php">Forgot password?</a>
                        <div class="input-field col s12">
                            <a class="waves-effect waves-light btn blue" id="loginBtn">Login</a>
                            <?php if ($_CFG["allowRegister"] == true) { ?>
                            <a class="waves-effect waves-light btn blue lighten-1" id="showRegisterBtn">Register</a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="sk-folding-cube" id="loginLoader" hidden>
                        <div class="sk-cube1 sk-cube"></div>
                        <div class="sk-cube2 sk-cube"></div>
                        <div class="sk-cube4 sk-cube"></div>
                        <div class="sk-cube3 sk-cube"></div>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($_CFG["allowRegister"] == true) { ?>
        <div id="registerForm" hidden>
            <hgroup>
                <h1>
                    <?php echo $_CFG["siteName"]; ?>
                </h1>
                <h3>Register a new account</h3>
            </hgroup>
            <form class="credentialForm">
                <div class="input-field col s6">
                    <input id="regEmailTxt" type="email" class="validate">
                    <label for="regEmailTxt">Email</label>
                </div>
                <div class="input-field col s6">
                    <input id="regUsernameTxt" type="text" class="validate">
                    <label for="regUsernameTxt">Username</label>
                </div>
                <div class="input-field col s6">
                    <input id="regPasswordTxt" type="password" class="validate">
                    <label for="regPasswordTxt">Password</label>
                </div>
                <div class="input-field col s6">
                    <input id="regPasswordConfTxt" type="password" class="validate">
                    <label for="regPasswordConfTxt">Password</label>
                </div>
                <div class="g-recaptcha" data-sitekey="6Lf7Oj4UAAAAAAgGQCRZYC2y0PuAvfQ3qkkzkuJy"></div>
                <div class="input-field col s12">
                    <a class="waves-effect waves-light btn blue" id="registerBtn">Register</a>
                    <a class="waves-effect waves-light btn blue lighten-1" id="hideRegisterBtn">Cancel</a>
                </div>
            </form>
        </div>
        <?php } ?>

        <div id="mainMenu" hidden>
            <hgroup>
                <h2>
                    <?php echo $_CFG['siteName']; ?>
                </h2>
                <h5>Welcome back
                    <span id="userEmail" class="userEmail"></span>
                </h5>
            </hgroup>
            <form class="credentialForm">
                <div class="input-field col s12">
                    <a class="waves-effect waves-light btn blue lighten-1" href="logout.php?r=index.php">Logout</a>
                </div>
            </form>
        </div>

        <script src="./js/functions.js"></script>
        <script src="./js/clicks.js"></script>
        <script src="./js/keyevents.js"></script>
        <script src="./js/forms.js"></script>
        <script>
            $(document).ready(function () {
                <?php
                if (isset($_SESSION['userName']))
                {
                    ?>
                loadEnv();
                <?php
                }
                ?>
            });
        </script>
    </body>

    </html>