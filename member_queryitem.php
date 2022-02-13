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
    <title>SCU Maker物资管理系统 || 物资查询</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        #resitem {
            top: 50%;

        }

        #query {

            text-align: center;
        }

        body {
            width: 100%;

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
                    <li class="active"><a href="member_queryitem.php">物资查询</a></li>
                    <li><a href="member_borrow_list.php">我的借用</a></li>
                    <li><a href="member_reservation_list.php">我的预约</a></li>
                    <li><a href="member_info.php">个人信息</a></li>
                    <li><a href="index.php">退出</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <h3 style="text-align: center"><?php echo $result['name'];  ?>同学，你好</h3><br />
    <h4 style="text-align: center">物资查询：</h4>


    <form action="member_queryitem.php" method="POST">
        <div id="query">
            <label><input autofocus name="itemquery" type="text" placeholder="模糊查询物品名" class="form-control"></label>
            <label><input type="submit" value="查询" class="btn btn-primary"></label>
        </div>
    </form>
    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $keywords = $_POST["itemquery"];
        if ($keywords == "") echo "<script>alert('关键词不能为空！')</script>";
        else {
            $sqla = "SELECT item_id,name,remarks,price,stock_date,class_info.class_name,shelf_id,state from item_info,class_info where item_info.class_id=class_info.class_id and name like '%{$keywords}%';";

            $resa = mysqli_query($dbc, $sqla);
            $jgs = mysqli_num_rows($resa);

            if ($jgs == 0)  echo "<script>alert('系统内暂无此物资！')</script>";
            else {
                echo "<table   id='resitem' class='table'>
            <tr>
                <th>物资号/条码号</th>
                <th>物资名</th>
                <th>备注</th>
                <th>价格（丢失或损坏照价赔偿）</th>
                <th>采购时间</th>
                <th>类型</th>
                <th>存放货架号</th>
                <th>状态</th>
                <th>操作</th>
                <th>操作</th>
            </tr>";
                foreach ($resa as $row) {
                    if ($row['state'] == 1) echo "<tr class='success'>";
                    else if ($row['state'] == 2) echo "<tr class='info'>";
                    else echo "<tr class='warning'>";
                    echo "<td>{$row['item_id']}</td>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['remarks']}</td>";
                    echo "<td>{$row['price']}</td>";
                    echo "<td>{$row['stock_date']}</td>";
                    echo "<td>{$row['class_name']}</td>";
                    echo "<td>{$row['shelf_id']}</td>";
                    if ($row['state'] == 1) echo "<td>在架上</td>";
                    else if ($row['state'] == 0) echo "<td>已借出</td>";
                    else if ($row['state'] == 2) echo "<td>已被预约</td>";
                    else echo "<td>无状态信息</td>";
                    if ($row['state'] == 1) {
                        echo "<td><a href='member_item_borrow.php?item_id={$row['item_id']}'>借用</a></td>";
                        echo "<td><a href='member_reserve_action.php?action=reserve&itemid={$row['item_id']}'>预约</a></td>";
                    }
                    else echo "<td></td><td></td>";
                    echo "</tr>";
                };
            };



            echo "</table>";
        }
    }
    ?>
</body>

</html>