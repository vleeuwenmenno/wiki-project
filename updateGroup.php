<?php 
include 'vars.php';
session_start();

//Check if we are logged on
if (!isset($_SESSION['email']))
    die(); // If not just die...

//Let's see if we got post data
if (isset($_POST['languageId']) && isset($_POST['languageName']) && isset($_POST['languageIcon']) && isset($_POST['languageDeveloper']) && isset($_POST['languageTags']))
{
    //Else let's get on with updating the group.
    $langId = $_POST['languageId'];
    $langName = $_POST['languageName'];
    $langIcon = $_POST['languageIcon'];
    $langDev = $_POST['languageDeveloper'];
    $langTags = $_POST['languageTags'];

    //Let's first update the language data
    $sql = "UPDATE `language` SET `languageName` = '$langName', `languageIcon` = '$langIcon', `languageDeveloper` = '$langDev' WHERE `language`.`languageId` = $langId;";

    if (!$result = $con->multi_query($sql)) {
        die('{ "status": "' . $con->error . '", "sql": "' . $sql . '" }');
    }

    //Let's connect the tags to the group
    //Firstly let's unhook all old connections
    $sql = "DELETE FROM languagetags WHERE languageId = $langId;";
    if (!$result = $con->query($sql)) {
        die('{ "status": "' . $con->error . '", "sql": "' . $sql . '" }');
    }

    //Let's check if tags already exist if not add them to the tags database
    $sql = "";

    foreach ($langTags as $value)
    {
        $sql .= "INSERT INTO `tags` (`tagName`) SELECT '$value' FROM tags WHERE NOT EXISTS (SELECT tagId FROM `tags` WHERE `tagName`='$value') LIMIT 1;\nSELECT * FROM tags WHERE `tagName`='$value';\n";
    }

    if (!$result = $con->multi_query($sql)) {
        die('{ "status": "' . $con->error . '", "sql": "' . $sql . '" }');
    }

    $tagIds = array();
    
    //Secondly let's get the ID's of the just inserted tags
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
        $sql .= "INSERT INTO `languagetags` (`tagId`, `languageId`) VALUES ('$value', '$langId');\n";
    }

    if (!$result = $con->multi_query($sql)) {
        die('{ "status": "' . $con->error . '", "sql": "' . $sql . '" }');
    }

    die('{ "status": "ok", "langId": "' . $langId .'" }');
}
else
    die('{ "status": "invalid_post_data" }');