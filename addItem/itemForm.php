<?php
session_start();
include("../cfg/connect.php");

$sql = "SELECT categoryName, categoryID FROM categoryTbl";
$res = mysqli_query($conn, $sql);

$sql2 = "SELECT * FROM supplierTbl";
$res2 = mysqli_query($conn, $sql2);

$sql3 = "SELECT * FROM bottleSizes";
$res3 = mysqli_query($conn, $sql3);

function category($results)
{
    echo "<label for='category'>Select Category:  </label><select id='category' name ='category'>";
    foreach ($results as $category) {
        echo "<option value='" . $category['categoryID'] . "'>" . $category['categoryName'] .
            "</option>";
    }
    echo "</select>";

}

function supplier($results2)
{
    echo "<label for='supplier'>Select Supplier:  </label><select id='supplier' name ='supplier'>";
    foreach ($results2 as $supplier) {
        echo "<option value='" . $supplier['supplierID'] . "'>" . $supplier['supplierName'] .
            "</option>";
    }
    echo "</select>";

}

function size($results3)
{
    echo "<label for='size'>Select Package Size:  </label><select id='size' name ='size'>";
    foreach ($results3 as $size) {
        echo "<option value='" . $size['sizeID'] . "'>" . $size['size'] .
            "</option>";
    }
    echo "</select>";

}

?>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="content-type" content="text/html"/>
    <meta name="author" content=""/>
    <link href="../inc/stylez.css" rel="stylesheet"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.css"/>

    <title>Add Item</title>
</head>

<body>
<div data-role="page" data-theme="b">
    <div data-role="header" data-theme="a"> <ul data-role="listview" data-split-icon="back" data-split-theme="d">
        <li><a href="#" class="" id="enterItemStatus">Enter 1st Item Info</a><a href="index.html" data-rel="back">Go Back</a></li>
    </div>

    <?php

    #var_dump($res);
    #echo "  category<br />";
    #var_dump($res2);
    #echo "  supplier<br />";
    #var_dump($res3);
    #echo "  bottleSizes<br />";

    ?>
    <div data-role="main">
        <form id='addItem' name='addItem' method='POST' action='addItem.php'>
            <label for='itemNum' class="ui-hidden-accessible">Item Number: </label><input type='tel' id='itemNum' name="itemNum"
                                                                                          placeholder="Item Number"/>
            <label for='itemName' class="ui-hidden-accessible">Item Name: </label><input type='text' id='itemName' name="itemName"
                                                                                         placeholder="Item Name"/>
            <label for='flavor' class="ui-hidden-accessible">Flavor: </label><input type='text' id='flavor' name="flavor"
                                                                                    placeholder="Flavor"/>
            <label for='pack' class="ui-hidden-accessible">Pack Quantity: </label><input type='tel' id='pack' name="pack" placeholder="Pack Quantity"/>
            <?php category($res); ?>
            <?php size($res3); ?>
            <?php supplier($res2); ?>
            <input type='submit' name='submit' value='submit'/>
        </form>
    </div>

</div>

<script>
    $(document).ready(function() {

        function enterNextItem() {
            $('#enterItemStatus').text('Enter Next Item');
        }

        $('#addItem').submit(function(event) {
           event.preventDefault();
             var submit = $.post('addItem.php', $('#addItem').serialize(), null, 'json');
                submit.done(function(data) {
                    var $success = data.dataInsert;
                   console.log("success:  " + $success);
                    if ($success) {
                        $('#enterItemStatus').text('Item Added Successfully To Database').delay(1000).text("Enter Next Item");
                        /*enterNextItem();*/
                    }
                    if (!$success) {
                        $('#enterItemStatus').text('Item Not Added to Database, Please try Again').delay(1000).text("EnterNextItem");
                        /*enterNextItem();*/

                    }
                });
                submit.fail(function(data) {
                    console.log("failure:  " + data.dataInsert);
                });
        });
        
        
    });
    
</script>

</body>
</html>