<?php
session_start();
if (!isset($_SESSION['is_adm']) || $_SESSION['is_adm'] != true) {
    die("<script>alert('你还未登录');window.location='index.php';</script>;");
}
$userid = $_SESSION['userid'];
include('mysqli_connect.php');
include('function_lib.php');

$studentid = $_GET['id'];
$sqlb = "SELECT * from class_info";
$resb = mysqli_query($dbc, $sqlb);
$resultb = mysqli_fetch_array($resb);
?>
<!DOCTYPE html>
<html lang="zh-Hans-CN">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <title>SCU Maker物资管理系统 || 物资分类管理</title>
</head>

<body>

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
                                <li class="active"><a href="admin_item_class_info.php">物资分类管理</a></li>
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


        <h1 style="text-align: center"><strong>物资分类管理</strong></h1><br /><br /><br />
        <div class="panel panel-default" style="margin-left:200px; margin-right:200px; padding: 5px 5px;">
        <table width='100%' class="table table-hover table-bordered" id=query>
            <tr>
                <th>分类号</th>
                <th>分类名</th>
                <th>操作</th>
                <th>操作</th>
            </tr>
            <?php
            $page = (int)$_GET['page'];
            $page = isset($page) && $page > 0 ? $page : 1;
            $show_num = (int)$_GET['show_num'];
            $show_num = isset($show_num) && $show_num > 0 ? $show_num : 20;
            $start_offset = ($page - 1) * $show_num;

            $sql = "SELECT class_id,class_name FROM class_info";
            $sql0 = "SELECT COUNT(*) total FROM class_info";
            
            $res = mysqli_query($dbc, $sql);
            $res0 = mysqli_query($dbc, $sql0);

            $previous_page = ($page == 1) ? 0 : ($page - 1);
            $next_page = ($page == $last_page) ? 1 : ($page + 1);
            $last_page = ceil(mysqli_fetch_array($res0)['total'] / $show_num);

            foreach ($res as $row) {
                if ($row['state'] == 1) echo "<tr class='success'>";
                else if ($row['state'] == 2) echo "<tr class='info'>";
                else echo "<tr class='warning'>";
                echo "<td>{$row['class_id']}</td>";
                echo "<td>{$row['class_name']}</td>";
            
                echo "<td><a href='admin_item_class_info.php?id={$row['item_id']}'><button class='btn btn-info'>修改</button></a></td>";
                if ($row['state'] == 1) echo "<td><button class='btn btn-danger'>删除</button></td>";
                else echo "<td><button disabled class='btn btn-default'>删除</button></td>"; //todo:confirm
                echo "</tr>";
            }
            ?>
        </table>
    </div>
        <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $class_name = replaceSpecialChar($_POST["class_name"]);
            $class_id = replaceSpecialChar($_POST["class_id"]);


            $sqla = "UPDATE class_info SET class_name='{$class_name}' WHERE class_id={$userid}";
            $resa = mysqli_query($dbc, $sqla);

            if ($resa == 1) {

                echo "<script>alert('修改成功！')</script>";
                echo "<script>window.location.href='admin_item_class_info.php'</script>";
            } else {
                echo "<script>alert('修改失败！请重新输入！');</script>";
            }
        }


        ?>
    </body>

</html>