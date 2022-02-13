<?php
session_start();
$userid = $_SESSION['userid'];
include('mysqli_connect.php');
include('function_lib.php');
if (!isset($_SESSION['is_adm']) || $_SESSION['is_adm'] != true) {
    die("<script>alert('你还未登录');window.location='index.php';</script>;");
}

?>

<!DOCTYPE html>
<html lang="zh-Hans-CN">

<head>
    <meta charset="UTF-8">
    <title>SCU Maker物资管理系统 || 主页</title>
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
                    <li class="active"><a href="admin_index.php">管理员主页</a></li>
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
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">会员管理<b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="admin_member.php">全部会员</a></li>
                            <li><a href="admin_member_add.php">增加会员</a></li>
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


    <h3 style="text-align: center"><?php echo $userid;  ?>号管理员，你好</h3><br /><br /><br />
    <h4 style="text-align: center"><?php
                                    $sql = "SELECT count(*) a from item_info;";

                                    $res = mysqli_query($dbc, $sql);
                                    $result = mysqli_fetch_array($res);
                                    echo "本系统当前共有物资{$result['a']}件。";
                                    ?>
    </h4>

    <h4 style="text-align: center">
        <?php
        $sqla = "SELECT count(*) total from member_info;";
        $resa = mysqli_query($dbc, $sqla);
        $resulta = mysqli_fetch_array($resa);
        $sqlb = "SELECT count(*) total_expired from member_info WHERE card_state=0;";
        $resb = mysqli_query($dbc, $sqlb);
        $resultb = mysqli_fetch_array($resb);
        echo "共有会员{$resulta['total']}个，";
        echo "超期会员{$resultb['total_expired']}个。";
        ?>
    </h4>

</body>

</html>