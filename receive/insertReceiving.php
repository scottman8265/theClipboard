<?php

include("../cfg/connect.php");

#var_dump($_POST); echo "<br />"; 

foreach ($_POST as $var => $something) {
    if (!is_int($var)){
        $count = $something;
        echo $count ."   " ;

    }else{
        $vars = explode (":", $something);
    #echo "var:  "; var_dump($var); echo "<br />";
    #echo "something:  "; var_dump($something); echo "<br />";
   #echo "vars:  "; var_dump ($vars); echo "<br />";
    $supplierID = $vars['1'];
    $itemNum = $vars['0'];
    $dateCol = $vars['2'];
#    echo $supplierID ."  " .$itemNum ."<br /><br />"; 
#    echo $dateCol ."<br />";
    
$sql = "insert into quenchInventory.receivingTbl (supplierID, dateCol, receivedCol, itemNum) values ('". $supplierID ."',". $dateCol .", " .$count .", " .$itemNum .")";
$res = mysqli_query($conn, $sql);
        
    }


}


header("Location: index.php")



?>