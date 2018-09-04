<?php
session_start();
//error_reporting(0);
include_once ('./conn.php');
$mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PWD,DB_DBNAME);
if(isset($_SESSION['username'])){
    mysqli_query($mysqli,'set names utf8');
    if(!empty($_GET["flag"])) {
        if($_GET["flag"] == "0"){
            $sql = "SELECT `user`.`username`,`user`.`profile`,`friends`.`fid`,`status`.`state` FROM `user`,`status`,`friends` where `status`.`userid`=`user`.`id` AND `user`.`id`=`friends`.`fid` ORDER BY `status`.`state` ASC";
        }
        if($_GET["flag"] == "1"){
            $sql = "SELECT `user`.`username`,`user`.`profile`,`status`.`state` FROM `user`,`status` where `status`.`userid`=`user`.`id` ORDER BY `status`.`state` ASC";
        }
    }else {//默认显示群列表
        $sql = "SELECT `user`.`username`,`user`.`profile`,`status`.`state` FROM `user`,`status` where `status`.`userid`=`user`.`id` ORDER BY `status`.`state` ASC";
    }

    $result = mysqli_query($mysqli,$sql);
    $rows = array();  //定义一个空数组用来保存用户数据
    while($r = mysqli_fetch_assoc($result)){
        $rows[] = $r;  //将$r作为一个元素追加到$rows数组最后
    }
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH'])){
        echo json_encode($rows);
        exit;
    }
}
else {
    echo 0;
}
?>