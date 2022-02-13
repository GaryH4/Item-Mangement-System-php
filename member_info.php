<?php
session_start();
$userid = $_SESSION['userid'];
if (!isset($userid))
    die("<script>alert('你还未登录');window.location='index.php';</script>");
include('mysqli_connect.php');
include('function_lib.php');


$sql = "SELECT name from member_info where student_id={$userid}";
$res = mysqli_query($dbc, $sql);
$result = mysqli_fetch_array($res);
?>

<!DOCTYPE html>
<html lang="zh-Hans-CN">

<head>
    <meta charset="UTF-8">
    <title>我的SCU Maker物资管理系统 || 我的信息</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        body {
            width: 100%;
            overflow: hidden;
            background: url("background.jpg") no-repeat;
            background-size: cover;
            color: black;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-default navbar-static-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">SCU Maker物资管理系统</a>
            </div>
            <div>
                <ul class="nav navbar-nav">
                    <li><a href="member_index.php">主页</a></li>
                    <li><a href="member_queryitem.php">物资查询</a></li>
                    <li><a href="member_borrow_list.php">我的借用</a></li>
                    <li><a href="member_reservation_list.php">我的预约</a></li>
                    <li class="active"><a href="member_info.php">个人信息</a></li>
                    <li><a href="index.php">退出</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <?php



    $sqla = "SELECT * from member_info where student_id={$userid} ;";

    $resa = mysqli_query($dbc, $sqla);
    $resulta = mysqli_fetch_array($resa);
    ?>
    <div class="col-xs-5 col-md-offset-3" style="position: relative;top: 25%">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">我的信息</h3>
            </div>
            <div class="panel-body">
                <a href="#" class="list-group-item"><?php echo "<p>会员证号:{$resulta['student_id']}</p><br/>"; ?></a>
                <a href="#" class="list-group-item"><?php echo "<p>姓名:{$resulta['name']}</p><br/>";  ?></a>
                <a href="#" class="list-group-item"><?php echo "<p>学院:{$resulta['college']}</p><br/>"; ?></a>
                <a href="#" class="list-group-item"><?php echo "<p>电话:{$resulta['phone']}</p><br/>";  ?></a>
                <a href="#" class="list-group-item"><?php echo "<p>一卡通UID:{$resulta['card_uid']}</p><br/>";  ?></a>
                <br>
                <button type="button" onClick="window.location.href='member_info_edit.php'" class="btn btn-primary">修改信息</button>
            </div>
        </div>
    </div>


</body>

</html>