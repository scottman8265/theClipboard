<?php

    include('../cfg/cfgGlobal.php');
    include('../cfg/connect.php');
    $data = false;
    $userReq = $_POST['userReq'];


    $verify = $conn->prepare("SELECT userName FROM quenchInventory.users WHERE userReq = ?");
    $verify->bind_param("s", $userReq);
    $verify->execute();
    $verify->store_result();
    $verify->bind_result($user);
    $verify->fetch();
    if (!$verify) {
        $data['success'] = false;
    }

    if ($verify) {
        $data['success'] = true;
        $data['user'] = $user;
        $data['userReq'] = $userReq;
    }

    echo json_encode($data); ?>

<script>
    
</script>