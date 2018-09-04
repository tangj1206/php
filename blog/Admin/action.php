<?php
	//引入配置文件
include './init.php';

	//接受处理方式
$a = isset($_GET['handler']) ? $_GET['handler'] : '';
switch ($a) {
	case 'dologin':
			// 用户输入的验证码
		$yzm = $_POST['code'];
			//拿到真实产生的验证码
		$code = $_SESSION['code'];

			//echo $code;
			//1.判断验证码
		if (strtolower($code) != strtolower($yzm)) {
			echo '<h2>验证码错误</h2>';
			echo '<meta http-equiv="refresh" content="2;url=./login.php?errno=1">';

			exit;
		}
			
			//接受用户输入的用户名密码
		$name = $_POST['name'];
		$pass = $_POST['password'];

			// 工具用户输入的数据，去查数据库是否存在该用户
		$sql = 'select * from ' . PIX . "adminuser where `name` = '{$name}'";
			
			//执行查询
		$userInfo = @query($sql)[0];
		//echo json_encode($userInfo);
		if (count($userInfo) > 0) {
			// echo "查到了该用户";
			// echo md5($pass);
			if ($userInfo['password'] == md5($pass)) {
				//到了这，说明用户名、密码、验证码全对了，登录成功
				$_SESSION['userInfo'] = $userInfo;
				echo '<h2>登录成功</h2>';
				echo '<meta http-equiv="refresh" content="2;url=./index.php">';
			} else {
				echo '<h2>密码不正确</h2>';
				echo '<meta http-equiv="refresh" content="2;url=./login.php?errno=3">';
			}
		} else {
			echo '<h2>用户不存在</h2>';
			echo '<meta http-equiv="refresh" content="2;url=./login.php?errno=2">';
		}
		break;

	case 'logout':
		//echo 'logout';
		//所谓的退出，只是把session里面的数据给销毁
		unset($_SESSION['userInfo']);
		echo '<h2>退出成功</h2>';
		echo '<meta http-equiv="refresh" content="2;url=./login.php">';
		break;
	default:
			# code...
		break;
}













?>