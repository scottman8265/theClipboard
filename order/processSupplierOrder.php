<?php
    session_start();
    include("../cfg/connect.php");
    $test = [];
    $test2 = [];
    $order = [];

    foreach ($_POST as $key => $items) {
        preg_match('/^[a-z]*/i', $key, $test);
        preg_match('/(\d+)$/', $key, $test2);
        $order[$test2[0]][$test[0]] = $items;
    }

    foreach ($order as $itemNum => $params) {
        if ($params['order']) {
            $insert = $conn->prepare("INSERT INTO quenchInventory.orderTbl (orderDate, itemNum, orderQty, pack) VALUES (?, ?, ?, ?)");
            $insert->bind_param("ssss", $_SESSION['currDate'], $itemNum, $params['orderQty'], $params['pack']);
            $insert->execute();
        }

    }
