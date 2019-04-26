<?php 
include 'vars.php';
session_start();

//Check if we are logged on
if (!isset($_SESSION['email']))
    die(); // If not just die...


//Let's see if we got post data
if (isset($_POST['id']))
{
    //Else let's get on with updating the group.
    $id = $_POST['id'];


    $sql = "DELETE FROM wikipage WHERE wikiPageId='$id';
    DELETE FROM wikipagedetails WHERE wikiPageId='$id';
    DELETE FROM wikipagetags WHERE wikiPageId='$id';";

    if (!$result = $con->multi_query($sql)) {
        die('{ "status": "' . $con->error . '", "sql": "' . $sql . '" }');
    }

    $langId = 0;
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
else
    die('{ "status": "invalid_post_data" }');