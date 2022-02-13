<?php
session_start();
$userid = $_SESSION['userid'];
if (!isset($userid))
    die("<script>alert('你还未登录');window.location='index.php';</script>");
include('mysqli_connect.php');
include('function_lib.php');

$item_id = $_GET['item_id'];

$sql = "SELECT name from member_info where student_id={$userid}";
$res = mysqli_query($dbc, $sql);
$result = mysqli_fetch_array($res);
?>
<!DOCTYPE html>
<html lang="zh-Hans-CN">

<head>
    <meta charset="UTF-8">
    <title>SCU Maker物资管理系统 || 借用物资</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>

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
                    <li class="active"><a href="member_borrow_list.php">我的借用</a></li>
                    <li><a href="member_reservation_list.php">我的预约</a></li>
                    <li><a href="member_info.php">个人信息</a></li>
                    <li><a href="index.php">退出</a></li>
                </ul>
            </div>
        </div>
    </nav>
    </br>
    <h2 style="text-align: center">今天想借用点什么呢？</h2>
    <div style="padding: 100px 550px 10px;text-align: center">
        <form action="member_item_action.php?action=borrow" method="POST" class="bs-example bs-example-form" role="form">
            <div id="borrow">
                <div class="input-group"><span class="input-group-addon">管理员卡号</span><input autofocus name="adminuid" type="text" placeholder="请刷卡获取卡号" class="form-control"></div><br>
                <div class="input-group"><span class="input-group-addon">物品条码</span><input name="item_id" type="text" placeholder=<?php if (isset($item_id)) echo $item_id;
                                                                                                                                    else echo '请扫描条码' ?> <?php if (isset($_GET["item_id"])) echo 'value=' . $_GET["item_id"] ?> class="form-control"></div><br>
                <input type="submit" value="借用" class="btn btn-primary">
            </div>
        </form>
    </div>
</body>

</html>