<?php
	session_start();  //开启session
	//用户登录页面
	if(!empty($_POST)){
        include_once ('./conn.php');
        $mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PWD,DB_DBNAME);
		$username = trim($_POST['username']);  //用户名，处理空格
		$password = md5($_POST['password']);
		mysqli_query($mysqli,'set names utf8');
		$sql = "SELECT * FROM `user` WHERE `username`='$username' AND `password`='$password'";  //验证SQL
		$result = mysqli_query($mysqli,$sql);  //执行查询
		$r = mysqli_fetch_assoc($result);  //将结果集转换为数组
		if(!empty($r)){
		    if(!empty($_SESSION['username'])){
                echo '<script>alert("登录成功，即将跳转！");window.location.href="../chat.html";</script>';
            }else {
                $_SESSION['username'] = $r['username'];  //将用户名保存到session，用作验证用户是否登录
                $_SESSION['userid'] = $r['id'];
                $userid = $_SESSION['userid'];
                $sql = "update `status` set `state` = 1 where `userid` = $userid";
                $result = mysqli_query($mysqli,$sql);
                if($result){
                    echo '<script>alert("登录成功，即将跳转！");window.location.href="../chat.html";</script>';
                }
                else {
                    echo '<script>alert("登录失败，重新登录！");window.location.href="../index.html";</script>';
                }
            }
		}
        else{
            echo '<script>alert("登录失败，重新登录！");window.location.href="../index.html";</script>';
        }
	}
?>