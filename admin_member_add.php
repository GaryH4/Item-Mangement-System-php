<?php
session_start();
if (!isset($_SESSION['is_adm']) || $_SESSION['is_adm'] != true) {
    die("<script>alert('你还未登录');window.location='index.php';</script>;");
}
$userid = $_SESSION['userid'];
include('mysqli_connect.php');
include('function_lib.php');


?>
<!DOCTYPE html>
<html lang="zh-Hans-CN">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <title>SCU Maker物资管理系统 || 增加会员</title>
</head>

<body>
    <nav class="navbar navbar-default navbar-static-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">SCU Maker物资管理系统</a>
            </div>
            <div>
                <ul class="nav navbar-nav">
                    <li><a href="admin_index.php">管理员主页</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">物资管理<b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="admin_item.php">全部物资</a></li>
                            <li><a href="admin_item_add.php">增加物资</a></li>
                            <li><a href="admin_item_batch_add.php">批量增加物资</a></li>
                            <li><a href="admin_item_class_info.php">物资分类管理</a></li>
                        </ul>
                    </li>
                    <li class="active" class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">会员管理<b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="admin_member.php">全部会员</a></li>
                            <li class="active"><a href="admin_member_add.php">增加会员</a></li>
                        </ul>
                    </li>
                    <li><a href="admin_borrow_info.php">借还管理</a></li>
                    <li><a href="admin_reservation_info.php">预约管理</a></li>
                    <li><a href="admin_repass.php">密码修改</a></li>
                    <li><a href="index.php">退出</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <h1 style="text-align: center"><strong>增加会员</strong></h1>
    <div style="padding: 10px 500px 10px;">
        <form action="admin_member_add.php" method="POST" style="text-align: center" class="bs-example bs-example-form" role="form">
            <div id="login">
                <div class="input-group"><span class="input-group-addon">学号</span><input name="nid" type="text" placeholder="请输入学号" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">姓名</span><input name="nname" type="text" placeholder="请输入姓名" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">学院</span><input name="ncollege" type="text" placeholder="请输入学院" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">电话</span><input name="nphone" type="text" placeholder="请输入电话号码" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">一卡通UID</span><input name="ncard_uid" type="text" placeholder="请刷卡获取一卡通UID" class="form-control"></div><br />
                <input type="submit" value="添加" class="btn btn-primary">
                <input type="reset" value="重置" class="btn btn-secondary">
            </div>
        </form>
    </div>
    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nnid = $_POST["nid"];
        $nnam = $_POST["nname"];
        $ncollege = $_POST["ncollege"];
        $nphone = $_POST["nphone"];
        $nadd = $_POST["ncard_uid"];


        $sqla = "insert into member_info VALUES ($nnid ,'{$nnam}','{$ncollege}','{$nphone}','{$nadd}')";

        $resa = mysqli_query($dbc, $sqla);


        if ($resa == 1) {

            echo "<script>alert('会员添加成功！')</script>";
            echo "<script>window.location.href='admin_member.php'</script>";
        } else {
            echo "<script>alert('添加失败！请重新输入！');</script>";
        }
    }


    ?>
</body>

</html>