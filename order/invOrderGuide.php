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
    $supplierBreak = "";
    $supplierBreakID = "";


?>

<html>
<head>
    <title>Order Guide</title>
    <link href="../report/stylesheet2.css" rel="stylesheet" type="text/css"/>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>
</head>
<body>

<div data-role="page">
    <div data-role="header">

        <ul data-role="listview" data-split-theme="a" data-split-icon="back">
            <li>
                <a href="#"><h1 style="text-align: center;">Quench Lounge Order Guide for week
                        Ending <?php echo $currDate; ?></h1></a>
                <a href="#" data-rel="back">Go Back</a>
            </li>
        </ul>

    </div>
    <div data-role="main">


        <?php

            $getItems = $conn->prepare("SELECT a.itemName, a.flavor, a.itemNum, b.supplierName AS supplier, b.supplierID, a.active FROM quenchInventory.itemTbl AS a JOIN quenchInventory.supplierTbl AS b ON a.supplier = b.supplierID LEFT JOIN quenchInventory.categoryTbl AS c ON a.category = c.categoryID LEFT JOIN quenchInventory.familyTbl AS d ON c.family = d.familyName where a.active = 'y' and a.supplier != 'SO' ORDER BY b.supplierPriority, d.familyPriority, c.categoryPriority, a.itemName, a.flavor");
            $getCount = $conn->prepare("SELECT sum(countCol) AS currCount FROM quenchInventory.countTbl WHERE dateCol = ? AND itemNum = ?");
            $getReceived = $conn->prepare("SELECT sum(receivedCol) AS qtyReceived FROM quenchInventory.receivingTbl WHERE dateCol = ? AND itemNum = ? GROUP BY itemNum");

            $getItems->execute();
            $getItems->store_result();
            $getItems->bind_result($itemName, $flavor, $currItem, $currSupplier, $currSupplierID, $active);

            while ($getItems->fetch()) {
            $itemCount = 0;
            $itemName = $itemName . "  " . $flavor;
            $processTime = microtime(true);
            $currCount = (float)$db->getCount($currDate, $currItem);
            $priorCount = (float)$db->getCount($oldDate, $currItem);
            $received = (float)$db->getReceived($currDate, $currItem);
            $averages = $db->getDaysOnHand($currItem, $currDate);
            $wksOnHand = $averages['wksOnHand'];
            $avgUsed = $averages['avgUsed'];
            $avgUsed = number_format((float)$avgUsed, 3, '.', '');
            if ($wksOnHand != 'irrelevant') {
                $wksOnHand = number_format((float)$wksOnHand, 3, '.', '');
            }

            $preCount = $priorCount + $received;
            $itemUsed = $preCount - $currCount;
            $itemCount = $currCount;

            if ($supplierBreak != $currSupplier and $counts != 0) {
            $oldSupplier = $supplierBreak;
            $oldSupplierID = $supplierBreakID;
            $supplierBreak = $currSupplier;
            $supplierBreakID = $currSupplierID;
        ?>
        </table>  <!--do not delete... this is the correct end tag-->
        <input type='submit' value='Submit <?php echo $oldSupplier ?> Order' name='orderBtn' id='orderBtn<?php echo $oldSupplierID ?>'
               onclick="submitOrder(event, '<?php echo $oldSupplierID ?>', '<?php echo $oldSupplier ?>');"/>
        </form>  <!-- do not delete... this is the correct end tag-->
    </div>
    <div data-role='collapsible' class='category' id="<?php echo $supplierBreakID ?>">
        <h1 id="<?php echo $supplierBreak ?>"><?php echo $supplierBreak ?></h1>
        <div id="<?php echo $supplierBreakID ?>Holder"></div>

        <form name='orderForm' id='orderForm<?php echo $currSupplierID ?>'>
            <table style='margin-left:50%; transform:translate(-50%);'>
                <tr>
                    <th>Item Name</th>
                    <th>Item Number</th>
                    <th>On Hand</th>
                    <th>QTY Used</th>
                    <th>4wk Avg.</th>
                    <th>Wks Inv.</th>
                    <th>Order</th>
                    <th>Order Qty</th>
                    <th>Case/Btl</th>
                </tr>
                <?php
                    }
                    if ($supplierBreak != $currSupplier and $counts == 0) {
                    $supplierBreak = $currSupplier;
                    $supplierBreakID = $currSupplierID;
                ?>
                <div data-role='collapsible' class='category' id="<?php echo $supplierBreakID ?>">

                    <h1 id="<?php echo $supplierBreak ?>"><?php echo $supplierBreak ?></h1>
                    <div id="<?php echo $supplierBreakID ?>Holder"></div>
                    <form name='orderForm' id='orderForm<?php echo $currSupplierID ?>'>
                        <table style='margin-left:50%; transform:translate(-50%);'>
                            <tr>
                                <th>Item Name</th>
                                <th>Item Number</th>
                                <th>On Hand</th>
                                <th>QTY Used</th>
                                <th>4wk Avg.</th>
                                <th>Wks Inv.</th>
                                <th>Order</th>
                                <th>Order Qty</th>
                                <th>Case/Btl</th>
                            </tr>
                            <?php }


                                if ($wksOnHand != 'irrelevant' and $wksOnHand < 10 or $currCount <= $avgUsed) {
                                    ?>
                                    <tr>
                                        <td style='text-align:left'><?php echo $itemName ?></td>
                                        <td><?php echo $currItem ?></td>
                                        <td><?php echo $currCount ?></td>
                                        <td><?php echo $itemUsed ?></td>
                                        <td><?php echo $avgUsed ?></td>
                                        <td><?php echo $wksOnHand ?></td>
                                        <td>
                                            <label for="order<?php echo $currItem ?>"
                                                   class="ui-hidden-accessible">Order</label>
                                            <input type='checkbox' data-role='flipswitch'
                                                   id='order<?php echo $currItem ?>'
                                                   name='order<?php echo $currItem ?>'>
                                        </td>
                                        <td>
                                            <label for="orderQty<?php echo $currItem ?>" class="ui-hidden-accessible">Order
                                                Qty</label>
                                            <input type='tel' id="orderQty<?php echo $currItem ?>"
                                                   name='orderQty<?php echo $currItem ?>' placeholder='QTY'/>
                                        </td>
                                        <td>
                                            <label for="pack<?php echo $currItem ?>" class="ui-hidden-accessible">Order
                                                By</label>
                                            <select id="pack<?php echo $currItem ?>" name='pack<?php echo $currItem ?>'>
                                                <option>Case</option>
                                                <option>Bottle</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php


                                    $counts++;
                                }
                                $processTime = (microtime(true) - $processTime) * 1000;
                                }
                            ?>

                </div>


    </div>
    <!--    <div data-role='footer'>-->
    <!--        <h3>It took --><?php //echo $processTime ?><!-- milliseconds to process -->
    <?php //echo $counts ?><!-- items!</h3>-->
    <!--    </div>-->
    <script>
        function submitOrder(event, supplierID, supplier) {
            event.preventDefault();
            console.log("submit Pressed:::!!!" + supplierID);
            sendData = {'supplier' : supplier,
                        'supplierID' : supplierID,
                        'setSupplier' : true};
            var setSupplier = $.post('../cfg/cfgGlobal.php', sendData);
            setSupplier.done(function(data) {
                console.log("success: " + data);
            });
            setSupplier.fail(function(data) {
                console.log("failure: " + data);
            });
            var formID = '#orderForm' + supplierID;
            var submitID = '#orderBtn' + supplierID;
            var divHolder = '#' + supplierID + "Holder";
            var formData = $(formID).serialize();
            var sent = $.post('processSupplierOrder.php', formData);
            sent.done(function (data) {
                console.log("success:  " + data);
                $(divHolder).text(supplier + " Order Submitted");
                console.log($(submitID).text());
                $(divHolder).html("<h3 style='background-color:red; text-align:center'>" + supplier + " Order Submitted</h3>");
            });
            sent.fail(function (data) {
                console.log("failure:  " + data);
            });
        }

    </script>
</body>
</html>
