<?php
include("../inc/phpFunctions/getFunctions.php");
include("connect.php");

session_start();
    $getStuff = new DB();
    

/*Set Initial Globals From Index (must be ran immediately after index page)*/
if (!empty($_POST) and isset($_POST['initializeCount']) and $_POST['initializeCount']) {
    $_SESSION['currDate'] = $_POST['currDate'];
    $_SESSION['categoryID'] = $_POST['categoryID'];
    $_SESSION['locationID'] = $_POST['locationID'];
    $_SESSION['oldDate'] = $getStuff->getOldDate($_SESSION['currDate']);
    $_SESSION['category'] = $getStuff->getCategory($_SESSION['categoryID']);
    $_SESSION['location'] = $getStuff->getLocation($_SESSION['locationID']);
    $_POST['initializeCount'] = false;
}

if (!empty($_POST) and isset($_POST['changeCategory']) and $_POST['changeCategory']) {
    $_SESSION['category'] = $_POST['category'];
    $_SESSION['categoryID'] = $_POST['categoryID'];
    $_POST['changeCategory'] = false;    
}

if (!empty($_POST) and isset($_POST['changeLocation']) and $_POST['changeLocation']) {
    $_SESSION['location'] = $_POST['location'];
    $_SESSION['locationID'] = $_POST['locationID'];
    $_POST['changeLocation'] = false;
}

echo json_encode($_SESSION);

?>