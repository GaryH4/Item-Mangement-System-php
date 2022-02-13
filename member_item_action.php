<?php
session_start();
$userid = $_SESSION['userid'];
if (!isset($userid))
    die("<script>alert('你还未登录');window.location='index.php';</script>");
include('mysqli_connect.php');
include('function_lib.php');

$action = $_GET['action'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adminuid = replaceSpecialChar($_POST['adminuid']);
    $item_id = replaceSpecialChar($_POST['item_id']);

    if (isset($action) && isset($userid) && isset($adminuid) && isset($item_id)) {
        if ($action == "borrow") {
            $sqla = "SELECT card_state from member_info where student_id={$userid}"; //会员是否有超期未还，有则不能借用
            $sqlb = "SELECT admin_student_id from admin where card_uid='{$adminuid}'"; //管理员验证
            $sqlc = "SELECT item_id,state from item_info where item_id={$item_id}"; //物品状态 0不可用，1可用，2被预约

            $resa = mysqli_query($dbc, $sqla);
            $resulta = mysqli_fetch_array($resa);

            $resb = mysqli_query($dbc, $sqlb);
            $resultb = mysqli_fetch_array($resb);
            $adminid = $resultb['admin_student_id'];

            $resc = mysqli_query($dbc, $sqlc);
            $resultc = mysqli_fetch_array($resc);
            $itemid = $resultc['item_id'];
            $item_state = $resultc['state'];

            if (isset($adminid) && $adminid != NULL) {
                if ($resulta['card_state'] == 1) {
                    if ($item_state == 2) {
                        $sqld = "SELECT student_id from reservation_list where item_id={$itemid}"; //预约状态
                        $resd = mysqli_query($dbc, $sqld);
                        $resultd = mysqli_fetch_array($resd);
                        if ($resultd['student_id'] == $userid) {
                            $sqle = "DELETE FROM reservation_list WHERE item_id={$itemid} LIMIT 1";
                            $rese = mysqli_query($dbc, $sqle);
                            if ($rese == 1) {
                                $item_state = 1;
                                echo "<script>alert('预约解除成功！')</script>";
                            } else {
                                echo "<script>alert('预约解除失败！借用失败！')</script>";
                                echo "<script>window.location.href=member_borrow_list.php'</script>";
                            }
                        } else die("<script>alert('物品已被他人预约，不能借用！');window.location.href='member_borrow_list.php'; </script>");
                    }
                    if ($item_state == 1) {
                        $sqla = "INSERT INTO lend_list(sernum,item_id,student_id,lend_admin_id,recover_admin_id,lend_date,due_date,back_date) values (NULL,{$itemid},{$userid},{$adminid},NULL,NOW(),date_add(NOW(), INTERVAL 6 month),NULL);";
                        // echo "<script>alert('{$sqla}');</script>";
                        // echo "<script>console.log('{$sqla}');</script>";
                        // die();
                        $resa = mysqli_query($dbc, $sqla);
                        if ($resa == 1) {
                            $sqlb = "UPDATE item_info set state=0 where item_id={$itemid} LIMIT 1";
                            $resb = mysqli_query($dbc, $sqlb);
                            if ($resb == 1) {
                                die("<script>alert('借用成功！');window.location.href='member_borrow_list.php'; </script>");
                            }
                        } else die("<script>alert('借用失败！');window.location.href='member_borrow_list.php'; </script>");
                    } else die("<script>alert('$item_state 该物品已被借出，无法再借用！');window.location.href='member_borrow_list.php'; </script>");
                } else die("<script>alert('该会员有超期未还记录，无法借用！');window.location.href='member_borrow_list.php'; </script>");
            } else die("<script>alert('查无此管理员');window.location.href='member_item_borrow.php?item_id={$item_id}'; </script>");
        } else if ($action == "return") {
            $sqla = "SELECT card_state from member_info where student_id={$userid}"; //会员是否有超期未还，归回完所有超期的才能置1
            $sqlb = "SELECT admin_student_id from admin where card_uid='{$adminuid}'"; //管理员验证
            $sqlc = "SELECT item_id,shelf_id,state from item_info where item_id={$item_id}"; //物品状态 将0->1，并展示货架号

            $resa = mysqli_query($dbc, $sqla);
            $resulta = mysqli_fetch_array($resa);

            $resb = mysqli_query($dbc, $sqlb);
            $resultb = mysqli_fetch_array($resb);
            $adminid = $resultb['admin_student_id'];

            $resc = mysqli_query($dbc, $sqlc);
            $resultc = mysqli_fetch_array($resc);
            $itemid = $resultc['item_id'];
            $shelf_id = $resultc['shelf_id'];

            if (isset($adminid) && $adminid != NULL) {
                if ($resultc['state'] == 0) {
                    $sqld = "UPDATE item_info SET state='1' where item_id={$item_id} LIMIT 1";
                    $sqle = "UPDATE lend_list SET back_date=NOW(),recover_admin_id=$adminid WHERE item_id=$item_id AND isnull(back_date)";
                    $resd = mysqli_query($dbc, $sqld);
                    $rese = mysqli_query($dbc, $sqle);
                    if ($resd == 1 && $rese == 1) {
                        echo "<script>alert('归还成功！请将物资放回货架：{$shelf_id}');</script>";
                    } else die("<script>alert('数据库写入失败，归还失败！');window.location.href='member_borrow_list.php'; </script>");
                    $sqle = "SELECT count(*) a FROM lend_list WHERE student_id=$userid and isnull(return_date)";
                    $rese = mysqli_query($dbc, $sqle);
                    $resulte = mysqli_fetch_array($resulte);
                    if ($resulte == 0) {
                        $sqlf = "UPDATE member_info SET card_state=1 WHERE student_id=$userid LIMIT 1";
                        $resf = mysqli_query($dbc, $sqlf);
                        if ($resf == 1) {
                            echo "<script>alert('无更多超期未还记录，卡状态正常！');window.location.href='member_borrow_list.php'; </script>";
                        } else echo "<script>alert('激活卡状态失败！');window.location.href='member_borrow_list.php'; </script>";
                    }
                } else die("<script>alert('物品不是借出状态，归还失败！');window.location.href='member_borrow_list.php'; </script>");
            } else die("<script>alert('查无此管理员');window.location.href='member_item_return.php?item_id={$item_id}'; </script>");
        }
    } else die("<script>alert('请求参数不完整');window.location.href='member_item_return.php'; </script>");
}
