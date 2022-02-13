<?php
session_start();
$userid = $_SESSION['userid'];
if (!isset($userid))
    die("<script>alert('你还未登录');window.location='index.php';</script>");
include('mysqli_connect.php');
include('function_lib.php');



$sqlb = "SELECT * from member_info where student_id={$userid} ;";
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
    <title>SCU Maker物资管理系统 || 个人信息修改</title>
</head>

<body>
    <h1 style="text-align: center"><strong>个人信息修改</strong></h1>
    <div style="padding: 10px 500px 10px;">
        <form action="member_info_edit.php" method="POST" style="text-align: center" class="bs-example bs-example-form" role="form">
            <div id="edit">
                <div class="input-group"><span class="input-group-addon">学号</span><input value="<?php echo $userid; ?>" type="text" placeholder="" class="form-control" disabled></div><br />
                <div class="input-group"><span class="input-group-addon">姓名</span><input value="<?php echo $resultb['name']; ?>" name="name" type="text" placeholder="请输入修改的姓名" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">学院</span><input value="<?php echo $resultb['college']; ?>" name="college" type="text" placeholder="请输入修改的学院" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">电话</span><input value="<?php echo $resultb['phone']; ?>" name="phone" type="text" placeholder="请输入修改的电话" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">卡号</span><input value="<?php echo $resultb['card_uid']; ?>" name="card_uid" type="text" placeholder="请刷卡获取新的的一卡通UID" class="form-control"></div><br />
                <label><input type="submit" value="确认" class="btn btn-primary"></label>
                <label><input type="reset" value="重置" class="btn btn-secondary"></label>
            </div>
        </form>
    </div>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nnam = replaceSpecialChar($_POST["name"]);
        $ncoll = replaceSpecialChar($_POST["college"]);
        $nphone = replaceSpecialChar($_POST["phone"]);
        $nuid = replaceSpecialChar($_POST["card_uid"]);

        if (isset($nnam) && isset($ncoll) && isset($nphone) && isset($nuid)) {
            $sqla = "UPDATE member_info set name='{$nnam}',college='{$ncoll}',phone='{$nphone}',card_uid='{$nuid}' where student_id={$userid};";
            $resa = mysqli_query($dbc, $sqla);

            if ($resa == 1) {
                echo "<script>alert('修改成功！');window.location.href='member_info.php'</script>";
            } else {
                echo "<script>alert('更新数据库失败，修改失败！');</script>";
            }
        } else echo "<script>alert('参数不完整，修改失败！');</script>";
    }


    ?>
</body>

</html>