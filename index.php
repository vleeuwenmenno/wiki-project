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
                    <a class="brand-logo" href="#"><?php echo $_CFG["siteName"]; ?></a>
                    <ul class="right hide-on-med-and-down" id="nav-mobile">
                        <?php if (isset($_SESSION['email'])) { ?>
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
                        <input id="search" required="" type="search" autocomplete="off">
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
    <div class="tag-animation hight">
        <div class="fixed-action-btn">
            <a class="btn-floating btn-large red filter" id="cancelFilterBtn" style="display: none;" data-filter="all">
                <i class="large material-icons">clear</i>
            </a>
        </div>


        <div id="tagAnimation" class="tag-animation-container container">
            <div class="row center-row">
                <?php
                $sql = "SELECT * FROM language WHERE 1;";

                if (!$result = $con->query($sql)) {
                    die('Error while attempting to execute this query (' . $con->error . ') (' . $sql . ')');
                }

                $cardCount = 0;
                ?>

                <?php while ($row = $result->fetch_assoc()) {
                $sqlInner = "SELECT * FROM languagetags INNER JOIN tags ON languagetags.tagId = tags.tagId WHERE languageId=" . $row["languageId"] . ";";

                if (!$resultInner = $con->query($sqlInner)) {
                    die('Error while attempting to execute this query (' . $con->error . ') (' . $sqlInner . ')');
                }

                $tags = array();

                echo '<div class="group-card-thing padding s6 m4 l3 mix ';
                while ($rowInner = $resultInner->fetch_assoc()) {
                    echo 'tag' . $rowInner["tagId"] . " ";
                    array_push($tags, $rowInner["tagName"]);
                }
                echo '">';

                $sqlInner = "SELECT wikiPageId, min(pageIndex) FROM wikipage WHERE languageId=" . $row["languageId"];

                if (!$resultInner = $con->query($sqlInner)) {
                    die('Error while attempting to execute this query (' . $con->error . ') (' . $sqlInner . ')');
                }

                while ($rowInner = $resultInner->fetch_assoc()) 
                {
                    $wikiPageId = $rowInner["wikiPageId"];
                }

                ?>
                <div class="card small hoverable">
                    <a href="main.php?lang=<?php echo $row["languageId"]; ?>&id=<?php echo $wikiPageId; ?>">
                        <i class="<?php echo $row["languageIcon"]; ?> card-icon dark-text-color-1"></i>
                    </a>
                    <div class="language-tags">
                        <?php
                        $sqlInner = "SELECT * FROM languagetags INNER JOIN tags ON languagetags.tagId = tags.tagId WHERE languageId=" . $row["languageId"] . ";";

                        if (!$resultInner = $con->query($sqlInner)) {
                            die('Error while attempting to execute this query (' . $con->error . ') (' . $sql . ')');
                        }

                        $i = 0;
                        while ($rowInner = $resultInner->fetch_assoc()) {
                            ?>
                            <div class="chip tag-button noselect">
                                <a class="filter"
                                   data-filter=".tag<?php echo $rowInner["tagId"]; ?>"><?php echo $rowInner["tagName"]; ?></a>
                            </div>
                            <?php $i++;
                        } ?>
                    </div>
                    <div class="card-action right-align">
                        <span style="float:left"><?php echo $row["languageName"]; ?></span>
                        <?php if (isset($_SESSION['email'])) { ?>
                        <a href="javascript:void(0)" class="editGroupBtn" data-languageId="<?php echo $row["languageId"]; ?>" data-languagetags="<?php echo htmlspecialchars(json_encode($tags), ENT_QUOTES, 'UTF-8'); ?>" data-languageName="<?php echo $row["languageName"]; ?>" data-languageDeveloper="<?php echo $row["languageDeveloper"]; ?>" data-languageIcon="<?php echo $row["languageIcon"]; ?>">
                            <i class="material-icons dark-text-color-3">edit</i>
                        </a>
                        <?php } ?>
                        <a href="main.php?lang=<?php echo $row["languageId"]; ?>">
                            <i class="material-icons dark-text-color-3">arrow_forward</i>
                        </a>
                    </div>
                </div>
            </div>
            <?php $cardCount++; } ?>
            <?php if (isset($_SESSION['email'])) { ?>
                <div class="group-card-thing padding s6 m4 l3 mix">
                    <div class="card small hoverable">
                        <a href="#addLangModal" class="modal-trigger"><i
                                    class="devicon-devicon-plain card-icon dark-text-color-1"></i></a>
                        <div class="card-action right-align">
                            <span style="float:left">Groep toevoegen</span>
                            <a href="#addLangModal" class="modal-trigger">
                                <i class="material-icons dark-text-color-3">library_add</i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- ========================== -->
    </div>

    </div>
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

<!-- Modals -->
<!-- Modal Structure -->
<?php if (isset($_SESSION['email'])) { ?>
    <div id="addLangModal" class="modal">
        <div class="modal-content">
            <h4>Groep toevoegen</h4>
            <p>
            <div class="row">
                <form class="col s12" autocomplete="off">
                    <div class="row">
                        <div class="input-field col s6">
                            <input id="languageName" type="text" class="validate">
                            <label for="languageName">Groep</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="languageDeveloper" type="text" class="validate">
                            <label for="languageDeveloper">Ontwikkelaar</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <div class="chips" id="languageTags"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="languageIcon" type="text" class="validate autocomplete">
                            <label for="languageIcon">Groep Logo</label>
                        </div>
                    </div>

                    <i class="devicon-devicon-plain devicon-preview" id="deviconPreviewer"></i>
                    <script>
                        var icons = {};
                        var iconsJson = {};

                        $(document).ready(function () {
                            
                            $('#languageTags').chips({
                                placeholder: 'Voeg tag toe...',
                                secondaryPlaceholder: '+Tag',
                                autocompleteOptions: {
                                    data: {
                                        <?php
                                        $sqlInner = "SELECT * FROM tags WHERE 1;";

                                        if (!$resultInner = $con->query($sqlInner)) {
                                            die('Error while attempting to execute this query (' . $con->error . ') (' . $sql . ')');
                                        }

                                        while($rowInner = $resultInner->fetch_assoc()) { ?>
                                        '<?php echo $rowInner["tagName"]; ?>': null,
                                        <?php } ?>
                                    },
                                    limit: Infinity,
                                    minLength: 1
                                }
                            });

                            $.getJSON("https://konpa.github.io/devicon/devicon.git/devicon.json", function (data) {
                                $.each(data, function () {
                                    icons[this.name] = null;
                                });
                                iconsJson = data;
                            });

                            $('#languageIcon').autocomplete({
                                data: icons,
                            });

                            $("#languageIcon").change(function () {
                                console.log("devicon");
                                console.log($("#languageIcon").val());
                                console.log(iconsJson.find(x => x.name === $("#languageIcon").val()).versions.font[0]);

                                var iconClass = "devicon" + "-" + $("#languageIcon").val() + "-" + iconsJson.find(x => x.name === $("#languageIcon").val()).versions.font[0];
                                console.log(iconClass);

                                $("#deviconPreviewer").removeAttr('class');
                                $("#deviconPreviewer").attr('class', '');
                                $("#deviconPreviewer").addClass("devicon-preview");
                                $("#deviconPreviewer").addClass(iconClass);
                            });
                        });
                    </script>
                </form>
            </div>
            </p>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0)" class="modal-close waves-effect waves-grey btn-flat">Annuleren</a>
            <a href="javascript:void(0)" class="waves-effect waves-grey btn-flat" onclick="addGroupBtn_Click()">Toevoegen</a>
        </div>
    </div>
    <div id="editGroupModal" class="modal">
        <div class="modal-content">
            <h4>Groep bijwerken</h4>
            <p>
            <div class="row">
                <form class="col s12" autocomplete="off">
                    <div class="row">
                        <div class="input-field col s6">
                            <input id="editLanguageName" type="text" class="validate">
                            <label for="editLanguageName">Groep</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="editLanguageDeveloper" type="text" class="validate">
                            <label for="editLanguageDeveloper">Ontwikkelaar</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <div class="chips" id="editLanguageTags"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="editLanguageIcon" type="text" class="validate autocomplete">
                            <label for="editLanguageIcon">Groep Logo</label>
                        </div>
                    </div>

                    <i class="devicon-devicon-plain devicon-preview" id="editDeviconPreviewer"></i>
                    <script>
                        var icons = {};
                        var iconsJson = {};
                        var tagSuggestions = {
                            <?php
                            $sqlInner = "SELECT * FROM tags WHERE 1;";

                            if (!$resultInner = $con->query($sqlInner)) {
                                die('Error while attempting to execute this query (' . $con->error . ') (' . $sql . ')');
                            }

                            while($rowInner = $resultInner->fetch_assoc()) { ?>
                            '<?php echo $rowInner["tagName"]; ?>': null,
                            <?php } ?>
                        };

                        $(document).ready(function () {
                            
                            $('.chips').chips({
                                placeholder: 'Voeg tag toe...',
                                secondaryPlaceholder: '+Tag',
                                autocompleteOptions: {
                                    data: tagSuggestions,
                                    limit: Infinity,
                                    minLength: 1
                                }
                            });

                            $.getJSON("https://konpa.github.io/devicon/devicon.git/devicon.json", function (data) {
                                $.each(data, function () {
                                    icons[this.name] = null;
                                });
                                iconsJson = data;
                            });

                            $('#editLanguageIcon').autocomplete({
                                data: icons,
                            });

                            $("#editLanguageIcon").change(function () {
                                console.log("devicon");
                                console.log($("#languageIcon").val());
                                console.log(iconsJson.find(x => x.name === $("#editLanguageIcon").val()).versions.font[0]);

                                var iconClass = "devicon" + "-" + $("#editLanguageIcon").val() + "-" + iconsJson.find(x => x.name === $("#editLanguageIcon").val()).versions.font[0];
                                console.log(iconClass);

                                $("#editDeviconPreviewer").removeAttr('class');
                                $("#editDeviconPreviewer").attr('class', '');
                                $("#editDeviconPreviewer").addClass("devicon-preview");
                                $("#editDeviconPreviewer").addClass(iconClass);
                            });
                        });
                    </script>
                </form>
            </div>
            </p>
        </div>
        <div class="modal-footer">
            <a href="javascript:void(0)" class="modal-close waves-effect waves-grey btn-flat">Annuleren</a>
            <a href="javascript:void(0)" class="waves-effect waves-grey btn-flat red-text" onclick="destroyGroupBtn_Click()">Verwijder</a>
            <a href="javascript:void(0)" class="waves-effect waves-grey btn-flat" onclick="updateGroupBtn_Click()">Bijwerken</a>
        </div>
    </div>
<?php } ?>
<!-- Modals -->

<!-- Define our own scripts -->
<script src="./js/index.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha256/0.9.0/sha256.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/mixitup/2.1.11/jquery.mixitup.min.js'></script>
<script>
    var cardCount = <?php echo (isset($_SESSION['email']) ? $cardCount +1 : $cardCount); ?>;
</script>
</body>

</html>