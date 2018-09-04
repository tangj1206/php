<?php
	session_start();
	//判断用户是否输入
	if(!empty($_POST)){  //empty():判断一个变量是否为空，空返回true   !：取反
        $username = trim($_POST['username']);  //用户名，处理空格
		$password = md5($_POST['password']);  //将用户输入的密码进行md5加密
        include_once ('./conn.php');
        $mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PWD,DB_DBNAME);
		mysqli_query($mysqli,'set names utf8');  //防止乱码
		if($_FILES['profile']['name'] == ''){
            $path = './images/webqq.png';//设置默认头像地址
		}
        else {
            //1.上传的文件类型是否符合需求
            $last = strrpos($_FILES['profile']['name'],'.')+1;  //获取.在文件名中最后一次出现的位置
            $suffix = strtolower(substr($_FILES['profile']['name'],$last));  //获取文件后缀名
            $arr = array('jpg','png','gif');  //将常用图片文件后缀保存为一个数组
            if(!in_array($suffix,$arr)){
                echo '<script>alert("文件类型错误，返回注册页面！");window.location.href="../reg.html";</script>';
                exit;
            }
            //2.判断上传文件的大小
            if($_FILES['profile']['size']>1024*1024){
                echo '<script>alert("文件大小超出最大限制，返回注册页面！");window.location.href="../reg.html";</script>';
                exit;
            }
            //3.文件名重名：随机重命名
            $randtime = mt_rand().time();
            $path = '../upload/'.$randtime.'.'.$suffix;  //上传文件保存的位置
            move_uploaded_file($_FILES['profile']['tmp_name'],$path);  //将文件保存到指定的位置
            $path = './upload/'.$randtime.'.'.$suffix;
        }

		$sql = "INSERT INTO `user`(`username`,`password`,`profile`) VALUES('$username','$password','$path')"; //插入SQL
		mysqli_query($mysqli,$sql);
		//判断用户是否注册成功:mysqli_affected_rows(连接标识)
		if(mysqli_affected_rows($mysqli)){
			$_SESSION['username'] = $username;
			$_SESSION['userid'] = mysqli_insert_id($mysqli);  //获取插入数据的id
            //在用户注册的时候就创建以id为名字的表
            $tb_name = mysqli_insert_id($mysqli);
            $ct_tb = "CREATE TABLE IF NOT EXISTS `{$tb_name}`(id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,userid int(11) NOT NULL comment'好友id',username varchar(20) NOT NULL comment'自己名字',profile varchar(100) NOT NULL comment'自己头像',content TEXT  NOT NULL comment'自己发送的消息内容',systime datetime comment'发送时间' DEFAULT CURRENT_TIMESTAMP) ;";
            //返回boolean值
            $ct_tb_result = mysqli_query($mysqli,$ct_tb);
            if($ct_tb_result){
                //更改登录状态
                $userid = $_SESSION['userid'];
                $sql = "INSERT INTO `status`(`state`, `userid`) values(1,$userid)";
                mysqli_query($mysqli,$sql);
                //跳转到聊天界面
                echo '<script>alert("注册成功，即将跳转到聊天页面！");window.location.href="../chat.html";</script>';
            }
			else {
                echo '<script>alert("注册失败，重新注册！");window.location.href="../reg.html";</script>';
            }
		}
	}
?>