<!DOCTYPE html>
<html lang="zh-Hans-CN">

<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>

<body>

</body>

</html>
<?php
session_start();
if (!isset($_SESSION['is_adm']) || $_SESSION['is_adm'] != true) {
    die("<script>alert('你还未登录');window.location='index.php';</script>;");
}
$userid = $_SESSION['userid'];
include('mysqli_connect.php');
include('security_lib.php');


$delid = $_GET['id'];
$sqla = "SELECT count(*) a from lend_list where student_id={$delid} and back_date is NULL;";
$resa = mysqli_query($dbc, $sqla);
$resulta = mysqli_fetch_array($resa);

$sqlb = "SELECT count(*) b from reservation_list where student_id={$delid};";
$resb = mysqli_query($dbc, $sqlb);
$resultb = mysqli_fetch_array($resb);

if ($resulta['a'] == 0 && $resultb['b'] == 0) {
    $sqla = "delete  from member_info where student_id={$delid} ;";
    $resa = mysqli_query($dbc, $sqla);

    if ($resa == 1) {
        echo "<script>alert('删除成功！')</script>";
        echo "<script>window.location.href='admin_member.php'</script>";
    } else {
        echo "删除失败！";
        echo "<script>window.location.href='admin_member.php'</script>";
    }
} else {
    echo "<script>alert('有预约或未归还记录，不能删除该会员！')</script>";
    echo "<script>window.location.href='admin_member.php'</script>";
}

?>