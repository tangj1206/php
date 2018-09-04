<?php
/**
 * Created by PhpStorm.
 * User: 12257
 * Date: 2018/3/21 021
 * Time: 22:50
 */
    if(isset($_POST['code']))
    {
        session_start();
        if (strtolower($_POST['code'])==strtolower($_SESSION['code']))
        {
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH'])){
                echo 1;
            }
            else{
                echo 0;
            }
        }
    }else {
        echo 0;
    }