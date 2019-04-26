<?php
include 'vars.php';
session_start();

if (isset($_SESSION['email']))
    if (isset($_POST["id"]) && isset($_POST["page"])) 
    {
        $id = $_POST["id"];
        $page = $con->real_escape_string($_POST["page"]);

        $sqlInner = "UPDATE `wikipagedetails` SET `pageContent` = '$page' WHERE `wikipagedetails`.`wikiPageId` = $id;";

        if (!$resultInner = $con->query($sqlInner)) {
            die('Error while attempting to execute this query (' . $con->error . ') (' . $sqlInner . ')');
        }

        die(" { \"status\": \"ok\" } ");
    }