<?php
include("mysqli_connect.php");
include("function_lib.php");
$ip = getIP();
if ($ip == "127.0.0.1") {
    //检查超期项目：借用、预约
    $sqla = "SELECT * FROM lend_list WHERE NOW()>due_date AND ISNULL(back_date)";
    $resa = mysqli_query($dbc, $sqla);
    foreach ($resa as $row) {
        echo "expired: " . $row['student_id'] . ';';
        $sqlb = "UPDATE member_info SET card_state=0 WHERE student_id={$row['student_id']} LIMIT 1";
        $resb = mysqli_query($dbc, $sqlb);
        //TODO:通知管理员
    }
    $sqlc = "SELECT * FROM reservation_list WHERE NOW()>due_time";//TODO:修改预约逻辑
    $resc = mysqli_query($dbc, $sqlc);
    foreach ($resc as $row) {
        echo "expired reservation: " . $row['student_id'] . ';';
        $sqld = "DELETE FROM reservation_list WHERE sernum={$row['sernum']} LIMIT 1";
        $resd = mysqli_query($dbc, $sqld);
        $sqle = "UPDATE item_info SET state=1 WHERE item_id={$row['item_id']} LIMIT 1";
        $rese = mysqli_query($dbc, $sqle);
    }

    die();
} else {
    header('HTTP/1.1 403 Forbidden');
    die("403 Forbidden");
}


