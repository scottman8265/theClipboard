<?php
    include("../inc/getFunctions.php");
    include("../cfg/connect.php");
session_start();

    $db = new DB();
    $currDate = $_SESSION['currDate'];
    $oldDate = $_SESSION['oldDate'];
    $counts = 0;
//    $fileName = "/report/inventory" . $currDate . ".csv";
    $categoryBreak = "";
?>

<html>
<head>
    <link href="stylesheet2.css" rel="stylesheet" type="text/css"/>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
</head>
<body>
<h1>Quench Lounge Inventory for week Ending <?php echo $currDate; ?>
    <table class="working">
        <tr>
            <th>Item Name</th>
            <th>Item Number</th>
            <th>Beg. QTY</th>
            <th>QTY Used</th>
            <th>On Hand</th>
        </tr>

        <?php
//            $file = fopen($fileName, "w");

            $getItems = $conn->prepare("select a.itemName, a.flavor, a.itemNum, b.categoryName, a.active, a.itemCost from quenchInventory.itemTbl as a join quenchInventory.categoryTbl as b on a.category = b.categoryID left join quenchInventory.familyTbl as c on b.family = c.familyName order by c.familyPriority, b.categoryPriority, a.itemName, a.flavor");
            $getItems->execute();
            $getItems->store_result();
            $getItems->bind_result($itemName, $flavor, $currItem, $currCategory, $active, $itemCost);
            while ($getItems->fetch()) {
                if ($active == 'y') {
                    $breakCount = 1;

                    $itemCount = 0;
                    $processTime = microtime(true);
                    $itemName = $itemName . "  " .$flavor;
                    $currCount = (float)$db->getCount($currDate, $currItem);
                    $priorCount = (float)$db->getCount($oldDate, $currItem);
                    $received = (float)$db->getReceived($currDate, $currItem);
                    $preCount = $priorCount + $received;
                    $itemUsed = (($preCount * 10) - ($currCount * 10)) / 10;
                    $itemCount = $currCount;

                    if ($categoryBreak != $currCategory) {
                        $categoryBreak = $currCategory;
                        print "<th class='category'>" . $categoryBreak . "</th>";
                    }

                    if ($breakCount != 2) {

                        print "<tr>";
                        $breakCount++;
                    } else {

                        print "<tr class='break'>";
                        $breakCount = 1;
                    }

                    echo "<td style='text-align:left'>" . $itemName . "</td><td>" . $currItem .
                        "</td><td>" . $preCount . "</td><td>" . $itemUsed . "</td><td>" . $currCount . "</td></tr>";

                    #$lines = $itemName . ", " . $currItem . ", " . $itemUsed . ", " . $currCount .
                     # ", \n";
                     #   fwrite($file, $lines);
                    $counts++;
                    $processTime = (microtime(true) - $processTime) * 1000;
                }
            }
            #fclose($file);
            echo "</br>It took " . $processTime . " milliseconds to process " . $counts . " items!";

        ?>
</h1>
</body>
</html>