<?php 
include 'vars.php';
session_start();

//Check if we are logged on
if (!isset($_SESSION['email']))
    die(); // If not just die...

//Let's see if we got post data
if (isset($_POST['pageTitle']) && isset($_POST['pageTags']) && isset($_POST['languageId']))
{
    //Else let's get on with adding the group.
    $pageTitle = $_POST['pageTitle'];
    $pageTags = $_POST['pageTags'];
    $pageOwner = $_SESSION['userId'];
    $languageId = $_POST["languageId"];

    $sql = "SELECT max(pageIndex) FROM wikipage WHERE languageId=$languageId";

    if (!$result = $con->multi_query($sql)) {
        die('{ "status": "' . $con->error . '", "sql": "' . $sql . '" }');
    }

    $pageIndex = 0;
    do 
    {
        if ($result = mysqli_store_result($con)) 
        {
            while ($row = mysqli_fetch_array($result)) 
            {
                $pageIndex = $row["max(pageIndex)"];
            }
            mysqli_free_result($result);
        }      
    } 
    while (mysqli_next_result($con));

    $pageIndex++;

    //Let's first add the language data
    $sql = "INSERT INTO `wikipage` (`languageId`, `pageIndex`) VALUES ('$languageId', '$pageIndex');\nSELECT LAST_INSERT_ID();";

    if (!$result = $con->multi_query($sql)) {
        die('{ "status": "' . $con->error . '", "sql": "' . $sql . '" }');
    }

    $pageId = 0;
    do 
    {
        if ($result = mysqli_store_result($con)) 
        {
            while ($row = mysqli_fetch_array($result)) 
            {
                $pageId = $row["LAST_INSERT_ID()"];
            }
            mysqli_free_result($result);
        }      
    } 
    while (mysqli_next_result($con));

    //Let's first add the language data
    $sql = "INSERT INTO `wikipagedetails` (`wikiPageId`, `pageOwner`, `pageTitle`) VALUES ('$pageId', '$pageOwner', '$pageTitle');\nSELECT LAST_INSERT_ID();";

    if (!$result = $con->multi_query($sql)) {
        die('{ "status": "' . $con->error . '", "sql": "' . $sql . '" }');
    }

    $pageId = 0;
    do 
    {
        if ($result = mysqli_store_result($con)) 
        {
            while ($row = mysqli_fetch_array($result)) 
            {
                $pageId = $row["LAST_INSERT_ID()"];
            }
            mysqli_free_result($result);
        }      
    } 
    while (mysqli_next_result($con));

    //Let's check if tags already exist if not add them to the tags database
    $sql = "";

    foreach ($pageTags as $value)
    {
        $sql .= "INSERT INTO `tags` (`tagName`) SELECT '$value' FROM tags WHERE NOT EXISTS (SELECT tagId FROM `tags` WHERE `tagName`='$value') LIMIT 1;\nSELECT * FROM tags WHERE `tagName`='$value';\n";
    }

    if (!$result = $con->multi_query($sql)) {
        die('{ "status": "' . $con->error . '", "sql": "' . $sql . '" }');
    }

    $tagIds = array();
    //Let's connect the tags to the group
    //Firstly let's get the ID's of the just inserted tags
    do 
    {
        if ($result = mysqli_store_result($con)) 
        {
            while ($row = mysqli_fetch_array($result)) 
            {
                array_push($tagIds, $row["tagId"]);
            }
            mysqli_free_result($result);
        }      
    } 
    while (mysqli_next_result($con));
    
    //Now let's insert all of the id's into the languageTags table
    $sql = "";
    foreach ($tagIds as $value)
    {
        $sql .= "INSERT INTO `wikipagetags` (`tagId`, `wikiPageId`) VALUES ('$value', '$pageId');\n";
    }

    if (!$result = $con->multi_query($sql)) {
        die('{ "status": "' . $con->error . '", "sql": "' . $sql . '" }');
    }

    die('{ "status": "ok", "wikiPageId": "' . $pageId .'" }');
}
else
    die('{ "status": "invalid_post_data" }');