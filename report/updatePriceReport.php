<?php
    session_start();
    include("../inc/getFunctions.php");
    include("../cfg/connect.php");

    $db = new DB();
    $conn->autocommit(false);
    $currDate = $_SESSION['currDate'];
    $oldDate = $_SESSION['oldDate'];
    $counts = 0;
    $breakCount = 1;
    $categoryBreak = "";
    $categoryBreakID = "";
    $costPerOz = "N/A";
    $costPerDrink = "N/F";


?>

<html>
<head>
    <title>Supply Order Guide</title>
    <link href="../report/stylesheet2.css" rel="stylesheet" type="text/css"/>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>

</head>
<body>

<div data-role="page" style="background-color:black;">
    <div data-role="header" data-position="fixed">

        <ul data-role="listview" data-split-theme="a" data-split-icon="back">
            <li>
                <a href="#"><h1 style="text-align: center;">Item Unit Cost % to Price</h1></a>
                <a href="#" data-rel="back">Go Back</a>
            </li>
        </ul>

    </div>
    <div data-role="main" class="ui-content">


        <div data-role="collapsibleset">
            <?php

                $items = $conn->prepare("
SELECT a.itemName, a.flavor, a.itemNum, a.category, c.categoryName, a.itemCost, b.ounces, a.sellPrice, a.figureBy, d.familyName
FROM quenchInventory.itemTbl AS a LEFT JOIN quenchInventory.bottleSizes AS b ON a.size = b.sizeID 
LEFT JOIN quenchInventory.categoryTbl AS c ON a.category = c.categoryID
LEFT JOIN quenchInventory.familyTbl AS d ON c.family = d.familyName
WHERE a.sellPrice != 0 AND a.active = 'y' 
ORDER BY d.familyPriority, c.categoryPriority, a.itemName, a.flavor");

                $items->execute();
                $items->store_result();
                $items->bind_result($itemName, $flavor, $currItem, $currCategoryID, $currCategory, $itemCost, $ounces, $sellPrice, $figureBy, $familyName);

                while ($items->fetch()) {
                $itemName = $itemName . "  " . $flavor;
                $costPerOunce = $itemCost / $ounces;

                if ($ounces != 1) {
                    $costPerOz = number_format((($costPerOunce * .1) + $costPerOunce + .1), 4, '.', '');
                    $costPerDrink = number_format(($costPerOz * 1.5), 2, '.', '');
                }

                if ($familyName == 'BEER') {
                    if ($currCategoryID == 'BB') {
                        $costPerOz = number_format($costPerOunce, 4, '.', '');
                        $costPerDrink = number_format(($costPerOz * $ounces), 2, '.', '');
                    }
                    if ($currCategoryID == 'D') {
                        $costPerOz = $costPerOz - .1;

                        if ($currItem == 441) {
                            $costPerDrink = number_format(($costPerOz * 16), 2, '.', '');
                        } else {
                            $costPerDrink = number_format(($costPerOz * 12), 2, '.', '');
                        }

                    }
                }

                if ($currCategoryID == 'W'){
                        $costPerDrink = $costPerOz * 5;
                    
                }



                $percentToPrice = number_format((($costPerDrink / ($sellPrice / 1.07)) * 100), 2, '.', '') ."%";
                $itemCost = "$" .$itemCost;
                $sellPrice = "$" .$sellPrice;
                $costPerDrink = "$" .$costPerDrink;
                $costPerOz = "$" .$costPerOz;


                //                ends old supplier -- must be 1st
                if ($categoryBreak != $currCategory and $counts != 0) {
                $oldCategory = $categoryBreak;
                $oldCategoryID = $categoryBreakID;

            ?>
            </table>  <!--do not delete... this is the correct end tag-->

        </div>
        <?php }
            if ($categoryBreak != $currCategory) {
            $categoryBreak = $currCategory;
            $categoryBreakID = $currCategoryID; ?>

        <div data-role='collapsible' data-collapsed-icon="gear" class='category' id="<?php echo $categoryBreakID ?>">
            <h1 id="<?php echo $categoryBreak ?>"><?php echo $categoryBreak ?></h1>

            <table style='margin-left:50%; transform:translate(-50%);'>
                <tr>
                    <th>figureBy</th>
                    <th>Item Name</th>
                    <th>Item Number</th>
                    <th>Item Cost</th>
                    <th>Ounces</th>
                    <th>Selling Price</th>
                    <th>Cost / Oz</th>
                    <th>Cost Per Drink</th>
                    <th>% Cost to Price</th>
                </tr>
                <?php
                    }
                ?>

                <!--builds items by supplier with less than 10 on hand and on hand is less than or equal to 4 wk average use-->


                <tr>
                    <td><?php echo $figureBy ?></td>
                    <td style='text-align:left'><?php echo $itemName ?></td>
                    <td><?php echo $currItem ?></td>
                    <td><?php echo $itemCost ?></td>
                    <td><?php echo $ounces ?></td>
                    <td><?php echo $sellPrice ?></td>
                    <td><?php echo $costPerOz ?></td>
                    <td><?php echo $costPerDrink ?></td>
                    <td><?php echo $percentToPrice ?></td>
                </tr>
                <?php


                    $counts++;

                    }
                ?>
        </div> <!--closes collapsible set-->
    </div>
    <!--<div data-role='footer'>
                <h3>It took <?php /*echo $processTime */ ?> milliseconds to process
        <?php /*echo $counts */ ?> items!</h3>
            </div>-->

</div>


</body>
</html>
