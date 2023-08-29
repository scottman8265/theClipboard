<?php
    include("../cfg/cfgGlobal.php");
    include("../cfg/connect.php");

    $db = new DB();

    $currDate = $_SESSION['currDate'];
    $oldDate = $_SESSION['oldDate'];
    $category = $_SESSION['categoryID'];
    $categoryID = $_SESSION['categoryID'];
    $location = $_SESSION['location'];
    $locationID = $_SESSION['locationID'];
    $tableID = basename($_SERVER["SCRIPT_FILENAME"]);

?>


<html>
<head>
    <title>Supply Count</title>
    <link href="stylesheet.css" rel="stylesheet" type="text/css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
</head>
<body>
<div id="pageContainer">

    <?php
        /*Build Category Menu*/
        $buildCatMenu = $conn->prepare("SELECT a.categoryID, a.categoryName FROM quenchInventory.categoryTbl AS a LEFT JOIN quenchInventory.itemTbl AS b ON a.categoryID = b.category LEFT JOIN quenchInventory.supplierTbl AS c ON b.supplier = c.supplierID WHERE c.supplierID = 'so' OR a.categoryID = 'B' GROUP BY a.categoryID");
        $buildCatMenu->execute();
        $buildCatMenu->store_result();
        $buildCatMenu->bind_result($catID, $cat);
    ?>
    <div id='selectCategoryWrapper' class='flexContainer'>
        <?php while ($buildCatMenu->fetch()) { ?>
            <div id='<?php echo $catID ?>' class='flexItem button'>
                <button id='<?php echo $catID ?>' name='<?php echo $cat ?>'><?php echo $cat ?></button>
            </div>
        <?php } ?>

    </div>

    <?php
        /*Build Location Menu*/
        $getLocation = $conn->prepare("SELECT locationName, locationID FROM quenchInventory.locationTbl");
        $getLocation->execute();
        $getLocation->store_result();
        $getLocation->bind_result($loc, $locID);
    ?>
    <div id='selectLocationWrapper' class='flexContainer'>

        <?php while ($getLocation->fetch()) { ?>
            <div id='<?php echo $locID ?>' class='changeLocation flexItem button'>
                <button id='<?php echo $locID ?>' name='<?php echo $loc ?>'><?php echo $loc ?></button>
            </div>
        <?php } ?>
    </div>

    <div id='countArea' class='countArea' style='padding:0; margin:0;'>
        <div id="countHeader"><?php echo $_SESSION['categoryID'] . ":" . $_SESSION['location'] ?> </div>
        <div id="itemsDiv">

            <?php
                $columnCount = 0;
                $count = 0;

                /*    Get Items to search with*/
                $getItems = $conn->prepare("SELECT a.itemName, a.flavor, a.category, a.itemNum FROM quenchInventory.itemTbl AS a LEFT JOIN quenchInventory.categoryTbl AS b ON a.category = b.categoryID WHERE a.category = ? AND a.active = 'y' AND a.supplier = 'SO' ORDER BY a.itemName, a.flavor");
                $getItems->bind_param("s", $_SESSION['categoryID']);
                $getItems->execute();
                $getItems->store_result();
                $getItems->bind_result($itemName, $flavor, $category, $itemNum);

                while ($getItems->fetch()) {
                    echo "<div id='itemWrap' class='itemWrap itemFloat flexContainer'>";
                    include("../inc/itemTable.php");
                    echo "</div>";
                    $count++;
                    $columnCount = 0;
                }

                echo "</div></div>";
                echo $count;
            ?>

            <a href="index.html">go back</a>
        </div>
        <script>
            $(document).ready(function () {

//        change Category
                $('#selectCategoryWrapper').on("click", ".button", function () {
                    var changeData = {
                        "categoryID": $(this).attr('id'),
                        "category": $(this).text(),
                        "changeCategory": true
                    };
                    console.log(changeData);
                    var changeCat = $.post('../cfg/cfgGlobal.php', changeData);
                    changeCat.done(function (data) {
                        console.log("categoryID changed:  " + data);
                        $('#itemsDiv').html("<h1>Loading Items...</h1>");
                        window.location.href = 'supItems.php';
                        console.log($('#itemsDiv').html());
                    });
                    changeCat.fail(function (data) {
                        console.log("categoryID not changed:  " + data);
                    });
                    console.log("categoryID menu button pressed: " + $(this).attr('id') + " : " + $(this).text());

                });

//        change location
                $('#selectLocationWrapper').on("click", "button", function () {
                    var changeData = {
                        "locationID": $(this).attr('id'),
                        "location": $(this).text(),
                        "changeLocation": true
                    };
                    console.log(changeData);
                    var changeCat = $.post('../cfg/cfgGlobal.php', changeData);
                    changeCat.done(function (data) {
                        console.log("location changed:  " + data);
                        $('#countHeader').html("<h1>Changing Location...</h1>");
                        $('#countHeader').load("supItems.php #countHeader");
                    });
                    changeCat.fail(function (data) {
                        console.log("location not changed:  " + data);
                    });
                    console.log("location menu button pressed: " + $(this).attr('id') + " : " + $(this).text());
                });
            });

        </script>

</body>

</html>