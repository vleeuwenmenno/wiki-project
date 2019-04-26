<?php
session_start();

// If session variable is not set it will redirect to login page
if (!isset($_SESSION['email']) || empty($_SESSION['email']))
    header("location: login-page.php");

include 'vars.php';
?>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css"
          integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
          crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- JQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- Define our own stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flexboxgrid/6.3.1/flexboxgrid.css">
    <link rel="stylesheet"
          href="https://cdn.rawgit.com/konpa/devicon/df6431e323547add1b4cf45992913f15286456d3/devicon.min.css">

    <link rel="stylesheet" href="./css/main.css" id="rel">
    <link rel="stylesheet" href="./css/management.css" id="rel">

    <!-- Define our own scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha256/0.9.0/sha256.min.js"></script>

    <?php if ($_CFG["allowRegister"] == true) { ?>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    <?php } ?>
</head>

<body>
<!-- Sidenav -->
<ul id="slide-out" class="sidenav sidenav-fixed">
    <li>
        <div class="user-view">
            <div class="background">
                <img src="./img/header.jpg" width="640">
            </div>
            <a href="#user">
                <i class="devicon-devicon-plain user-icon"></i>
            </a>
            <a href="#name">
                <span class="white-text name">Management</span>
            </a>
            <a href="#email">
                <span class="white-text email">
                    <b><?php echo $_SESSION["email"]; ?></b></span>
            </a>
        </div>
    </li>
    <div class="tabs-vertical ">
        <div class="col s4 m3 l2">
            <ul class="tabs">
                <li class="tab">
                    <a class="waves-effect waves-cyan" href="#test1"><i class="material-icons">person</i>Users</a>
                </li>
                <li class="tab disabled">
                    <a class="waves-effect waves-cyan" href="#test2"><i class="material-icons">show_chart</i>Stats</a>
                </li>
            </ul>
        </div>
        <div class="col s8 m9 l6">
            <div id="appsDir" class="tab-content"></div>
            <div id="emailDir" class="tab-content"></div>
            <div id="codeDir" class="tab-content"></div>
        </div>
    </div>
</ul>
<!-- Sidenav -->

<!-- navbar -->
<nav>
    <div class="nav-wrapper mainNav dark-color-1">
        <a href="#" data-target="slide-out" class="sidenav-trigger">
            <i class="material-icons">menu</i>
        </a>
        <ul class="right hide-on-med-and-down">
            <!-- <li>
                <a href="sass.html">Sass</a>
            </li> -->
        </ul>
        <a href="index.php" class="left backButton"><i class="material-icons">arrow_back</i></a>
        <ul class="right hide-on-med-and-down">
            <li>
                <div class="input-field">
                    <input id="navBarSearch" type="search" required>
                    <label class="label-icon" for="search">
                        <i class="material-icons searchIcon">search</i>
                    </label>
                    <i class="material-icons">close</i>
                </div>
            </li>
            <li>
                <a href="#" data-target='navbarDropdown' class="dropdown-trigger">
                    <i class="material-icons">more_vert</i>
                </a>
                <ul id='navbarDropdown' class='dropdown-content dropdown-wide'>
                    <?php if (!isset($_SESSION['email'])) { ?>
                        <li><a class="dark-text-color-2" href="login-page.php">Inloggen</a></li>
                    <?php } else if (isset($_SESSION['email'])) { ?>
                        <li><a class="dark-text-color-2" href="logout.php?r=index.php">Uitloggen</a></li>
                    <?php } ?>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<!-- navbar -->

<!-- page -->
<main>

    <div id="test1" class="col s12">
        <div class="row">
            <div class="col s12 m8 offset-m1 xl8 offset-xl1">
                <h4>Users   WIP!    Not Functional</h4>

                <table class="highlight responsive-table tborderless">
                    <!-- <label style="padding-left: 5px;">
                        <input type="checkbox" class="filled-in" id="checkall"/>
                        <span style="padding-left: 0px;">
                    </label>
                    <span style="margin-left: 2em; vertical-align:super;">Select all <span style="color: #9e9e9e;">( 'counter for users' Users)</span></span> -->
                    <div class="divider"></div>
                    <thead>
                    <tr>
                        <!-- <th></th> -->
                        <th>NAME</th>
                        <th>E-MAIL</th>
                        <th>MEMBER SINCE</th>
                        <th>ADMIN</th>
                        <th>OPTIONS</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    $sql = "SELECT id, username, email, mod_timestamp, userType FROM members";

                    if (!$result = $con->query($sql)) {
                        die('{ "status": "' . $con->error . '", "sql": "' . $sql . '" }');
                    }

                    while ($row = $result->fetch_assoc()) { ?>
                        <tr data-id="<?php echo $row["id"];?>" data-userType="<?php echo $row["userType"];?>">
                            <!-- <td>
                                <label style="padding-right: 10px;">
                                    <input type="checkbox" class="filled-in usercheck"/>
                                    <span style="padding-left: 0px;">
                                </label>
                            </td> -->
                            <td><?php echo $row['username'] ;?></td>
                            <td><?php echo $row['email'] ;?></td>
                            <td><?php echo $row['mod_timestamp'] ;?></td>
                            <td>
                                <div class="switch">
                                    <label>
                                        <?php if ($row['userType'] == 'Supreme Leader') {
                                            echo '<input checked class="adminswitch" type="checkbox">';
                                        } else {
                                            echo '<input class="adminswitch" type="checkbox">';
                                        } ?>
                                        <span class="lever"></span>
                                    </label>
                                </div>
                            </td>
                            <td>
                            <a class="deleteuserbtn"><i class="material-icons">delete_forever</i></a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="test2" class="col s12">Test 2</div>
</main>
<!-- page -->

<!-- Define our own scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha256/0.9.0/sha256.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/mixitup/2.1.11/jquery.mixitup.min.js'></script>

<script src="./js/management.js"></script>
</body>

</html>