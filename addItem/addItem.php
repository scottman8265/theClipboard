<?php
    session_start();
    include("../cfg/connect.php");
    $itemNum = $itemName = $flavor = $category = $supplier = $pack = $size = "";
    $data['dataInsert'] = false;

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $itemNum = test_input($_POST['itemNum']);
        $itemName = test_input($_POST['itemName']);
        $flavor = test_input($_POST['flavor']);
        $category = test_input($_POST['category']);
        $supplier = test_input($_POST['supplier']);
        $pack = test_input($_POST['pack']);
        $size = test_input($_POST['size']);
    }

    $query = "INSERT INTO itemTbl (itemNum, itemName, flavor, category, supplier, pack, size, active) VALUES (" . $itemNum . ", '" . $itemName . "', '" . $flavor . "', '" . $category . "', '" . $supplier . "', " . $pack . ", " . $size . ", 'y')";
    $insert = mysqli_query($conn, $query);

    if (!$insert) {



    }

    if ($insert) {
        $data['dataInsert'] = true;

    }

    echo json_encode($data);

?>





