<?php 
include 'vars.php';
session_start();

//Check if we are logged on
if (!isset($_SESSION['email']))
    die(); // If not just die...

//Let's see if we got post data
if (isset($_POST['langId']))
{
    $langId = $_POST["langId"];
    $sql = "DELETE FROM language WHERE languageId = $langId;
            DELETE FROM wikipage WHERE languageId = $langId;";

    if (!$result = $con->multi_query($sql)) {
        die('{ "status": "' . $con->error . '", "sql": "' . $sql . '" }');
    }

    do 
    {
        if ($result = mysqli_store_result($con)) 
        {
            while ($row = mysqli_fetch_array($result)) 
            { }
            mysqli_free_result($result);
        }      
    } 
    while (mysqli_next_result($con));

    die('{ "status": "ok" }');
}