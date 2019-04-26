<?php
    include_once("Parsedown.php");
    $_CFG = array(
        "siteName" => "Wiki Project",
        "siteDomain" => "localhost",
        "allowRegister" => true,   //Setting this to true allows public registration of new users
        "requireConfirm" => false, //Setting this to true requires the newly made user to confirm their account by email.
        
        "sqlHost" => "127.0.0.1",
        "sqlUser" => "root",
        "sqlPass" => "",
        "sqlDb" => "wiki-project",

        //The following only needs to be setup for using when requireConfirm is true
        "smtpHost" => "smtp.gmail.com",
        "smtpPort" => "587",
        "smtpSecure" => "STARTTLS",
        "smtpUser" => "",
        "smtpPass" => "",
    );

    function genStr($length = 10) 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    $con = new mysqli($_CFG["sqlHost"], $_CFG["sqlUser"], $_CFG["sqlPass"], $_CFG["sqlDb"]);

    if($con == false)
        echo "Failed to establish connection to MySQL Server.";

?>
