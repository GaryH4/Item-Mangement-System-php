<?php
$studentid = $_GET['studentid'];
$itemid = $_GET['itemid'];
$action = $_GET['action'];
include('mysqli_connect.php');
include('function_lib.php');

if (isset($studentid)) {
    $sqla = "SELECT student_id,card_state from member_info where student_id='$studentid'";
    $resa = mysqli_query($dbc, $sqla);
    $resulta = mysqli_fetch_assoc($resa);


    if ($resulta["card_state"] == 1) {
        if (isset($action)) {
            if (isset($itemid) && $action == "reserve") {
                $sqla = "INSERT INTO reservation_list(sernum,student_id,item_id,start_time,due_time) VALUES(NULL,$studentid,$itemid,NOW(),DATE_ADD(NOW(),INTERVAL 1 DAY))";
                $resa = mysqli_query($dbc, $sqla);
                $sqlb = "UPDATE item_info set state=2 where item_id={$itemid} LIMIT 1";
                $resb = mysqli_query($dbc, $sqlb);
                if ($resa == 1 && $resb == 1) {
                    echo '{"res":"success"}';
                } else echo '{"err":"action failed"}';
            } else if (isset($itemid) && $action == "cancel") {
                $sqla = "DELETE FROM reservation_list WHERE item_id='{$itemid}' LIMIT 1";
                $resa = mysqli_query($dbc, $sqla);
                $sqlb = "UPDATE item_info SET state=1 WHERE item_id='{$itemid}' AND state=2 LIMIT 1";
                $resb = mysqli_query($dbc, $sqlb);
                if ($resa == 1 && $resb == 1) {
                    echo '{"res":"success"}';
                } else echo '{"err":"action failed"}';
            } else if ($action == "query") {
                $sqlb = "SELECT * FROM reservation_list WHERE student_id = '{$studentid}'";
                $resb = mysqli_query($dbc, $sqlb);
                $results = array();
                if (mysqli_num_rows($resb) > 0) {
                    while ($line = mysqli_fetch_assoc($resb)) {
                        $results[] = $line;
                    }
                    echo json_encode($results);
                } else echo '{"res":"no reservation yet"}';
            } else echo '{"err":"bad request param"}';
        } else echo '{"err":"bad request param"}';
    } else echo '{"err":"card is disables"}';
} else die('{"err":"empty studentid param"}');
