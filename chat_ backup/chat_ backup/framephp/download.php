<?php
session_start();
header("content-type:text/html;charset=utf-8");
error_reporting(0);
include_once ('./conn.php');
$mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PWD,DB_DBNAME);
$selval = $_GET['selval'];//需要保存几天前的聊天记录
//$yesterday = date("Y-m-d",strtotime("$selval -1 day"));
//$tomorow = date("Y-m-d",strtotime("$selval +1 day"));
$friend = $_GET['content_title'];//当前聊天对象
mysqli_query($mysqli, 'set names utf8');
if ($friend == "正在群聊") {
    $prename = '群聊';
    $sql = "SELECT * FROM(SELECT `user`.`username`,`groupchat`.`content`,`groupchat`.`systime` FROM `groupchat` INNER JOIN `user` ON `groupchat`.`userid`=`user`.`id`  ORDER BY `systime` ASC)AS temp WHERE date_format( temp.`systime`,'%Y-%m-%d')='$selval'";
} else {
    //获取聊天好友注册时的id
    $arr = array();
    $sql_f = "SELECT `id` FROM `user` WHERE `username`='$friend'";
    $temp = mysqli_query($mysqli, $sql_f);
    while ($rr = mysqli_fetch_assoc($temp)) {
        $arr[] = $rr;
    }
    $sql_fid = $arr[0]['id'];
    $username = $_SESSION["username"];
    //获取当前登录用户的id
    $sql_myid = $_SESSION['userid'];

    $prename = $username .'-'. $friend;

    $sql = "SELECT * FROM(SELECT `username`,`content`,`systime` FROM `{$sql_fid}` WHERE `userid`='$sql_myid'  UNION SELECT `username`,`content`,`systime` FROM `{$sql_myid}` WHERE `userid`='$sql_fid'  ORDER BY `systime` ASC )AS temp WHERE date_format( temp.`systime`,'%Y-%m-%d')='$selval'";
}
$result = mysqli_query($mysqli, $sql);
if (!$result) {
    printf("Error: %s\n", mysqli_error($mysqli));
    exit();
}
$Use_Title = 1;
$now_date = date('Y-m-d H:i:s');
$title =  $prename . '的聊天记录';
$file_type = "vnd.ms-excel";
$file_ending = "xls";
$filename = mb_convert_encoding($title, "gb2312", "utf-8").'.'.$file_ending;
header("Content-Type: application/$file_type");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
if ($Use_Title == 1) {
    $title_name = mb_convert_encoding($title, "gb2312", "utf-8");
    echo("\t$title_name\n");
}
print("\n");
$sep = "\t";
$tablehead = array('用户名', '聊天内容', '聊天时间');
for ($i = 0; $i < count($tablehead); $i++) {
    echo mb_convert_encoding($tablehead[$i], "gb2312", "utf-8") . "\t";
}
print("\n");
while ($row = mysqli_fetch_row($result)) {
    $schema_insert = "";
    for ($j = 0; $j < mysqli_num_fields($result); $j++) {
        if (!isset ($row[$j])) {
            $fieldname = "null";//当前字段中没有值时，用null填充
            $fieldname = mb_convert_encoding($fieldname, "gb2312", "utf-8");
            $schema_insert .= $fieldname . $sep;
        } else {
            $row[$j] = mb_convert_encoding($row[$j], "gb2312", "utf-8");
            $schema_insert .= $row[$j] . $sep;
        }
    }
    $schema_insert = str_replace($sep . "$", "", $schema_insert);
    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
    $schema_insert .= "\t";
    print(trim($schema_insert));
    print "\n";
}