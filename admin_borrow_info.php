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
                    <li class="active"><a href="admin_borrow_info.php">借还管理</a></li>
                    <li><a href="admin_reservation_info.php">预约管理</a></li>
                    <li><a href="admin_repass.php">密码修改</a></li>
                    <li><a href="index.php">退出</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <h1 style="text-align: center"><strong>借还管理</strong></h1>
    <form id="query" action="admin_borrow_info.php" method="GET">
        <div id="query">
            <label><input name="keywords" type="text" placeholder=<?php if (!empty($keywords)) echo "{$keywords}";
                                                                    else echo "输入物资名,物资号或会员证号"; ?> class="form-control"></label>
            <input type="submit" value="查询" class="btn btn-primary">
            <button action="?" class="btn btn-secondary">重置</button>
        </div>
    </form>
    <div class="panel panel-default" style="padding: 5px 5px;">
        <table width='100%' class="table table-hover table-striped">
            <tr>
                <th>借物流水号</th>
                <th>物资号</th>
                <th>物资名</th>
                <th>会员号</th>
                <th>会员名</th>
                <th>借出日期</th>
                <th>应还日期</th>
                <th>实还日期</th>
                <th>归还状态</th>
                <th>是否超期</th>
                <th>借出经手人</th>
                <th>归还经手人</th>
            </tr>
            <?php

            $page = (int)$_GET['page'];
            $page = isset($page) && $page > 0 ? $page : 1;
            $show_num = (int)$_GET['show_num'];
            $show_num = isset($show_num) && $show_num > 0 ? $show_num : 20;
            $start_offset = ($page - 1) * $show_num;

            if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($keywords)) {
                $sql = "SELECT * FROM (SELECT sernum,tmp.item_id,item_info.name AS item_name,tmp.student_id,member_info.name AS member_name,lend_admin_id,recover_admin_id,lend_date,due_date,back_date,admin.admin_name AS lend_admin_name,tmp.admin_name AS recover_admin_name 
                  FROM (SELECT * FROM lend_list LEFT JOIN admin ON admin.admin_student_id=recover_admin_id) AS tmp, admin,member_info,item_info
                  WHERE tmp.lend_admin_id=admin.admin_student_id AND member_info.student_id=tmp.student_id AND tmp.item_id=item_info.item_id) AS tmp2 WHERE item_name like '%{$keywords}%'or student_id='{$keywords}' or item_id='{$keywords}'
                  ORDER BY sernum LIMIT $start_offset, $show_num";
                $sql0 = "SELECT COUNT(*) total FROM (SELECT sernum,tmp.item_id,item_info.name AS item_name,tmp.student_id,member_info.name AS member_name,lend_admin_id,recover_admin_id,lend_date,due_date,back_date,admin.admin_name AS lend_admin_name,tmp.admin_name AS recover_admin_name 
                  FROM (SELECT * FROM lend_list LEFT JOIN admin ON admin.admin_student_id=recover_admin_id) AS tmp, admin,member_info,item_info
                  WHERE tmp.lend_admin_id=admin.admin_student_id AND member_info.student_id=tmp.student_id AND tmp.item_id=item_info.item_id) AS tmp2 WHERE item_name like '%{$keywords}%'or student_id='{$keywords}' or item_id='{$keywords}'";
            } else {
                $sql = "SELECT sernum,tmp.item_id,item_info.name AS item_name,tmp.student_id,member_info.name AS member_name,lend_admin_id,recover_admin_id,lend_date,due_date,back_date,admin.admin_name AS lend_admin_name,tmp.admin_name AS recover_admin_name 
                  FROM (SELECT * FROM lend_list LEFT JOIN admin ON admin.admin_student_id=recover_admin_id) AS tmp, admin,member_info,item_info
                  WHERE tmp.lend_admin_id=admin.admin_student_id AND member_info.student_id=tmp.student_id AND tmp.item_id=item_info.item_id
                  ORDER BY sernum LIMIT $start_offset, $show_num";
                $sql0 = "SELECT COUNT(*) total FROM (SELECT sernum,tmp.item_id,item_info.name AS item_name,tmp.student_id,member_info.name AS member_name,lend_admin_id,recover_admin_id,lend_date,due_date,back_date,admin.admin_name AS lend_admin_name,tmp.admin_name AS recover_admin_name 
                  FROM (SELECT * FROM lend_list LEFT JOIN admin ON admin.admin_student_id=recover_admin_id) AS tmp, admin,member_info,item_info
                  WHERE tmp.lend_admin_id=admin.admin_student_id AND member_info.student_id=tmp.student_id AND tmp.item_id=item_info.item_id) AS tmp2";
            }
            $res = mysqli_query($dbc, $sql);
            $res0 = mysqli_query($dbc, $sql0);
            
            $previous_page = ($page == 1) ? 0 : ($page - 1);
            $next_page = ($page == $last_page) ? 1 : ($page + 1);
            $last_page = ceil(mysqli_fetch_array($res0)['total'] / $show_num);

            foreach ($res as $row) {
                if (empty($row['back_date']) && date("Y-m-d") <= $row['due_date']) echo "<tr class='info'>";
                else if (empty($row['back_date']) && date("Y-m-d") > $row['due_date']) echo "<tr class='danger'>";
                else echo "<tr class='success'>";
                echo "<td>{$row['sernum']}</td>";
                echo "<td>{$row['item_id']}</td>";
                echo "<td>{$row['item_name']}</td>";
                echo "<td>{$row['student_id']}</td>";
                echo "<td>{$row['member_name']}</td>";
                echo "<td>{$row['lend_date']}</td>";
                echo "<td>{$row['due_date']}</td>";
                echo "<td>{$row['back_date']}</td>";
                echo "<td>";
                if ($row['back_date'] != null) echo "已归还</td>";
                else echo "未归还</td>";
                echo "<td>";
                if (date("Y-m-d") > $row['due_date']) echo "已超期</td>";
                else echo "未超期</td>";
                echo "<td>{$row['lend_admin_name']}</td>";
                echo "<td>{$row['recover_admin_name']}</td>";
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