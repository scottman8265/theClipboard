<?php
session_start();
    include("../cfg/connect.php");
    include("../inc/getFunctions.php");

    $db = new DB();

    $selectItem = $conn->prepare("SELECT itemNum FROM quenchInventory.itemTbl where active = 'y' order by itemName");
    $selectItem->execute();
    $selectItem->store_result();
    $selectItem->bind_result($currItem);

    echo "<label for='itemSel'>Select Item</label>
    <select id='itemSel' name='itemNum' class='select ui-corner-all ui-btn'>";
    while ($selectItem->fetch()) {
        $itemName = $db->getItemName($currItem);
        echo "<option value='" . $currItem .
            "'>" . $itemName . "</option>";
    }
    echo "</select>";