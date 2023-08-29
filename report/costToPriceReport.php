<?php
    session_start();
    
    include ("../cfg/connect.php");
    include("../inc/getFunctions.php");
    
    $items = $conn->prepare("select")