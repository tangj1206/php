<?php
	//引入配置文件
include '../init.php';

	//接受处理方式
$a = isset($_GET['handler']) ? $_GET['handler'] : '';
switch ($a) {
    case 'add':
        if ($_SESSION['userInfo']['status'] != 0) {
            //不等于0 不是超级管理员 无法添加用户
            echo '<script> alert("非超级管理员无法操作"); window.history.go(-1);</script>';
            exit;
        }

        if ($_POST['password'] != $_POST['repassword']) {
            echo '<script> alert("两次密码不一致"); window.history.go(-1);</script>';
            exit;
        }

        var_dump($_FILES);
        //准备存储图片的目录
        $savePath = '../Public/icon/';

        // 允许的类型
        $allowType = array('image/jpg', 'image/jpeg', 'image/gif', 'image/png'); 
        
        // 1.调用上传函数
        // 2.文件上传form表单必须加上这个属性:enctype="multipart/form-data"
        $upload = upload('myfile', $savePath, $allowType);
        // var_dump($upload);
        // 判断上传之后的状态 如果成功，status为true
        if ($upload['status']) {
            // 如果上传成功，就返回上传文件名
            $picPath = $upload['imageName'];//获取上传后的文件名

            $picPath = $savePath . $picPath;

            // 得到图片，进行缩放
            $zoomSmallName = zoom($picPath, $savePath, 100, 100);

            //把上传了的大图片删除掉
            unlink($picPath);
        }

        // 1.接受用户输入的数据
        $name = htmlspecialchars($_POST['name']);
        $pass = md5($_POST['password']);
        $truename = htmlspecialchars($_POST['truename']);
        $email = htmlspecialchars($_POST['email']);
        $icon = $zoomSmallName;
        $status = $_POST['status'];
        $addtime = time();

        // 2.准备sql语句
        $sql = 'insert into ' . PIX . "adminuser 
        (`name`, `password`, `truename`, `email`, `icon`,`status`,`addtime`) values
         ('{$name}','{$pass}','{$truename}','{$email}','{$icon}','{$status}','{$addtime}')";
        // echo $sql;
        // 执行插入返回的ID
        $insertId = execu($sql);
        if ($insertId) {
            echo 'ok...添加管理员成功啦,3秒以后跳转';
            echo '<meta http-equiv="refresh" content="2;url=./userlist.php">';
        } else {
            echo '<script> alert("添加失败，请重试！"); window.history.go(-1);</script>';
            exit;
        }
        break;
    case 'edit':
        // var_dump($_POST);
        // 1.接收用户id
        $id = $_GET['id'];

        // 2.用户没有重新设置密码 
        $pass = $_POST['password'];
        $repass = $_POST['repassword'];
        //判断两次密码是否相等
        if ($pass != $repass) {
            echo '两次密码不一样,3秒以后跳转';
            echo '<meta http-equiv="refresh" content="2;url=./edit.php?id=' . $id . '">';
            exit;
        }
        if (empty($pass)) {
            $sql = 'select id,name,password from `' . PIX . 'adminuser` where id =' . $id;
            $list = query($sql)[0];
            $pass = $list['password'];
           // echo '用户在更新的时候，没有重新输入密码，那么我们拿数据库的密码：' . $pass;
        } else {
            $pass = md5($_POST['password']);
            echo '用户在更新的时候，有输入密码，那么我们取用户输入的密码：' . $pass;
        }

        //用户是否上传图片，如果没上传，我们就取隐藏域传过来的图片名
        if ($_FILES['myfile']['error'] == 0) {
            //文件上传成功
            echo '用户有上传文件';
            //准备存储图片的目录
            $savePath = '../Public/icon/';

            //允许上传的类型
            $allowType = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');

            // 1.调用上传函数
            // 文件上传，form表单必须加上这个属性 enctype="multipart/form-data"
            $upload = upload('myfile', $savePath, $allowType);
        }



        break;
    default:
			# code...
        break;
}













?>