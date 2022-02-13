<?php
session_start();
$userid = $_SESSION['userid'];
if (!isset($userid))
    die("<script>alert('你还未登录');window.location='index.php';</script>");
include('mysqli_connect.php');
include('function_lib.php');

$sql = "SELECT name from member_info where student_id={$userid}";
$res = mysqli_query($dbc, $sql);
$result = mysqli_fetch_array($res);
date_default_timezone_set("PRC");
?>

<!DOCTYPE html>
<html lang="zh-Hans-CN">

<head>
    <meta charset="UTF-8">
    <title>SCU Maker物资管理系统 || 主页</title>
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

        #announce {
            position: absolute;
            left: 40%;
            top: 50%;
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
                    <li class="active"><a href="member_index.php">主页</a></li>
                    <li><a href="member_queryitem.php">物资查询</a></li>
                    <li><a href="member_borrow_list.php">我的借用</a></li>
                    <li><a href="member_reservation_list.php">我的预约</a></li>
                    <li><a href="member_info.php">个人信息</a></li>
                    <li><a href="index.php">退出</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <br /><br />
    <h3 style="text-align: center"><?php echo $result['name'];  ?>同学，你好</h3><br />
    <h4 style="text-align: center"><?php
                                    $sqla = "SELECT count(*) a from lend_list where student_id={$userid} and ISNULL(back_date);";
                                    $resa = mysqli_query($dbc, $sqla);
                                    $resulta = mysqli_fetch_array($resa);
                                    echo "你目前共借用{$resulta['a']}件物资。";
                                    ?>
    </h4>
    <h4 style="text-align: center">
        <?php
        $sqlb = "SELECT COUNT(*) b from lend_list where ISNULL(back_date) AND due_date < CURRENT_DATE() AND student_id={$userid};";
        $resb = mysqli_query($dbc, $sqlb);
        $resultb = mysqli_fetch_array($resb);
        $expired_count = $resultb['b'];

        if ($expired_count == 0) echo "你当前没有超期且未归还的物资。";
        else echo "有{$expired_count}件物资已超期，请你及时归还";
        ?>
        <br><br><br>
        <button type="button" onClick="window.location.href='member_item_borrow.php'" class="btn btn-default">借用物品</button>
        <button type="button" onClick="window.location.href='member_item_return.php'" class="btn btn-default">归还物品</button>


    </h4>
    <div id="announce">
        <a href="help.html" style="font-style: italic;color: black;text-decoration:replace-underline">使用帮助</a><br>

    </div>

</body>

</html>