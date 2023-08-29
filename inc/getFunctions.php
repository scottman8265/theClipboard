<?php
require_once("../inc/cfg/config.php");

class DB
{

    public $link;

    public function __construct()
    {
        $this->link = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if (mysqli_connect_errno()) {
            exit();
        }
    }

    public function __destruct()
    {
        mysqli_close($this->link);
    }

    public function getOldDate($currDate)
    {
        $preDate = $this->link->prepare("select dateID from dateTbl where dateCol = ?");
        $preDate->bind_param("i", $currDate);
        $preDate->execute();
        $preDate->store_result();
        $preDate->bind_result($thisID);
        $preDate->fetch();
        
        $thatID = $thisID - 1;
        
        $thatDate = $this->link->prepare("select dateCol from dateTbl where dateID = ?");
        $thatDate->bind_param("i", $thatID);
        $thatDate->execute();
        $thatDate->store_result();
        $thatDate->bind_result($oldDate);
        $thatDate->fetch();

        return $oldDate;
    }

    public function getItemName($currItem)
    {
        $name = $this->link->prepare("select itemName, flavor from itemTbl where itemNum = ?");
        $name->bind_param("i", $currItem);
        $name->execute();
        $name->store_result();
        $name->bind_result($itemName, $flavor);
        $name->fetch();

        $itemName = $itemName . " " . $flavor;
        
        return $itemName;
    }

    public function getCount($currDate, $currItem)
    {
        $currentCount = $this->link->prepare("select sum(countCol) as 'count' from countTbl where dateCol = ? and itemNum = ? group by itemNum");
        $currentCount->bind_param("ii", $currDate, $currItem);
        $currentCount->execute();
        $currentCount->store_result();
        $currentCount->bind_result($count);
        $currentCount->fetch();
        
        if(!$currentCount) {
            $count = 0;
        }

        return $count;
    }

      public function getReceived($currDate, $currItem)
    {
        $rec = $this->link->prepare("select sum(receivedCol) as qtyReceived from receivingTbl where dateCol = ? and itemNum = ? group by itemNum");
        $rec->bind_param("ii", $currDate, $currItem);
        $rec->execute();
        $rec->store_result();
        $rec->bind_result($receive);
        $rec->fetch();
        
        if (!$rec) {
            $receive = 0;
        }

        return $receive;
    }
    
    public function getCategory($categoryID) {
        $cat = $this->link->prepare("select categoryName from categoryTbl where categoryID = ?");
        $cat->bind_param("s", $categoryID);
        $cat->execute();
        $cat->store_result();
        $cat->bind_result($category);
        $cat->fetch();

        return $category;
    }

    public function getLocation($locationID) {
        $loc = $this->link->prepare("select locationName from locationTbl where locationID = ?");
        $loc->bind_param("s", $locationID);
        $loc->execute();
        $loc->store_result();
        $loc->bind_result($location);
        $loc->fetch();

        return $location ;
    }

    public function getSupplier($supplierID) {
        $loc = $this->link->prepare("select supplierName from supplierTbl where supplierID = ?");
        $loc->bind_param("s", $supplierID);
        $loc->execute();
        $loc->store_result();
        $loc->bind_result($supplier);
        $loc->fetch();

        return $supplier;
    }



    public function getLocCounts($date, $itemNum) {
        $locationArray = array();

        $locationCol = $this->link->prepare ("select sum(a.countCol), b.locationName from countTbl as a left join locationTbl as b on a.locationCol = b.locationID where dateCol = ? and itemNum = ? group by locationCol");
        $locationCol->bind_param("ii", $date, $itemNum);
        $locationCol->execute();
        $locationCol->store_result();
        $locationCol->bind_result($count, $location);

        while ($locationCol->fetch()) {
            $locationArray['count'][] = $count;
            $locationArray['location'][] = $location;

        }

        return $locationArray;
    }



}

?>