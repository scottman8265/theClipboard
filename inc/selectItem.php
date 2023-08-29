<?php

    include("../cfg/connect.php");
    //Category Selector Query
    $selectCategory = $conn->prepare("SELECT locationName, locationID FROM quenchInventory.locationTbl");
    $selectCategory->execute();
    $selectCategory->store_result();
    $selectCategory->bind_result($location, $locationID);

    echo "<label for='locationSel' class='centerText'>Starting Location</label>
    <select id='locationSel' name='locationID' class='centerText centerObject select ui-corner-all ui-btn'>";
    while ($selectCategory->fetch()) {
        echo "<option value='" . $locationID .
            "'>" . $location . "</option>";
    }
    echo "</select>";