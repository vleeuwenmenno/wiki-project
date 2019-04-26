<?php
include('vars.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './phpMailer/Exception.php';
require './phpMailer/PHPMailer.php';
require './phpMailer/SMTP.php';

if (isset($_GET['email']) && isset($_GET['userName']) && isset($_GET['password']))
{
    $salt = uniqid(mt_rand(), true);
    $email = urldecode($_GET['email']);
    $userName = strtolower($_GET['userName']);
    $passwordHash = hash('sha256', $_GET['password'] . $salt);
    $activateSecret = genStr(196);

    $sql = "SELECT * FROM `members` WHERE username = '$userName' OR email = '$email'";

    if(!$result = $con->query($sql))
        die('Error while attempting to execute this query (' . $con->error . ') (' . $sql . ')');

    while($row = $result->fetch_assoc())
        die('{ "status": "username_email_used" }');

    if ($_CFG['requireConfirm'])
    {
        $sql = "INSERT INTO `members` (`id`, `email`, `username`, `password`, `salt`, `activateSecret`, `recoverySecret`, `mod_timestamp`) VALUES (NULL, '$email', '$userName', '$passwordHash', '$salt', '$activateSecret', '', CURRENT_TIMESTAMP)";
        $mail = new PHPMailer(true);   

        try 
        {
            //Server settings
            //$mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = $_CFG["smtpHost"];
            $mail->SMTPAuth = true;
            $mail->Username = $_CFG["smtpUser"];
            $mail->Password = $_CFG["smtpPass"];
            $mail->SMTPSecure = $_CFG["smtpSecure"];
            $mail->Port = $_CFG["smtpPort"];

            //Recipients
            $mail->setFrom($_CFG["smtpUser"], $_CFG["siteName"] .' Mailer');
            $mail->addAddress($email, $userName);

            //Content
            $mail->isHTML(true);
            $mail->Subject =    $_CFG["siteName"] . ' - Account activation';
            $mail->Body    = 'Please activate your account by following this link <a href="https://' . $_CFG["siteName"] . '/activate.php?s=' . $activateSecret . '">link</a>';
            $mail->AltBody = 'Please activate your account by following this link: https://' . $_CFG["siteName"] . '/activate.php?s=' . $activateSecret;

            $mail->send();

            if(!$resultTwo = $con->query($sql))
                echo '{ "status": "error", "cause": "' . urlencode($con->error) . '" }';
            else
            {
                echo '{ "status": "ok" }';
            }
        } 
        catch (Exception $e) 
        {
            echo '{ "status": "error-mailing", "cause": "' . urlencode($e) . '" }';
        }
    }
    else
    {
        $sql = "INSERT INTO `members` (`id`, `email`, `username`, `password`, `salt`, `activateSecret`, `recoverySecret`, `mod_timestamp`) VALUES (NULL, '$email', '$userName', '$passwordHash', '$salt', '', '', CURRENT_TIMESTAMP)";
        
        if(!$resultTwo = $con->query($sql))
            echo '{ "status": "error", "cause": "' . urlencode($con->error) . '" }';
        else
        {
            echo '{ "status": "ok" }';
        }
    }
}