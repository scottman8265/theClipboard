<?php
    include("../cfg/cfgGlobal.php");
    include("../cfg/connect.php");

   $db = new DB();

    $currDate = $_SESSION['currDate'];
    $oldDate = $_SESSION['oldDate'];
    $category = $_SESSION['category'];
    $categoryID = $_SESSION['categoryID'];
    $location = $_SESSION['location'];
    $locationID = $_SESSION['locationID'];
    $tableID = basename($_SERVER["SCRIPT_FILENAME"]);

?>


<html>
<head>
    <title>Inventory Count</title>
    <link href="stylesheet.css" rel="stylesheet" type="text/css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
</head>
<body>
<div id="pageContainer">

    <?php
        /*Build Category Menu*/
        $buildCatMenu = $conn->prepare("SELECT categoryID, categoryName FROM quenchInventory.categoryTbl");
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
        <div id="countHeader"><?php echo $_SESSION['category'] . ":" . $_SESSION['location'] ?> </div>
        <div id="itemsDiv">

        <?php
            $columnCount = 0;
            $count = 0;

            /*    Get Items to search with*/
            $getItems = $conn->prepare("SELECT a.itemName, a.flavor, a.category, a.itemNum FROM quenchInventory.itemTbl AS a LEFT JOIN quenchInventory.categoryTbl AS b ON a.category = b.categoryID WHERE a.category = ? ORDER BY a.itemName, a.flavor");
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
                    console.log("category changed:  " + data);
                    $('#itemsDiv').html("<h1>Loading Items...</h1>");
                    window.location.href = 'invItems.php';
                    console.log($('#itemsDiv').html());
                });
                changeCat.fail(function (data) {
                    console.log("category not changed:  " + data);
                });
                console.log("category menu button pressed: " + $(this).attr('id') + " : " + $(this).text());

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
                    $('#countHeader').load("invItems.php #countHeader");
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