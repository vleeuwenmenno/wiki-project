<?php

$sql = 
"SELECT tagName AS name FROM tags WHERE 1;
SELECT pageTitle AS name FROM wikipagedetails WHERE 1;
SELECT languageName AS name FROM `language` WHERE 1;";

if (!$result = $con->multi_query($sql)) {
    die('{ "status": "' . $con->error . '", "sql": "' . $sql . '" }');
}

$langId = 0;
do 
{
    if ($result = mysqli_store_result($con)) 
    {
        while ($row = mysqli_fetch_array($result)) 
        {
            echo "'" . $row['name'] . "': null,";
        }
        mysqli_free_result($result);
    }      
} 
while (mysqli_next_result($con));