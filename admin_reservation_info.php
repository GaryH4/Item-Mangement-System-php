<?php
session_start();
if (!isset($_SESSION['is_adm']) || $_SESSION['is_adm'] != true) {
    die("<script>alert('你还未登录');window.location='index.php';</script>;");
}
$userid = $_SESSION['userid'];
include('mysqli_connect.php');
include('function_lib.php');
date_default_timezone_set("PRC");
if (!isset($_SESSION['is_adm']) || $_SESSION['is_adm'] != true) {
    die("<script>alert('你还未登录');window.location='index.php';</script>;");
}
?>

<!DOCTYPE html>
<html lang="zh-Hans-CN">

<head>
    <meta charset="UTF-8">
    <title>SCU Maker物资管理系统 || 借用信息</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        body {
            width: 100%;
            height: auto;

        }

        #query {
            text-align: center;
        }
    </style>
</head>

<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "GET") $keywords = replaceSpecialChar($_GET["keywords"]);
    ?>
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
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">会员管理<b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="admin_member.php">全部会员</a></li>
                            <li><a href="admin_member_add.php">增加会员</a></li>
                        </ul>
                    </li>
                    <li><a href="admin_borrow_info.php">借还管理</a></li>
                    <li class="active"><a href="admin_reservation_info.php">预约管理</a></li>
                    <li><a href="admin_repass.php">密码修改</a></li>
                    <li><a href="index.php">退出</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <h1 style="text-align: center"><strong>预约管理</strong></h1>
    <form id="query" action="admin_reservation_info.php" method="GET">
        <div id="query">
            <label><input name="keywords" type="text" placeholder=<?php if (!empty($keywords)) echo "{$keywords}";
                                                                    else echo "输入物资名,物资号或学生卡号" ?> class="form-control"></label>
            <input type="submit" value="查询" class="btn btn-primary">
            <button action="?" class="btn btn-secondary">重置</button>
        </div>
    </form>
    <div class="panel panel-default" style="padding: 5px 5px;">
        <table width='100%' class="table table-hover table-striped">
            <tr>
                <th>预约流水号</th>
                <th>物资号</th>
                <th>物资名</th>
                <th>会员号</th>
                <th>预约时间</th>
                <th>保留时间</th>
                <th>操作</th>
            </tr>
            <?php

            $page = (int)$_GET['page'];
            $page = isset($page) && $page > 0 ? $page : 1;
            $show_num = (int)$_GET['show_num'];
            $show_num = isset($show_num) && $show_num > 0 ? $show_num : 20;
            $start_offset = ($page - 1) * $show_num;

            if (isset($_GET['action']) && isset($_GET['item_id'])) {
                if ($_GET['action'] == "remove") {
                    $item_id = (int)$_GET['item_id'];
                    $sqla = "UPDATE item_info SET state=1 WHERE item_id={$item_id} AND state=2 LIMIT 1";
                    $resa = mysqli_query($dbc, $sqla);
                    if ($resa == 1) {
                        $sqlb = "DELETE FROM reservation_list WHERE item_id='{$item_id}' LIMIT 1";
                        $resb = mysqli_query($dbc, $sqlb);
                        if ($resb == 1) {
                            echo "<script>alert('删除预约记录成功！');window.location.href='admin_reservation_info.php';</script>";
                        } else echo "<script>alert('删除预约记录失败！$sqla');window.location.href='admin_reservation_info.php';</script>";
                    } else echo "<script>alert('删除预约记录失败！');window.location.href='admin_reservation_info.php';</script>";
                }
            }
            if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($keywords)) {
                $sql = "SELECT sernum,reservation_list.item_id,name,student_id,start_time,due_time FROM item_info,reservation_list
                  WHERE item_info.item_id=reservation_list.item_id and ( name like '%{$keywords}%'or student_id = '{$keywords} 'or lend_list.item_id = '{$keywords}' ) ORDER BY sernum;";
                $sql0 = "SELECT COUNT(*) total FROM (SELECT sernum,reservation_list.item_id,name,student_id,start_time,due_time FROM item_info,reservation_list
                  WHERE item_info.item_id=reservation_list.item_id and ( name like '%{$keywords}%'or student_id = '{$keywords} 'or lend_list.item_id = '{$keywords}') ) AS tmp";
            } else {
                $sql = "SELECT sernum,reservation_list.item_id,name,student_id,start_time,due_time FROM item_info,reservation_list
                WHERE item_info.item_id=reservation_list.item_id ORDER BY sernum";
                $sql0 = "SELECT COUNT(*) total FROM (SELECT sernum,reservation_list.item_id,name,student_id,start_time,due_time FROM item_info,reservation_list
                  WHERE item_info.item_id=reservation_list.item_id) AS tmp";
            }

            $res = mysqli_query($dbc, $sql);
            $res0 = mysqli_query($dbc, $sql0);

            $previous_page = ($page == 1) ? 0 : ($page - 1);
            $next_page = ($page == $last_page) ? 1 : ($page + 1);
            $last_page = ceil(mysqli_fetch_array($res0)['total'] / $show_num);

            foreach ($res as $row) {
                echo "<tr>";
                echo "<td>{$row['sernum']}</td>";
                echo "<td>{$row['item_id']}</td>";
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['student_id']}</td>";
                echo "<td>{$row['start_time']}</td>";
                echo "<td>{$row['due_time']}</td>";
                echo "<td><a href='admin_reservation_info.php?action=remove&item_id={$row['item_id']}'><button class='btn btn-danger'>删除</button></a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
    <div class="text-center">
        <?php
        if ($page == 1) {
            echo "<button disabled class='btn btn-secondary' id='page_button'>首页</button>";
        } else {
            echo "<a href='?page=1&keywords={$keywords}&show_num={$show_num}'><button class='btn btn-secondary' id='page_button'>首页</button></a>";
        }
        if ($previous_page) {
            echo "<a href='?page={$previous_page}&keywords={$keywords}&show_num={$show_num}'><button class='btn btn-primary' id='page_button'>上一页</button></a>";
        } else {
            echo "<button disabled class='btn btn-primary' id='page_button'>上一页</button>";
        }
        echo "<span class='' style='margin: 10px;'>第{$page}页，共{$last_page}页</span>";
        if ($page < $last_page) {
            echo "<a href='?page={$next_page}&keywords={$keywords}&show_num={$show_num}'><button class='btn btn-primary' id='page_button'>下一页</button></a>";
        } else {
            echo "<button disabled class='btn btn-primary' id='page_button'>下一页</button>";
        }
        if ($page == $last_page) {
            echo "<button disabled class='btn btn-secondary' id='page_button'>尾页</button>";
        } else {
            echo "<a href='?page={$last_page}&keywords={$keywords}&show_num={$show_num}'><button class='btn btn-secondary' id='page_button'>尾页</button></a>";
        }
        ?>
        <div class="btn-group" role="group" aria-label="...">
            <button type="text" class="btn btn-default">每页展示</button>
            <div class="btn-group dropup">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo $show_num . '&nbsp'; ?><span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <?php
                    echo "<li><a href='?page=1&keywords={$keywords}&show_num=20'>20</a></li>";
                    echo "<li><a href='?page=1&keywords={$keywords}&show_num=50'>50</a></li>";
                    echo "<li><a href='?page=1&keywords={$keywords}&show_num=100'>100</a></li>";
                    ?>
                </ul>
            </div>
            <button type="button" class="btn btn-default">条记录</button>
        </div>
    </div>
</body>

</html>