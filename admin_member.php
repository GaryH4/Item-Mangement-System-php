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
    <title>SCU Maker物资管理系统 || 会员管理</title>
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
                    <li class="active" class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">会员管理<b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="active"><a href="admin_member.php">全部会员</a></li>
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
    <h1 style="text-align: center"><strong>全部会员</strong></h1>
    <form id="query" action="admin_member.php" method="GET">
        <div id="query">
            <label><input name="keywords" type="text" placeholder=<?php if (isset($keywords) && !empty($keywords)) echo "{$keywords}";
                                                                        else echo "请输入会员姓名或会员证号" ?> class="form-control"></label>
            <input type="submit" value="查询" class="btn btn-primary">
            <button action='?' class="btn btn-secondary">重置</button>
        </div>
    </form>
    <div class="panel panel-default" style="padding: 5px 5px;">
        <table width='100%' class="table table-hover table-bordered">
            <tr>
                <th>会员证号</th>
                <th>姓名</th>
                <th>学院</th>
                <th>电话号码</th>
                <th>一卡通UID</th>
                <th>状态</th>
                <th>操作</th>
                <th>操作</th>
                <th>操作</th>
            </tr>
            <?php

            $page = (int)$_GET['page'];
            $page = isset($page) && $page > 0 ? $page : 1;
            $show_num = (int)$_GET['show_num'];
            $show_num = isset($show_num) && $show_num > 0 ? $show_num : 20;
            $start_offset = ($page - 1) * $show_num;

            if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($keywords)) {
                $sql = "SELECT student_id, name, college, phone, card_uid, card_state from member_info where name like '%{$keywords}%' OR student_id like '%{$keywords}%' LIMIT $start_offset, $show_num;";
                $sql0 = "SELECT COUNT(*) total FROM member_info where name like '%{$keywords}%' OR student_id like '%{$keywords}%'";
            } else {
                $sql = "SELECT student_id, name, college, phone, card_uid, card_state from member_info LIMIT $start_offset, $show_num";
                $sql0 = "SELECT COUNT(*) total FROM member_info";
            }
            $res = mysqli_query($dbc, $sql);
            $res0 = mysqli_query($dbc, $sql0);

            $previous_page = ($page == 1) ? 0 : ($page - 1);
            $next_page = ($page == $last_page) ? 1 : ($page + 1);
            $last_page = ceil(mysqli_fetch_array($res0)['total'] / $show_num);

            foreach ($res as $row) {
                if ($row['card_state'] == 1) echo "<tr class='success'>";
                else echo "<tr class='danger'>";
                echo "<td>{$row['student_id']}</td>";
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['college']}</td>";
                echo "<td>{$row['phone']}</td>";
                echo "<td>{$row['card_uid']}</td>";
                if ($row['card_state'] == 1) echo "<td>正常</td>";
                else echo "<td>有超期未还</td>";
                echo "<td><a class='btn btn-info' href='admin_borrow_info.php?keywords={$row['student_id']}'>查看借用记录</a></td>";
                echo "<td><a class='btn btn-info' href='admin_member_edit.php?id={$row['student_id']}'>修改</a></td>";
                echo "<td><a class='btn btn-danger' href='admin_member_del.php?id={$row['student_id']}'>删除</a></td>";
                echo "</tr>";
            };

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