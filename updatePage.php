<?php 
include 'vars.php';
session_start();

//Check if we are logged on
if (!isset($_SESSION['email']))
    die(); // If not just die...

//Let's see if we got post data
if (isset($_POST['wikiPageId']) && isset($_POST['pageTitle']))
{
    $id = $_POST['wikiPageId'];
    $title = $_POST['pageTitle'];

    //Let's check if tags already exist if not add them to the tags database
    $sql = "UPDATE `wikipagedetails` SET `pageTitle` = '$title' WHERE `wikipagedetails`.`wikiPageId` = $id;";

    if (!$result = $con->multi_query($sql)) {
        die('{ "status": "' . $con->error . '", "sql": "' . $sql . '" }');
    }

    die('{ "status": "ok" }');
}
else
    die('{ "status": "invalid_post_data" }');