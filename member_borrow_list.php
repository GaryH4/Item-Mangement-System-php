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
    <title>SCU Maker物资管理系统 || 我的借用</title>
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
                    <li class="active"><a href="member_borrow_list.php">我的借用</a></li>
                    <li><a href="member_reservation_list.php">我的预约</a></li>
                    <li><a href="member_info.php">个人信息</a></li>
                    <li><a href="index.php">退出</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <h3 style="text-align: center"><?php echo $result['name'];  ?>同学，你好</h3><br />
    <h4 style="text-align: center">你已借用的物资目如下：</h4>

    <table width='100%' class="table table-hover table-bordered">
        <tr>
            <th>借用流水号</th>
            <th>物资号/条码号</th>
            <th>物资名</th>
            <th>借用时间</th>
            <th>应还时间</th>
            <th>归还时间</th>
        </tr>
        <?php
        $sqla = "SELECT sernum,item_info.item_id,lend_list.item_id,item_info.name,lend_date,due_date,back_date from lend_list,item_info where student_id={$userid} and lend_list.item_id=item_info.item_id ORDER BY lend_date DESC;";
        $resa = mysqli_query($dbc, $sqla);
        foreach ($resa as $row) {
            if (empty($row['back_date'])) echo "<tr class='danger'>";
            else echo "<tr>";
            echo "<td>{$row['sernum']}</td>";
            echo "<td>{$row['item_id']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['lend_date']}</td>";
            echo "<td>{$row['due_date']}";
            if (date("Y-m-d") > $row['due_date'] && empty($row['back_date'])) echo "[已超期]</td>";
            else echo "</td>";
            if (empty($row['back_date'])) {
                echo  "<td><a href='member_item_return.php?item_id={$row['item_id']}'><button>去归还</button></a></td>";
            } else echo "<td>{$row['back_date']}</td>";
            echo "</tr>";
        };
        ?>
    </table>
    <br><br>
    <h4 style="text-align: center">
        <button type="button" onClick="window.location.href='member_item_borrow.php'" class="btn btn-default">借用物品</button>
        <button type="button" onClick="window.location.href='member_item_return.php'" class="btn btn-default">归还物品</button>
    </h4>
</body>

</html>