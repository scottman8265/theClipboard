<?php
    $db = new DB();
    $currCount = $db->getCount($_SESSION['currDate'], $itemNum);
    $oldCount = $db->getCount($_SESSION['oldDate'], $itemNum);
    $received = $db->getReceived($_SESSION['currDate'], $itemNum);
    $currLocation = $db->getLocCounts($_SESSION['locationID'], $itemNum, $_SESSION['currDate']);
    $starting = $oldCount + $received;
    $used = $starting - $currCount;
    $currLocation1 = 0;
    $oldLocation1 = 0;
    $currLocation2 = 0;
    $oldLocation2 = 0;
    $currLocation3 = 0;
    $oldLocation3 = 0;
    $currLocation4 = 0;
    $oldLocation4 = 0;

?>
<div id='itemArea<?php echo $itemNum; ?>' class='itemArea areas flexItem button'>
    <div class='itemName'><?php echo $itemName . "  " . $flavor; ?></div>
</div>

<div id='enterArea<?php echo $itemNum; ?>' class='enterArea areas button flexItem'>
    <form name='sendCount' id='sendCount<?php echo $itemNum; ?>' method='POST' action='enterCount.php'>
        <label for="<?php echo $itemNum; ?>" class="ui-hidden-accessible"></label>
        <input id='<?php echo $itemNum; ?>' type='tel' step='any' name='count'/>
        <input type='hidden' name='itemNum' value='<?php echo $itemNum; ?>'/>

        <input type='submit' id='sendCountBtn<?php echo $itemNum; ?>' value='count<?php echo $itemNum; ?>'
               style='opacity:0;'/>
    </form>
</div>

<div id='infoArea<?php echo $itemNum; ?>' class='infoArea flexContainer'>
    <div class='flexItem'>
        <div class='label infoArea itemFloat'>Loc1:</div>
        <div class='label infoArea itemFloat'><?php echo $currLocation1 . " / " . $oldLocation1; ?></div>
    </div>
    <div class='flexItem'>
        <div class='label infoArea itemFloat'>Loc2:</div>
        <div class='label infoArea itemFloat'><?php echo $currLocation2 . " / " . $oldLocation2; ?></div>
    </div>
    <div class='flexItem'>
        <div class='label infoArea itemFloat'>Loc3:</div>
        <div class='label infoArea itemFloat'><?php echo $currLocation3 . " / " . $oldLocation3; ?></div>
    </div>
    <div class='flexItem'>
        <div class='label infoArea itemFloat'>Loc4:</div>
        <div class='label infoArea itemFloat'><?php echo $currLocation4 . " / " . $oldLocation4; ?></div>
    </div>
    <div class='flexItem'>
        <div class='label infoArea itemFloat'>Count:</div>
        <div id="currCount<?php echo $itemNum; ?>"
             class='label infoArea itemFloat'><?php echo $currCount . " / " . $starting; ?></div>
    </div>
</div>


<script>
    $("#countArea").on("submit", "#sendCount<?php echo $itemNum; ?>", function (event) {
        event.preventDefault();
    });

    $("#countArea").on("focusout", "#sendCount<?php echo $itemNum; ?>", function (event) {
        event.stopImmediatePropagation();
        event.preventDefault();
        console.log("#sendCount submitted:" + $('#<?php echo $itemNum ?>').val());
        var data = $(this).serialize();
        var sent = $.post('enterCount.php', data, null, 'json');
        if ($('#<?php echo $itemNum ?>').val() != "") {
            sent.done(function (data) {
                console.log(data.dataInsert);
                if (data.dataInsert == true) {
                    console.log("inserted in data base");
                    $('#currCount<?php echo $itemNum ?>').html('...');
                    $('#currCount<?php echo $itemNum ?>').load('items.php #currCount<?php echo $itemNum ?>');
                    $('#<?php echo $itemNum ?>').val("");
                } else {
                    console.log("did not insert in data base");
                }
            });

            sent.fail(function (data) {
                console.log("error did not insert");
            });
        }

    });
</script>