<?php
session_start();
$userid=$_SESSION['userid'];
if(!isset($userid))
    die("<script>alert('你还未登录');window.location='index.php';</script>");
include ('mysqli_connect.php');

if($_SERVER["REQUEST_METHOD"] == "GET"){
    $action=$_GET['action'];
    $itemid= replaceSpecialChar( $_GET['itemid']);

    if (isset($action) && isset($userid) && isset($itemid)){
        if($action=='reserve'){
            $sqla="INSERT INTO reservation_list(sernum,student_id,item_id,start_time,due_time) VALUES(NULL,$userid,$itemid,NOW(),DATE_ADD(NOW(),INTERVAL 1 DAY)) ";
            $resa=mysqli_query($dbc,$sqla);
            $sqlb="UPDATE item_info set state=2 where item_id={$itemid} LIMIT 1";
            $resb=mysqli_query($dbc,$sqlb);
            if ($resa==1 && $resb==1) {
                echo "<script>alert('预约成功！');window.location.href='member_reservation_list.php';</script>";
            }
            else {
                echo "<script>alert('预约失败！');window.location.href='member_reservation_list.php';</script>";
            }
        }
        else if($action=='cancel'){
            $sqla="DELETE FROM reservation_list WHERE item_id={$itemid} LIMIT 1";
            $resa=mysqli_query($dbc,$sqla);
            $sqlb="UPDATE item_info set state=1 where item_id={$itemid} LIMIT 1";
            $resb=mysqli_query($dbc,$sqlb);
            if ($resa==1 && $resb==1) {
                echo "<script>alert('预约取消成功！');window.location.href='member_reservation_list.php';</script>";
            }
            else {
                echo "<script>alert('预约取消失败！');window.location.href='member_reservation_list.php';</script>";
            }
        }
    }
    else die("<script>alert('请求参数不完整');window.location.href='member_reservation_list.php'; </script>");

}
