<?php
session_start();
if (isset($_SESSION['userid'])) unset($_SESSION['userid']);
if (isset($_SESSION['is_adm'])) unset($_SESSION['is_adm']);

include('mysqli_connect.php');
include('function_lib.php');
include('function_lib.php');

?>
<!DOCTYPE html>
<html lang="zh-Hans-CN">

<head>
    <meta charset="UTF-8">
    <title>SCU Maker物资管理系统 || 注册页面</title>
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
    <h1 style="text-align: center"><strong>SCU Maker物资管理系统 | 用户注册</strong></h1>

    <div style="padding: 10px 500px 10px;">
        <form action="member_register.php" method="POST" style="text-align: center" class="bs-example bs-example-form" role="form">
            <div id="reg">
                <div class="input-group"><span class="input-group-addon">学号</span><input name="student_id" type="text" placeholder="请输入学号" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">姓名</span><input name="name" type="text" placeholder="请输入姓名" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">学院</span><input name="college" type="text" placeholder="请输入学院" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">电话</span><input name="phone" type="text" placeholder="请输入电话" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">卡号</span><input name="card_uid" type="text" placeholder="请刷卡获取一卡通UID" class="form-control"></div><br />
                <label><input type="submit" value="确认" class="btn btn-primary"></label>
                <label><input type="reset" value="重置" class="btn btn-secondary"></label>
            </div>
        </form>
    </div>
    <?php
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $stuid = replaceSpecialChar($_POST["student_id"]);
        $nnam = replaceSpecialChar($_POST["name"]);
        $ncoll = replaceSpecialChar($_POST["college"]);
        $nphone = replaceSpecialChar($_POST["phone"]);
        $nuid = replaceSpecialChar($_POST["card_uid"]);
        //TODO:加入学号校验
        if (isset($stuid) && isset($nnam) && isset($ncoll) && isset($nphone) && isset($nuid)) {
            $sqla = "INSERT INTO member_info(student_id,name,college,phone,card_uid,card_state,wx_openid) VALUES ('{$stuid}','{$nnam}','{$ncoll}','{$nphone}','{$nuid}',1,NULL)";
            $resa = mysqli_query($dbc, $sqla);

            if ($resa == 1) {
                echo "<script>alert('注册成功，请登录');window.location.href='index.php'</script>";
            } else {
                echo "<script>alert('写入数据库失败，注册失败！');</script>";
            }
        } else echo "<script>alert('参数不完整，注册失败！');</script>";
    }


    ?>
</body>

</html>