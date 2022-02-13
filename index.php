<?php
session_start();
if(isset($_SESSION['userid'])) unset($_SESSION['userid']);
if(isset($_SESSION['is_adm'])) unset($_SESSION['is_adm']);
?>
<!DOCTYPE html>
<html lang="zh-Hans-CN">
<head>
    <meta charset="UTF-8">
    <title>SCU Maker物资管理系统 || 请登录</title>
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
<h1 style="text-align: center"><strong>SCU Maker物资管理系统 | 登录</strong></h1>
<div style="padding: 180px 550px 10px;text-align: center">
    <form  action="login_check.php" method="POST" class="bs-example bs-example-form" role="form">
        <div id="login">
            
            <div class="input-group"><span class="input-group-addon">会员登录</span><input autofocus name="card_uid" type="text" placeholder="请刷卡..." class="form-control"></div><br><br>
            
            <input type="submit" value="登录" class="btn btn-primary">
            <input type="reset" value="重置" class="btn btn-secondary">
        </div>
    </form>
    <br/><br/><br/>
    <button type="button" onClick="window.location.href='member_register.php'"  class="btn btn-default">用户注册</button>
    <button type="button" onClick="window.location.href='admin_login.php'"  class="btn btn-default">管理员登录</button>

</div>
</body>
</html>