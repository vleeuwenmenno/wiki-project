<?php
include('vars.php');
session_start();
?>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css"
          integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp"
          crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- JQuery -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- Define our own stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flexboxgrid/6.3.1/flexboxgrid.css">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet"
          href="https://cdn.rawgit.com/konpa/devicon/df6431e323547add1b4cf45992913f15286456d3/devicon.min.css">

    <?php if ($_CFG["allowRegister"] == true) { ?>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    <?php } ?>
</head>

<body>
<header>
    <div class="header">
        <div class="container">
            <nav class="transparent z-depth-0">
                <div class="nav-wrapper">
                    <a class="brand-logo" href="index.php"><?php echo $_CFG["siteName"]; ?></a>
                    <ul class="right hide-on-med-and-down" id="nav-mobile">
                        <?php if (isset($_SESSION['email'])) { ?>
                            <li><a href="management.php">Management</a></li>
                            <li><a href="logout.php?r=index.php">Logout</a></li>
                        <?php } else if (!isset($_SESSION['email'])) { ?>
                            <li><a href="login-page.php">Login</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </nav>

            <div>
                <h3 class="center-align white-text logo-icon"><i class="devicon-devicon-plain"></i></h3>
            </div>
            </nav>

            <nav class="headerSearch dark-color-3">
                <div class="nav-wrapper">
                    <div class="input-field">
                        <input id="search" required="" type="search" autocomplete="off" value="<?php echo $_GET["search"];?>">
                        <label class="label-icon" for="search">
                            <i class="material-icons searchBarIcon">search</i>
                        </label>
                        <i class="material-icons">close</i>
                    </div>
                </div>
            </nav>
            <script>
            $(document).ready(function () {
                $('#search').autocomplete({
                    data: {
                        <?php include("search.php"); ?>
                    },
                    limit: 20,
                    onAutocomplete: function(data) {
                        window.location = "searchPage.php?search=" + encodeURIComponent($("#search").val());
                    },
                    minLength: 1, // The minimum length of the input for the autocomplete to start. Default: 1.
                    scrollLength: 2,
                });

                $("#search").keyup(function (e) {
                    if ((e.keyCode === 13)) 
                    {
                        window.location = "searchPage.php?search=" + encodeURIComponent($("#search").val());
                    }
                });
            });
            </script>
        </div>
    </div>
</header>

<main>
    <?php 
        $searchString = urldecode($_GET["search"]);
        $searchStringWords = preg_split('/\s+/', $searchString);

        $sql = "SELECT * FROM wikipagedetails INNER JOIN wikipage ON wikipage.wikipageid = wikipagedetails.wikipageid WHERE ";

        foreach ($searchStringWords as $key=>$word)
        {
            $sql = $sql . " pageContent LIKE '%" . $word . "%' OR";
            $sql = $sql . " pageTitle LIKE '%" . $word . "%' OR";  
        }

        $sql = substr($sql, 0, strlen($sql) -2);
        $sql .= ";";
        
        if (!$result = $con->multi_query($sql)) {
            die('{ "status": "' . $con->error . '", "sql": "' . $sql . '" }');
        }
        
        $noResult = true;
        $langId = 0;
        do 
        {
            if ($result = mysqli_store_result($con)) 
            {
                while ($row = mysqli_fetch_array($result)) 
                {
                    $noResult = false;
                    ?>
                    <div class="row">
                        <div class="col s2"></div>
                        <div class="col s8">
                            <div class="card dark-color-3">
                                <div class="card-content white-text">
                                <span class="card-title"><?php echo $row['pageTitle']; ?></span>
                                <p>
                                    <?php echo substr($row['pageContent'], 0, 128); ?>
                                </p>
                                </div>
                                <div class="card-action right-align">
                                    <a href="<?php echo './main.php?lang=' .$row['languageId'] . '&id=' .$row['wikiPageId']; ?>">
                                        <i class="material-icons light-text-color-1">arrow_forward</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col s2"></div>
                    </div>
                    <?php
                }
                mysqli_free_result($result);
            }      
        } 
        while (mysqli_next_result($con));

        if ($noResult)
        {
            echo "<br /><center>Geen resultaat gevonden met zoekterm: '" . $searchString . "'</center>";
        }
    ?>
</main>

<footer class="page-footer dark-color-3">
    <div class="container">
        <div class="row">
            <div class="col l12 s12">
                <h5 class="white-text"><?php echo $_CFG["siteName"]; ?></h5>
                <p class="grey-text text-lighten-4"></p>
            </div>
        </div>
    </div>
    <div class="footer-copyright dark-color-2">
        <div class="container">
            © 2018 Designed and programmed by Menno van Leeuwen & Dunccan Groenendijk
        </div>
    </div>
</footer>

<!-- Define our own scripts -->
<script src="./js/index.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha256/0.9.0/sha256.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/mixitup/2.1.11/jquery.mixitup.min.js'></script>
</body>

</html>