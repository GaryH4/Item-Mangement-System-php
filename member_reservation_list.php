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
                    <li><a href="member_borrow_list.php">我的借用</a></li>
                    <li class="active"><a href="member_reservation_list.php">我的预约</a></li>
                    <li><a href="member_info.php">个人信息</a></li>
                    <li><a href="index.php">退出</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <h3 style="text-align: center"><?php echo $result['name'];  ?>同学，你好</h3><br />
    <?php
    $sqla = "SELECT sernum,item_info.item_id,item_info.name,item_info.shelf_id,start_time,due_time from reservation_list,item_info where student_id={$userid} and reservation_list.item_id=item_info.item_id;";
    $resa = mysqli_query($dbc, $sqla);

    if (mysqli_num_rows($resa) > 0) {
        print <<<EOT
    <h4 style="text-align: center">你已预约的物资目如下：</h4>
    <table  width='100%' class="table">
        <tr>
            <th>预约流水号</th>
            <th>物资号/条码号</th>
            <th>物资名</th>
            <th>货架号</th>
            <th>预约时间</th>
            <th>最晚保留至</th>
            <th>操作</th>
            <th>操作</th>
        </tr>
EOT;
        foreach ($resa as $row) {
            echo "<tr>";
            echo "<td>{$row['sernum']}</td>";
            echo "<td>{$row['item_id']}</td>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['shelf_id']}</td>";
            echo "<td>{$row['start_time']}</td>";
            echo "<td>{$row['due_time']}</td>";
            echo "<td><a href='member_item_borrow.php?item_id={$row['item_id']}'><button>去借用</button></a></td>";
            echo "<td><a href='member_reserve_action.php?action=cancel&itemid={$row['item_id']}'><button>取消预约</button></a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo '<h4 style="text-align: center">你当前没有预约的物品</h4>';
    }
    ?>
</body>

</html>