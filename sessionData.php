<?php
session_start();
include_once "vars.php";

if ($_GET['data'] == "userEmail")
    echo urldecode($_SESSION['email']);

if ($_GET['data'] == "userTotalScore")
    echo urldecode(studentScore($_SESSION['userId']));

if ($_GET['data'] == "userFinishedExercises")
    echo urldecode(completedObj($_SESSION['userId']));

if ($_GET['data'] == "userAverageScore")
    echo urldecode(studentAvgScore($_SESSION['userId']));
