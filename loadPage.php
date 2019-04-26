<?php
include 'vars.php';
session_start();

if (isset($_GET["lang"]) && isset($_GET["id"])) 
{
    $id = $_GET["id"];
    $langId = $_GET["lang"];

    $sqlInner = "SELECT * FROM wikipage INNER JOIN wikipagedetails ON wikiPage.wikiPageId = wikipagedetails.wikiPageId WHERE languageId=$langId AND wikipagedetails.wikiPageId=$id";

    if (!$resultInner = $con->query($sqlInner)) {
        @die();
    }

    $pageCount = 0;
    while($rowInner = $resultInner->fetch_assoc()) 
    { 
        if (isset($_SESSION['email']) && isset($_GET["markdown"]))
            die($rowInner['pageContent']);

        $Parsedown = new Parsedown();
        die($Parsedown->text($rowInner['pageContent']));
    }
}