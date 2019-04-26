<?php 
include 'vars.php';
session_start();

//Check if we are logged on
if (!isset($_SESSION['email']))
    die(); // If not just die...

//Let's check if we are not the user we are deleting
if ($_SESSION['userId'] == $_POST['id'])
    die('{ "status": "cannot_delete_active_user" }');

//If user is supreme leader
if ($_SESSION["userType"] != "Supreme Leader")
    die('{ "status": "403" }');

//Let's see if we got post data
if (isset($_POST['id']))
{
    //Else let's get on with updating the group.
    $id = $_POST['id'];


    $sql = "DELETE FROM members WHERE id=$id";

    if (!$result = $con->multi_query($sql)) {
        die('{ "status": "' . $con->error . '", "sql": "' . $sql . '" }');
    }

    die('{ "status": "ok" }');
}
else
    die('{ "status": "invalid_post_data" }');