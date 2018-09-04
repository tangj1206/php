<?php
session_start();
header('Content-type:text/html;charset=utf-8');
error_reporting(0);
include_once ('./conn.php');
$mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PWD,DB_DBNAME);
$userid = $_SESSION['userid'] ;
$sql = "update `status` set `state` = 0 where `userid` = {$userid}";
$result = mysqli_query($mysqli,$sql);
if($result){
    $_SESSION = array();
    if(isset($_COOKIE[session_name()])){
        setCookie(session_name(),'',time()-3600);
        session_destroy();//销毁一个会话中的全部数据
        echo '<script>alert("注销成功！");location.href="../index.html";</script>';
    }else{
        echo '<script>alert("注销失败！");</script>';
    }
}else {
    echo '<script>alert("注销失败！");</script>';
}
?>