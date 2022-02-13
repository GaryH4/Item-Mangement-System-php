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
    <title>SCU Maker物资管理系统 || 物资管理</title>
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

        #page_button {
            margin: 5px;
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
                    <li class="active" class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">物资管理<b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="active"><a href="admin_item.php">全部物资</a></li>
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
    <h1 style="text-align: center"><strong>全部物资</strong></h1>
    <form id="query" action="admin_item.php" method="GET">
        <div id="query">
            <label><input name="keywords" type="text" placeholder=<?php if (isset($keywords) && !empty($keywords)) echo "{$keywords}";
                                                                    else echo "输入物资名或物资号"; ?> class="form-control"></label>
            <input type="submit" value="查询" class="btn btn-primary">
            <button action='?' class="btn btn-secondary">重置</button>
        </div>
    </form>

    <div class="panel panel-default" style="padding: 5px 5px;">
        <table width='100%' class="table table-hover table-bordered" id=query>
            <tr>
                <th>物资条码号</th>
                <th>物资名</th>
                <th>备注</th>
                <th>价格</th>
                <th>采购日期</th>
                <th>分类</th>
                <th>货架号</th>
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
                $sql = "SELECT item_id,name,remarks,price,stock_date,item_info.class_id,class_name,shelf_id,state from item_info,class_info where item_info.class_id=class_info.class_id and ( name like '%{$keywords}%' or item_id like '%{$keywords}%') LIMIT $start_offset, $show_num;";
                $sql0 = "SELECT COUNT(*) total FROM item_info WHERE (name like '%{$keywords}%' or item_id like '%{$keywords}%')";
            } else {
                $sql = "SELECT item_id,name,remarks,price,stock_date,item_info.class_id,class_name,shelf_id,state from item_info,class_info where item_info.class_id=class_info.class_id LIMIT $start_offset, $show_num;";
                $sql0 = "SELECT COUNT(*) total FROM item_info";
            }
            $res = mysqli_query($dbc, $sql);
            $res0 = mysqli_query($dbc, $sql0);

            $previous_page = ($page == 1) ? 0 : ($page - 1);
            $next_page = ($page == $last_page) ? 1 : ($page + 1);
            $last_page = ceil(mysqli_fetch_array($res0)['total'] / $show_num);

            foreach ($res as $row) {
                if ($row['state'] == 1) echo "<tr class='success'>";
                else if ($row['state'] == 2) echo "<tr class='info'>";
                else echo "<tr class='warning'>";
                echo "<td>{$row['item_id']}</td>";
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['remarks']}</td>";
                echo "<td>{$row['price']}</td>";
                echo "<td>{$row['stock_date']}</td>";
                echo "<td>[{$row['class_id']}] {$row['class_name']}</td>";
                echo "<td>{$row['shelf_id']}</td>";
                if ($row['state'] == 1) echo "<td>在架上</td>";
                else if ($row['state'] == 0) echo "<td>已借出</td>";
                else if ($row['state'] == 2) echo "<td>被预订</td>";
                else  echo "<td>无状态信息</td>";
                echo "<td><a href='admin_item_edit.php?id={$row['item_id']}'><button class='btn btn-info'>修改</button></a></td>";
                echo "<td><a href='admin_lable_print.php?item_id={$row['item_id']}'><button class='btn btn-info'>打印条码</button></a></td>";
                if ($row['state'] == 1) echo "<td><button class='btn btn-danger'>删除</button></td>";
                else echo "<td><button disabled class='btn btn-default'>删除</button></td>"; //todo:confirm
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
        <!-- TODO:导出为excel -->
    </div>
    </div>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($_POST['action'] == 'delete') {
            echo "<script>confirm('删除成功！')</script>";
            $delid = $_GET['id'];
            $sqla = "SELECT state a from item_info where item_id={$delid};";
            $resa = mysqli_query($dbc, $sqla);
            $resulta = mysqli_fetch_array($resa);

            if ($resulta['a'] == 1) {
                $sql = "DELETE from item_info where item_id={$delid} ;";
                $res = mysqli_query($dbc, $sql);

                if ($res == 1) {
                    echo "<script>alert('删除成功！')</script>";
                    echo "<script>window.location.href='admin_item.php'</script>";
                } else {
                    echo "删除失败！";
                    echo "<script>window.location.href='admin_item.php'</script>";
                }
            } else {
                echo "<script>alert('不能删除该物资！')</script>";
                echo "<script>window.location.href='admin_item.php'</script>";
            }
        }
    }
    ?>
    <script type="text/javascript">
        function getConfirmation() {
            var retVal = confirm("Do you want to continue ?");
            if (retVal == true) {
                document.write("User wants to continue!");
                return true;
            } else {
                document.write("User does not want to continue!");
                return false;
            }
        }
    </script>
</body>

</html>