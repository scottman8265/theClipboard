
<html>

<head>
<title>Receiving Page</title>
<link href="stylesheet.css" rel="stylesheet" type="text/css" />

</head>


<body>
<?php

include("../cfg/connect.php");

#var_dump($_POST);
$dateCol = $_POST['dateCol'];
$supplier = explode (":", $_POST['supplierID']);
$supplierID = $supplier['0'];
$supplierName = $supplier['1'];

echo "<br /><br />" . $supplierName . "<br /><br />";

$sql3 = "select itemName, flavor, itemNum from quenchInventory.itemTbl where supplier = '" . $supplierID .
    "' order by category, itemName";
$res3 = mysqli_query($conn, $sql3);


echo "<table border = '1'><form name='sendReceiving' method='POST' action='insertReceiving.php'>";
foreach ($res3 as $items) {
    echo "<tr>";
$itemName = $items['itemName'] . "  " . $items['flavor'];
print "<td><input type='text' name='count:".$items['itemNum'] ."'/></td><td><input type='hidden' name='" .$items['itemNum'] ."' value='" .$items['itemNum'] .":" .$supplierID .":" .$dateCol ."'/>" .$itemName ."</td>";
echo"</tr>";
}
echo"<input type='submit' name='submit' value='submit'/></form></table>";
?>

<a href="index.php">go back</a>
</body>
</html>