<?php
session_start();
if (!isset($_SESSION['is_adm']) || $_SESSION['is_adm'] != true) {
    die("<script>alert('你还未登录');window.location='index.php';</script>;");
}
$userid = $_SESSION['userid'];
include('mysqli_connect.php');
include('function_lib.php');
$item_id = $_GET['item_id'];

$sqlb = "SELECT item_id,name,remarks,price,stock_date,class_id,shelf_id,state from item_info where item_id={$item_id}";
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
    <title>SCU Maker物资管理系统 || 物资信息修改</title>
</head>

<body>
    <h1 style="text-align: center"><strong>物资信息修改</strong></h1>
    <div style="padding: 10px 500px 10px;">
        <form action="admin_item_edit.php?id=<?php echo $xgid; ?>" method="POST" style="text-align: center" class="bs-example bs-example-form" role="form">
            <div id="login">
                <div class="input-group"><span class="input-group-addon">物资名</span><input value="<?php echo $resultb['nname']; ?>" name="nname" type="text" placeholder="请输入修改的物资名" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">备注</span><input value="<?php echo $resultb['nremarks']; ?>" name="nremarks" type="text" placeholder="请输入新的备注" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">价格</span><input value="<?php echo $resultb['nprice']; ?>" name="nprice" type="text" placeholder="请输入修改的价格" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">采购日期</span><input value="<?php echo $resultb['nstockdate']; ?>" name="nstkdate" type="text" placeholder="请输入修改的采购日期" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">分类号</span><input value="<?php echo $resultb['nclass_id']; ?>" name="nclass_id" type="text" placeholder="请输入修改的分类号" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">货架号</span><input value="<?php echo $resultb['nshelf_id']; ?>" name="npressmark" type="text" placeholder="请输入修改的货架号" class="form-control"></div><br />
                <div class="input-group"><span class="input-group-addon">物资状态</span><input value="<?php echo $resultb['nstate']; ?>" name="nstate" type="text" placeholder="请输入修改的物资状态" class="form-control"></div><br />
                <label><input type="submit" value="确认" class="btn btn-primary"></label>
                <label><input type="reset" value="重置" class="btn btn-secondary"></label>
            </div>
        </form>
    </div>
    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $itemid = $_GET['id'];
        $nnam = $_POST["nname"];
        $nrem = $_POST["nremarks"];
        $npri = $_POST["nprice"];
        $nstkd = $_POST["nstockdate"];
        $ncla = $_POST["nclass_id"];
        $npre = $_POST["nshelf_id"];
        $nsta = $_POST["nstate"];



        $sqla = "update item_info set name='{$nnam}',remarks='{$nrem}',price='{$npri}',stock_date='{$nstkd}',
class_id={$ncla},state={$nsta} where item_id=$itemid;";
        $resa = mysqli_query($dbc, $sqla);


        if ($resa == 1) {

            echo "<script>alert('修改成功！')</script>";
            echo "<script>window.location.href='admin_item.php'</script>";
        } else {
            echo "<script>alert('修改失败！请重新输入！');</script>";
        }
    }


    ?>
</body>

</html>