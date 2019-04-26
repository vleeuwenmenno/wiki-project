<?php 
include 'vars.php';
session_start();

//Check if we are logged on
if (!isset($_SESSION['email']))
    die(); // If not just die...

//Let's see if we got post data
if (isset($_POST['lang']) && isset($_POST['indexes']))
{
    //Else let's get on with adding the group.
    $languageId = $_POST['lang'];
    $indexes = $_POST['indexes'];
    $sql = "";

    foreach ($indexes as $key => $value)
    {
        $sql .= "UPDATE `wikipage` SET `pageIndex` = '$value' WHERE `wikipage`.`wikiPageId` = '$key';\n";
    }

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
else
    die('{ "status": "invalid_post_data" }');