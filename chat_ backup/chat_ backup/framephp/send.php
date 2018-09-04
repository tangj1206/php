<?php
session_start();
date_default_timezone_set('PRC');
if(!empty($_POST['content'])){
	$userid = $_SESSION['userid'];//用户自己的id,将自己发送的消息存储到此处
	$username = $_SESSION['username'];//用户自己的名字
    $content_title = $_POST['content_title']; //获取当前与之聊天的用户名字username
    $content = $_POST['content']; //获取用户输入内容
    $systime = date('y-m-d H:i:s',time());  //获取发送消息时间戳
    include_once ('./conn.php');
    $mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PWD,DB_DBNAME);
    mysqli_query($mysqli,'set names utf8');
    //判断当前是群聊还是好友
    if($content_title == '正在群聊'){
        $sql = "INSERT INTO `groupchat`(`userid`,`content`,`systime`) VALUES('$userid','$content','$systime')";
    }else {
        //获取好友的id,表名
        $arr_f = array();
        $sql_f = "SELECT `id`,`profile` FROM `user` WHERE `username`='$content_title'";
        $temp_f = mysqli_query($mysqli,$sql_f);
        while ($r_f = mysqli_fetch_assoc($temp_f)) {
            $arr_f[] = $r_f;
        }
        $fid =  $arr_f[0]['id'];
        //获取自己的头像
        $arr_m = array();
        $sql_m = "SELECT `id`,`profile` FROM `user` WHERE `username`='$username'";
        $temp_m = mysqli_query($mysqli,$sql_m);
        while ($r_m = mysqli_fetch_assoc($temp_m)) {
            $arr_m[] = $r_m;
        }
        $mprofile =  $arr_m[0]['profile'];
        //将好友id和自己的名字，自己的头像存到自己的数据里
        $sql = "INSERT INTO `{$userid}`(`userid`,`username`,`content`,`profile`,`systime`) VALUES('$fid','$username','$content','$mprofile','$systime')";
    }
	mysqli_query($mysqli,$sql);
	if(mysqli_affected_rows($mysqli)  && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])){
		echo 1;
	}
}
?>