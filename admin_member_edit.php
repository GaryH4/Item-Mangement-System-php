<?php
session_start();
if (!isset($_SESSION['is_adm']) || $_SESSION['is_adm'] != true) {
    die("<script>alert('你还未登录');window.location='index.php';</script>;");
}
$userid = $_SESSION['userid'];
include('mysqli_connect.php');
include('function_lib.php');

$studentid=$_GET['id'];
$sqlb = "SELECT * from member_info where student_id={$studentid}";
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
    <title>SCU Maker物资管理系统 || 会员信息修改</title>
</head>

<body>

    <h1 style="text-align: center"><strong>会员信息修改</strong></h1><br /><br /><br />
    <div style="padding: 10px 500px 10px;">
        <form action="admin_member_edit.php?id=<?php echo $studentid; ?>" method="POST" style="text-align: center" class="bs-example bs-example-form" role="form">
            <div id="login">
                <div class="input-group"><span class="input-group-addon">学号</span><input name="nid" value="<?php echo $resultb['student_id'];; ?>" type="text" placeholder="" class="form-control" disabled></div><br />
                <div class="input-group"><span class="input-group-addon">姓名</span><input name="nname" value="<?php echo $resultb['name']; ?>" type="text" placeholder="请输入修改的名字" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">学院</span><input name="ncoll" value="<?php echo $resultb['college']; ?>" type="text" placeholder="请输入修改的学院" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">电话</span><input name="nphone" value="<?php echo $resultb['phone']; ?>" type="text" placeholder="请输入修改的电话号码" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">一卡通UID</span><input name="nuid" value="<?php echo $resultb['card_uid']; ?>" type="text" placeholder="请输入修改的卡片uid" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">卡状态</span><input name="nstate" value="<?php echo $resultb['card_state']; ?>" type="text" placeholder="请输入修改的卡状态，1可用，0不可用" class="form-control"></div><br />
                <input type="submit" value="确认" class="btn btn-primary">
                <input type="reset" value="重置" class="btn btn-secondary">
            </div>
        </form>
    </div>

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $nnam = $_POST["nname"];
        $ncoll = $_POST["ncoll"];
        $nphone = $_POST["nphone"];
        $nuid = $_POST["nuid"];
        $nstate = $_POST["nstate"];



        $sqla = "UPDATE member_info set student_id={$userid},name='{$nnam}', college='{$ncoll}',
        phone='{$nphone}',card_uid='{$nuid}',card_state='{$nstate}' where student_id=$studentid;";
        $resa = mysqli_query($dbc, $sqla);

        if ($resa == 1) {

            echo "<script>alert('修改成功！')</script>";
            echo "<script>window.location.href='admin_member.php'</script>";
        } else {
            echo "<script>alert('修改失败！请重新输入！');</script>";
        }
    }


    ?>
</body>

</html>