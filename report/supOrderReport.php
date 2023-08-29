<?php
//    session_start();
    include('../cfg/cfgGlobal.php');
    //    include('../inc/getFunctions.php');
var_dump($_SESSION);
   $currDate = $_SESSION['currDate'];
?>

<html>

<head>
    <title>Inventory Order Report</title>
    <link href="stylesheet2.css" rel="stylesheet" type="text/css"/>
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
                <a href="#"><h1 style="text-align: center;">Inventory Order Report for Wk
                        Ending <?php echo $currDate; ?></h1></a>
                <a href="#" data-rel="back">Go Back</a>
            </li>
        </ul>
    </div>
    <div data-role="main">
        <div data-role="collapsibleset">
            <?php
                $order = $conn->prepare("SELECT a.itemNum, a.orderQty, a.pack, b.itemName, b.flavor, c.supplierName FROM quenchInventory.orderTbl AS a LEFT JOIN quenchInventory.itemTbl AS b ON a.itemNum = b.itemNum LEFT JOIN quenchInventory.supplierTbl AS c ON b.supplier = c.supplierID LEFT JOIN quenchInventory.categoryTbl AS d ON b.category = d.categoryID WHERE a.orderDate = ? ORDER BY c.supplierID");
                $order->bind_param("s", $currDate);
                $order->execute();
                $order->store_result();
                $order->bind_result($itemNum, $orderQty, $pack, $itemName, $flavor, $supplier);

                echo "<table>
                        <tr>
                            <th>Item Number</th>
                            <th>Item Name</th>
                            <th>Order Qty</th>
                            <th>Supplier</th>
                        </tr>";
              while ($order->fetch()) {
                  echo "<tr>
                            <td>" .$itemNum ."</td>
                            <td>" .$itemName ." " .$flavor ."</td>
                            <td>" .$orderQty ." " .$pack ."</td>
                            <td>" .$supplier ."</td>
                        </tr>";
              }
            ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>