<?php 

$server = "198.71.227.89:3306";
$user = "scriptjet";
$password = "12Trustno1";
$database = "quenchInventory";

$conn = mysqli_connect($server, $user, $password, $database);

if (!$conn) {
    die("connection failed: " . mysqli_connect_error());
}

?>