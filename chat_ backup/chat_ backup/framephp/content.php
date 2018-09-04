<?php
session_start();
header("content-type:text/html;charset=utf-8");
include_once ('./conn.php');
$mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PWD,DB_DBNAME);
mysqli_query($mysqli,'set names utf8');
if($_GET["friend"] == "正在群聊"){
    $sql = "SELECT `user`.`username`,`user`.`profile`,`groupchat`.* FROM `groupchat` INNER JOIN `user` ON `groupchat`.`userid`=`user`.`id` ORDER BY `groupchat`.`systime` ASC";
    $result = mysqli_query($mysqli,$sql);
}
else {
    //获取聊天好友注册时的id
    $friend = $_GET["friend"];
    $arr = array();
    $sql_f = "SELECT `id` FROM `user` WHERE `username`='$friend'";
    $temp = mysqli_query($mysqli,$sql_f);
    while ($rr = mysqli_fetch_assoc($temp)) {
        $arr[] = $rr;
    }
    $sql_fid =  $arr[0]['id'];
    //判断好友表是否存在(实际上已经存在，在好友注册时就已经创建)
    $sql_show_tb = 'show tables';//显示所有表
    $result_tb = mysqli_query($mysqli,$sql_show_tb);
    $rows_tb = array();
    while ($r = mysqli_fetch_assoc($result_tb)) {
        $rows_tb[] = $r;
    }
    $flag = false;
    foreach ( $rows_tb as $key => $value) {
        if($value['Tables_in_webchat'] == $sql_fid ){
            $flag = true;
        }
    }
    if(!$flag){
        $ct_tb = "CREATE TABLE `{$sql_fid}`(id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,userid int(11) NOT NULL comment'好友id',username varchar(20) NOT NULL comment'自己名字',profile varchar(100) NOT NULL comment'自己头像',content TEXT  NOT NULL comment'自己发送的消息内容',systime int(11) comment'发送时间');";
        echo 0;
        exit();
    }
    else { //否则，两人之间进行过聊天，直接从两人数据表中取出数据
        //获取当前登录用户的名字
        $username = $_SESSION["username"];
        //获取当前登录用户的id
        $sql_myid = $_SESSION['userid'];

        $sql = "SELECT * FROM `{$sql_fid}` WHERE userid='$sql_myid'  UNION SELECT * FROM `{$sql_myid}` WHERE userid='$sql_fid' ORDER BY systime ASC";
        $result = mysqli_query($mysqli,$sql);
    }
}
$rows = array();
while($r = mysqli_fetch_assoc($result)){
    if($_GET["friend"] == "正在群聊"){
        if($_SESSION['userid'] == $r['userid']){  //如果$tb_name表中的userid与当前登录信息中的用户id一致，则表示是当前用户自己发送的消息
            $r['is_mine'] = 1;
        }else{
            $r['is_mine'] = 0;
        }
    }else {
        if($_SESSION['userid'] == $r['userid']){
            $r['is_mine'] = 0;
        }else{
            $r['is_mine'] = 1;
        }
    }
	$rows[] = $r;
}
//foreach($rows as $key=>$value){
//    $rows[$key]['systime'] =  date('H:i:s',$value['systime']) ;
//};
//判断是否是ajax请求
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH'])){
	echo json_encode($rows);
}else{
}
?>