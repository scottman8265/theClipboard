<?php

//    session_start();
//    include('../cfg/cfgGlobal.php');
    include('../cfg/connect.php');
    include('../inc/getFunctions.php');

    $db = new DB();

    $itemNum = $_POST['itemNum'];

    $lookup = $conn->prepare("SELECT supplier, pack, itemCost, category, size FROM quenchInventory.itemTbl WHERE itemNum = ?");
    $lookup->bind_param("s", $itemNum);
    $lookup->execute();
    $lookup->store_result();
    $lookup->bind_result($supplierID, $pack, $itemCost, $categoryID, $size);
    $lookup->fetch();

    $itemName = $db->getItemName($itemNum);
    $supplierName = $db->getSupplier($supplierID);
    $categoryName = $db->getCategory($categoryID);

    $itemArray = ['itemNum' => $itemNum, 'itemName' => $itemName, 'supplierID' => $supplierID, 'supplier' => $supplierName, 'pack' => $pack, 'itemCost' => $itemCost, 'categoryID' => $categoryID, 'category' => $categoryName, 'size' => $size];

    echo json_encode($itemArray);