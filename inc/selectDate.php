<?php

echo "session id from select Date:  " . session_id() . "</br>";
include('../cfg/connect.php');

$sql5 = "select * from dateTbl order by dateID DESC";
$res5 = mysqli_query($conn, $sql5);

$sql6 = "select * from category";
$res6 = mysqli_query($conn, $sql6);

echo "<form name='dateSelection' method='POST' action='../enterCount/items.php'><select input type='text' name ='date1'>";

foreach ($res5 as $dates) {
    echo "<option value='" . $dates['dateCol'] . "'>" . $dates['dateCol'] .
        "</option>";
}

echo "</select>";
echo "<select input type='text' name ='date2'>";

foreach ($res5 as $dates2) {
    echo "<option value='" . $dates2['dateCol'] . "'>" . $dates2['dateCol'] .
        "</option>";
}

echo "</select><input type='hidden' name='categoryID' value='v'></input><input type='hidden' name='category' value='VODKA'></input><input type='submit' name='send2' value='send2'></input></form>";

?>