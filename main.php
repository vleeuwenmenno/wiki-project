<?php
include 'vars.php';
session_start();

if (isset($_GET["lang"])) {
    $sql = "SELECT * FROM language WHERE languageId=" . $_GET["lang"] . ";";

    if (!$result = $con->query($sql)) {
        die('Error while attempting to execute this query (' . $con->error . ') (' . $sql . ')');
    }

    $i = 0;
    while ($row = @$result->fetch_assoc()) {
        $i++;
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
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.6.4/jquery.contextMenu.css">
            
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
            <!-- <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css"> -->
            <link rel="stylesheet" href="./css/vs2015.css">

            <link rel="stylesheet" href="./css/main.css" id="rel">

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
                    <a href="index.php">
                        <i class="<?php echo $row["languageIcon"]; ?> user-icon"></i>
                    </a>
                    <a href="#name">
                        <span class="white-text name"><?php echo $row["languageName"]; ?></span>
                    </a>
                    <a href="#email">
                        <span class="white-text email">
                            <b>Developer:</b> <?php echo $row["languageDeveloper"]; ?></span>
                    </a>
                </div>
            </li>
            <ul id="page-buttons">
                <?php
                $sqlInner = "SELECT * FROM wikipage INNER JOIN wikipagedetails ON wikiPage.wikiPageId = wikipagedetails.wikiPageId WHERE wikiPage.languageId = " . $_GET["lang"] . " ORDER BY wikipage.pageIndex;";

                if (!$resultInner = $con->query($sqlInner)) {
                    die('Error while attempting to execute this query (' . $con->error . ') (' . $sql . ')');
                }

                $pageCount = 0;
                while($rowInner = $resultInner->fetch_assoc()) 
                { 
                    $sqlInnerInner = "SELECT * FROM wikipagetags INNER JOIN tags ON wikipagetags.tagId = tags.tagId WHERE wikiPageId=" . $rowInner['wikiPageId'] . ";";

                    if (!$resultInnerInner = $con->query($sqlInnerInner)) {
                        die('Error while attempting to execute this query (' . $con->error . ') (' . $sqlInner . ')');
                    }

                    $tags = array();

                    while ($rowInnerInner = $resultInnerInner->fetch_assoc()) {
                        array_push($tags, $rowInnerInner["tagName"]);
                    }

                    if ($pageCount == 0)
                    { ?> <li id="<?php echo $rowInner['wikiPageId']; ?>" data-pagetags="<?php echo htmlspecialchars(json_encode($tags), ENT_QUOTES, 'UTF-8'); ?>" data-name="<?php echo $rowInner['pageTitle']; ?>" class="pageNavButton active"><a class="" href="javascript:void(0)"><?php echo $rowInner['pageTitle']; ?></a></li><?php }
                    else
                    { ?> <li id="<?php echo $rowInner['wikiPageId']; ?>" data-pagetags="<?php echo htmlspecialchars(json_encode($tags), ENT_QUOTES, 'UTF-8'); ?>" data-name="<?php echo $rowInner['pageTitle']; ?>" class="pageNavButton"><a class="" href="javascript:void(0)"><?php echo $rowInner['pageTitle']; ?></a></li><?php }
                    
                    $pageCount++;
                }

                if ($pageCount == 0) {
                    ?>
                    <li><a class="subheader">Geen pagina's gevonden :(</a></li><?php
                }
                ?>
            </ul>
            <?php if (isset($_SESSION['email'])) { ?>
            <li>
                <div class="divider"></div>
            </li>
            <li>
                <a class="subheader">Editor opties</a>
            </li>
            <li>
                <a href="#addPageModal" class="waves-effect modal-trigger"><i class="material-icons">add</i>Nieuwe pagina aanmaken</a>
            </li>
            <li>
                <a class="waves-effect" href="javascript:void(0)" id="changeToolbarOrderBtn" onclick="changeToolbarOrderBtn_Click()"><i class="material-icons">edit</i>Toolbar volgorde aanpassen</a>
            </li>
            <?php } ?>
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
                <a href="index.php" id="backBtn" class="left backButton"><i class="material-icons">arrow_back</i></a>
                <?php if (isset($_SESSION['email'])) { ?>
                <a href="#" class="left backButton" id="savePageBtn" hidden><i class="fas fa-save"></i></a>
                <a href="#" class="left backButton" id="closePageEditBtn" hidden><i class="fas fa-times-circle"></i></a>
                <?php } ?>
                <a href="#" class="left backButton" id="pageTitleLabel"></a>
                
                <ul class="right hide-on-med-and-down">
                    <li>
                        <div class="input-field">
                            <input id="navBarSearch" type="search" required>
                            <label class="label-icon" for="search">
                                <i class="material-icons searchIcon">search</i>
                            </label>
                            <i class="material-icons">close</i>
                        </div>
                        <script>
                            $(document).ready(function () {
                                $('#navBarSearch').autocomplete({
                                    data: {
                                        <?php include("search.php"); ?>
                                    },
                                    limit: 20,
                                    onAutocomplete: function(data) {
                                        window.location = "searchPage.php?search=" + encodeURIComponent($("#navBarSearch").val());
                                    },
                                    minLength: 1, // The minimum length of the input for the autocomplete to start. Default: 1.
                                    scrollLength: 2,
                                });

                                $("#navBarSearch").keyup(function (e) {
                                    if ((e.keyCode === 13)) 
                                    {
                                        window.location = "searchPage.php?search=" + encodeURIComponent($("#navBarSearch").val());
                                    }
                                });
                            });
                        </script>
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
            <div class="row">
                <div class="col s12 m8 offset-m1 xl8 offset-xl1 editor-panel">
                    <?php if (isset($_SESSION['email'])) { ?> <textarea id="mde" style="display: none;"></textarea><?php } ?>
                    <div id="pageContents">
                        <div class="center-loader preloader-wrapper big active">
                            <div class="spinner-layer spinner-blue-only">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div><div class="gap-patch">
                                <div class="circle"></div>
                            </div><div class="circle-clipper right">
                                <div class="circle"></div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- page -->

        <!-- Modals -->
        <?php if (isset($_SESSION['email'])) { ?>
            <div id="addPageModal" class="modal">
                <div class="modal-content">
                    <h4>Pagina aanmaken</h4>
                    <p>
                        <div class="row">
                            <form class="col s12">
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="pageName" type="text" class="validate">
                                        <label for="pageName">Pagina naam</label>
                                    </div>
                                    <div class="input-field col s6">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                    <div class="chips" id="addPageTags"></div>
                                </div>
                                <script>
                                    var tagSuggestions = {
                                            <?php 
                                                $sqlInner = "SELECT tagName FROM `tags` WHERE 1";

                                                if (!$resultInner = $con->query($sqlInner)) {
                                                    die('Error while attempting to execute this query (' . $con->error . ') (' . $sql . ')');
                                                }

                                                while($rowInner = $resultInner->fetch_assoc()) { ?>
                                                '<?php echo $rowInner["tagName"]; ?>': null,
                                            <?php } ?>
                                        };

                                    function populateAddPageTags()
                                    {
                                        $('#addPageTags').chips({
                                            placeholder: 'Voeg tag toe...',
                                            secondaryPlaceholder: '+Tag',
                                            autocompleteOptions: {
                                                data: tagSuggestions,
                                                limit: Infinity,
                                                minLength: 1,
                                            },
                                        })
                                    }

                                    $(document).ready(function () {
                                        populateAddPageTags();
                                    });
                                </script>
                            </form>
                        </div>
                    </p>
                </div>
                    <div class="modal-footer">
                        <a href="javascript:void(0)" class="modal-close waves-effect waves-grey btn-flat">Annuleren</a>
                        <a href="#" onclick="addPageBtn_Click()" class="waves-effect waves-grey btn-flat">Aanmaken</a>
                    </div>
                </div>
            </div>

            <div id="editPageModal" class="modal">
                <div class="modal-content">
                    <h4>Pagina eigenschappen</h4>
                    <p>
                    <div class="row">
                        <form class="col s12">
                            <div class="row">
                                <div class="input-field col s6">
                                    <input id="editPageName" type="text" class="validate" disabled>
                                    <label for="editPageName">Pagina naam</label>
                                </div>
                                <div class="input-field col s6">
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                <div class="chips" id="editPageTags" disabled></div>
                            </div>
                            <script>
                                function populateEditPageTags()
                                {
                                    $('#editPageTags').chips({
                                        placeholder: 'Voeg tag toe...',
                                        secondaryPlaceholder: '+Tag',
                                        autocompleteOptions: {
                                            data: tagSuggestions,
                                            limit: Infinity,
                                            minLength: 1,
                                        },
                                    })
                                }

                                $(document).ready(function () {
                                    populateEditPageTags();
                                });
                            </script>
                        </form>
                    </div>
                    </p>
                </div>
                <div class="modal-footer">
                    <a href="javascript:void(0)" class="modal-close waves-effect waves-grey btn-flat">Sluiten</a>
                </div>
            </div>
        <?php } ?>
        <!-- Modals -->

        <!-- Compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sortable/0.9.13/jquery-sortable-min.js"></script>
        <?php if (isset($_SESSION['email'])) { ?><script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script><?php } ?>
        <script src="./js/main.js"></script>
        <?php if (isset($_SESSION['email'])) { ?><script src="./js/mainlogin.js"></script><?php } ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.6.4/jquery.contextMenu.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
        <script>hljs.initHighlightingOnLoad();</script>
    </body>
</html>

        <?php
    }
} else $i = 0;

if ($i < 1) die("Invalid id.");