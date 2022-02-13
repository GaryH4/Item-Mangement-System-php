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
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <!-- Bootstrap Date-Picker Plugin -->
    <script type="text/javascript" src="js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap-datepicker3.css" />

    <title>SCU Maker物资管理系统 || 增加物资</title>
</head>

<body>
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
                            <li><a href="admin_item.php">全部物资</a></li>
                            <li class="active"><a href="admin_item_add.php">增加物资</a></li>
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
    <h1 style="text-align: center"><strong>增加物资</strong></h1>
    <div style="padding: 10px 300px 10px;">
        <form action="admin_item_add.php" method="POST" style="text-align: center" class="bs-example bs-example-form" role="form">
            <div id="item_add">
                <div class="input-group"><span class="input-group-addon">物品名</span><input autofocus name="nname" type="text" placeholder="请输入物品名" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">备注</span><input name="nremarks" type="text" placeholder="请输入备注" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">价格</span><input name="nprice" type="text" placeholder="请输入价格" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">采购日期</span><input id="date" name="nstockdate" type="text" placeholder="YYYY-MM-DD" class="form-control"></div><br />
                <div class="input-group">
                    <span class="input-group-addon">物资分类</span>
                    <select class="form-control" name="nclassid">
                        <option selected>Choose...</option>
                        <?php
                        $sqla = "SELECT class_id,class_name FROM class_info";
                        $resa = mysqli_query($dbc, $sqla);
                        foreach ($resa as $row) {
                            echo "<option value={$row['class_id']}>{$row['class_name']}</option>";
                        }
                        ?>
                    </select>
                </div><br />
                <div class="input-group"><span class="input-group-addon">货架号</span><input name="nshelfid" type="text" placeholder="请输入货架号" class="form-control"></div><br />
                <label><input type="submit" value="添加" class="btn btn-primary"></label>
                <label><input type="reset" value="重置" class="btn btn-secondary"></label>
            </div>
        </form>
    </div>
    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nnam = $_POST["nname"];
        $nrmk = $_POST["nremarks"];
        $npri = $_POST["nprice"];
        $nstkd = $_POST["nstockdate"];
        $ncla = $_POST["nclassid"];
        $nshelf = $_POST["nshelfid"];
        if (isset($nnam) && isset($nrmk) && is_float($npri) && isset($nstkd) && isset($ncla) && isset($nshelf)) {
            
            $sqla = "INSERT INTO item_info(`item_id`,`name`,`remarks`,`price`,`stock_date`,`class_id`,`shelf_id`,`state`) VALUES (NULL ,'{$nnam}','{$nrmk}','{$npri}','{$nstkd}',{$ncla},'{$nshelf}',1 )";
            $resa = mysqli_query($dbc, $sqla);
            if ($resa == 1) {
                echo "<script>alert('添加成功！')</script>";
                echo "<script>window.location.href='admin_item.php'</script>";
            } else {
                echo "<script>alert('添加失败！请重新输入！');</script>";
                echo "<script>window.location.href='admin_item_add.php'</script>";
            }
        } else {
            echo "<script>alert('输入有误，请重新输入！');</script>";
            echo "<script>window.location.href='admin_item_add.php'</script>";
        }
    }

    ?>

    <script>
        $(document).ready(function() {
            var date_input = $('input[name="nstockdate"]'); //our date input has the name "nstockdate"
            var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
            date_input.datepicker({
                format: 'yyyy-mm-dd',
                container: container,
                todayHighlight: true,
                autoclose: true,
            })
        })
    </script>
</body>

</html>