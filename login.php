<?php
include("vars.php");

$userName = strtolower($_GET['userName']);
$passwordHash = $_GET['password'];

$sql = "SELECT password, salt, activateSecret FROM `members` WHERE username='$userName' OR email='$userName'";

if(!$result = $con->query($sql))
{
    die('Error while attempting to execute this query (' . $con->error . ') (' . $sql . ')');
}

$return = array();
$i = 0;

while($row = $result->fetch_assoc())
{
    $i++;
    $salt = $row['salt'];
    if (hash('sha256', $passwordHash . $salt) == $row['password'])
    {
        if ($row['activateSecret'] == "")
        {
            session_start();

            $sql = "SELECT * FROM members WHERE username='$userName' OR email='$userName' limit 1";

            $results = $con->query($sql);
            while($rows = $results->fetch_assoc())
            {
                $_SESSION['email'] = $rows['email'];
                $_SESSION['userName'] = $userName;
                $_SESSION['userId'] = $rows['id'];
                $_SESSION['userType'] = $rows['userType'];
                $_SESSION['debugMode'] = true;
            }

            if (isset($_GET['r']))
                header("Location: " . $_GET['r']);
                
            die('{ "login": "ok", "sql": "'.$sql.'" }');
        }
        else
            die('{ "login": "ok_not_activated" }');
    }
}

echo '{ "login": "fail" }';