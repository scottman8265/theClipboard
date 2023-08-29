<?php
    /**
     * Created by PhpStorm.
     * User: scott
     * Date: 5/9/16
     * Time: 5:43 PM
     */
    include("../inc/connect.php");

    function getCurrentCount($conn, $currDate, $currItem) {
        $sql = "select sum(countCol) as currCount, itemNum, dateCol from countTbl where dateCol = " . $currDate . " and itemNum = " . $currItem . " group by itemNum";
        $res = mysqli_query($conn, $sql);

        foreach ($res as $count) {
            return $count['currCount'];
        }

    }

    function getPriorCount($conn, $oldDate, $currItem) {
        $sql = "select sum(countCol) as priorCount, itemNum, dateCol from countTbl where dateCol = " . $oldDate . " and itemNum = " . $currItem . " group by itemNum";
        $res = mysqli_query($conn, $sql);

        foreach ($res as $count) {
            return $count['priorCount'];
        }

    }

    function getReceived($conn, $currDate, $currItem) {
        $sql = "select sum(qtyReceived) as qtyReceived, itemNumber, dateCol from receiving where dateCol = " . $currDate . " and itemNumber = " . $currItem . " group by itemNumber";
        $res = mysqli_query($conn, $sql);

        foreach ($res as $count) {
            return $count['qtyReceived'];
        }

    }

    $sql = "select itemName, itemNum, flavor, active from items";
    $res = mysqli_query($conn, $sql);
    $deleteCount = 0;
    $keepCount = 0;

    foreach ($res as $active) {
        if ($active['active'] == 'y') {
            $keepCount++;
        } else {
            $deleteCount++;
        }
    }
    $totalCount = $keepCount + $deleteCount;

    echo "active items:  " .$keepCount ."  inactive items:  " .$deleteCount ."  Total items:  " .$totalCount;






?>