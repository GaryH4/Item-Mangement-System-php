<!DOCTYPE html>
<html lang="zh-Hans-CN">

<head>
    <meta charset="UTF-8">
</head>

<body>

</body>

</html>
<?php
include('mysqli_connect.php');
include('function_lib.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_GET["admin_login"]) && $_GET["admin_login"] == true) {
        $acco = replaceSpecialChar($_POST["account"]);
        $acco = intval($acco);
        $pass_hash = sha1($_POST["pass"]);
        $adsql = "SELECT * from admin where admin_student_id={$acco} and password='{$pass_hash}'";
        $adres = mysqli_query($dbc, $adsql);
        if (mysqli_num_rows($adres) == 1) {
            session_start();
            $_SESSION['userid'] = $acco;
            $_SESSION['is_adm'] = true;
            echo "<script>window.location='admin_index.php'</script>";
        } else {
            echo "<script>alert('用户名或密码错误，请重新输入!');window.location='admin_login.php';</script>";
        }
    } else {
        $uid = replaceSpecialChar($_POST["card_uid"]);
        $uid = substr($uid,0,40); 
        $resql = "SELECT student_id from member_info where card_uid='{$uid}'";
        $reres = mysqli_query($dbc, $resql);
        if (mysqli_num_rows($reres) == 1) {
            session_start();
            $res = mysqli_fetch_array($reres);
            $_SESSION['userid'] = $res["student_id"];
            //echo "<script>alert('{$res["student_id"]}')</script>";
            echo "<script>window.location='member_index.php'</script>";
        } else {
            echo "<script>alert('用户不存在，请重新输入!');window.location='index.php';</script>";
        }
    }
} else die("bad request");
?>