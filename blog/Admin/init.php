<?php
	// 初始化文件，可以做项目的一些配置

	// 开启会话跟踪
    session_start();

	// 设置字符集
	header('Content-Type:text/html;charset=utf-8');

	// 本地路径
	$localpath = str_replace('\\','/',dirname(__FILE__) . '/');
	define('LOCALPATH',$localpath);
    
	// 切割出协议
	$http =  explode('/',$_SERVER['SERVER_PROTOCOL'])[0];
	// 拼接协议和主机名
	$http .= '://' . $_SERVER['HTTP_HOST'];
	// 执行替换

	$httpath = str_replace($_SERVER['DOCUMENT_ROOT'],$http,$localpath);


	//导入配置文件
	require  LOCALPATH . '../Public/config.php';
	// 导入公共函数库
	require  LOCALPATH . '../Public/functions.php';


	// 定义协议路径  跳转用协议路径，引入文件用本地路径
	define('HTTPATH',$httpath);
	// echo '本地' . LOCALPATH;
	// echo '协议' . HTTPATH;
	//中国时区
	date_default_timezone_set('PRC');
