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
include('function_lib.php');


$delid = $_GET['id'];
$sqla = "SELECT state a from item_info where item_id={$delid};";
$resa = mysqli_query($dbc, $sqla);
$resulta = mysqli_fetch_array($resa);

if ($resulta['a'] == 1) {
    $sql = "DELETE from item_info where item_id={$delid} ;";
    $res = mysqli_query($dbc, $sql);

    if ($res == 1) {
        echo "<script>alert('删除成功！')</script>";
        echo "<script>window.location.href='admin_item.php'</script>";
    } else {
        echo "删除失败！";
        echo "<script>window.location.href='admin_item.php'</script>";
    }
} else {
    echo "<script>alert('不能删除该物资！')</script>";
    echo "<script>window.location.href='admin_item.php'</script>";
}

?>