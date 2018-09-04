<?php
    session_start();
    $image = imagecreatetruecolor(100,37);
    $bgcolor = imagecolorallocate($image,255,255,255);//#FFFFFFFFFFFF
    imagefill($image,0,0,$bgcolor);
    $captch_code="";
    for ($i=0;$i<4;$i++){
        $fontfiles = './font/consola.ttf';
        $fontsize = 20;
        $fontcolor = imagecolorallocate($image,rand(0,120),rand(0,120),rand(0,120));
        $data='abFPc78hVdjklmnGpqRrstCDuvwMNoWxyAz1B25TSXi9U0EefgH34IJKL6QYZ';
        $fontcontent=substr($data,rand(0,strlen($data)),1);
        $captch_code.="$fontcontent";
        $x = ($i * 100/4)+rand(5,10);
        // $y = rand(5,10);
        $y=rand(25,23);
        $randAngle = rand(-15,15);
        imagettftext($image,$fontsize,$randAngle,$x,$y,$fontcolor,$fontfiles,$fontcontent);
        // imagestring($image,$fontsize,$x,$y,$fontcontent,$fontcolor);
    }
    $_SESSION['code']=$captch_code;
    for($i=0;$i<200;$i++){
        $pointcolor = imagecolorallocate($image,rand(50,200),rand(50,200),rand(50,200));
        imagesetpixel($image,rand(1,99),rand(1,29),$pointcolor);
    }
    for($i=0;$i<8;$i++){
        $linecolor = imagecolorallocate($image,rand(60,220),rand(60,220),rand(60,220));
        imageline($image,rand(1,99),rand(1,29),rand(1,99),rand(1,29),$linecolor);
    }
    header('content-type: image/png');
    imagepng($image);
    //销毁
    imagedestroy($image);
?>