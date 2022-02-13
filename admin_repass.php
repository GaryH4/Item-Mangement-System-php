<?php
session_start();
if(!isset($_SESSION['is_adm']) || $_SESSION['is_adm']!=true){
    die("<script>alert('你还未登录');window.location='index.php';</script>;");
}
include ('mysqli_connect.php');
$userid=$_SESSION['userid'];
$sql="SELECT name from admin where student_id={$userid}";
$res=mysqli_query($dbc,$sql);
$result=mysqli_fetch_array($res);
?>

<!DOCTYPE html>
<html lang="zh-Hans-CN">
<head>
    <meta charset="UTF-8">
    <title>SCU Maker物资管理系统 || 管理员密码修改</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <style>
        body{
            width: 100%;
            overflow: hidden;
            background: url("background.jpg") no-repeat;
            background-size:cover;
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
                <li ><a href="admin_index.php">管理员主页</a></li>
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
                        <li><a href="admin_item_add.php">增加会员</a></li>
                    </ul>
                </li>
                <li><a href="admin_borrow_info.php">借还管理</a></li>
                <li><a href="admin_reservation_info.php">预约管理</a></li>
                <li class="active"><a href="admin_repass.php" >密码修改</a></li>
                <li><a href="index.php">退出</a></li>
            </ul>
        </div>
    </div>
</nav>


<h3 style="text-align: center"><?php echo $userid;  ?>号管理员，你好</h3><br/>
<h4 style="text-align: center">修改你的密码：</h4><br/><br/>
<form action="admin_repass.php" method="post"  style="text-align: center">
    <label><input type="password" name="pass1" placeholder="请输入新的密码" class="form-control"></label><br/><br/>
    <label><input type="password" name="pass2" placeholder="确认新的密码" class="form-control"></label><br/><br/>
    <input type="submit" value="提交" class="btn btn-primary">
    <input type="reset" value="重置"  class="btn btn-secondary">
</form>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $passa = $_POST["pass1"];
    $passb = $_POST["pass2"];
    $pass_hash = sha1($passa);
    if($passa==$passb){
        $sql="update admin set password='{$pass_hash}' where admin_id={$userid}";
        $res=mysqli_query($dbc,$sql);
        if($res==1)
        {
            echo "<script>alert('密码修改成功！请重新登录！')</script>";
            echo "<script>window.location.href='index.php'</script>";
        }

    }
    else{
        echo "<script>alert('两次输入密码不同，请重新输入！')</script>";

    }

}


?>
</body>
</html>