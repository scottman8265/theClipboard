
<?php

include("../cfg/connect.php");
include("../cfg/cfgGlobal.php");


//Inventory Selector Query
$selectDate = $conn->prepare("select dateCol from quenchInventory.dateTbl order by dateID DESC");
$selectDate->execute();
$selectDate->store_result();
$selectDate->bind_result($dateCol);
#$dateArray = array();
//Category Selector Query
$selectCategory = $conn->prepare("select categoryName, categoryID from quenchInventory.categoryTbl");
$selectCategory->execute();
$selectCategory->store_result();
$selectCategory->bind_result($category, $categoryID);
#$categoryArray = array();
//Location Selector Query
$selectLocation = $conn->prepare("select locationName, locationID from quenchInventory.locationTbl");
$selectLocation->execute();
$selectLocation->store_result();
$selectLocation->bind_result($location, $locationID);
#$locationArray = array();

echo "<form id='startCount' name='startCount' method='POST' action='../cfg/cfgGlobal.php'>";

#<!--Date Selection-->
echo "<label for='dateSel'>Select Count Date</label>
<select id='dateSel' name ='currDate' class='select'>";
while ($selectDate->fetch()) {
    echo "<option value='" . $dateCol . "'>" . $dateCol .
        "</option>";
}
echo "</select><br /><br />";

#<!--Category Selection-->
echo "<label for='categorySel'>Select Starting Category</label><select id='categorySel' name='categorySel' class='select'>";
while ($selectCategory->fetch()) {
    echo "<option value='" . $categoryID .
        "'>" . $category . "</option>";
}
echo "</select><br /><br />";

#<!--Location Selection-->
echo "<label for='locationSel'>Select Starting Location</label><select id='locationSel' name='locationSel' class='select'>";
while ($selectLocation->fetch()) {
    echo "<option value='" . $locationID . "' id='" .$locationID .
        "'>" . $location . "</option>";
}
echo "</select><br /><br />";

echo "</form>";
?>


<!--</body>
</html>-->