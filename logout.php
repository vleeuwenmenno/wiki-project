<?php
session_start();
session_destroy();

if (isset($_GET["r"]))
    header("Location: " . $_GET['r']);

die('{ "logout": "ok" }');
