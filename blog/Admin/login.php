<?php
    // 开启会话跟踪
    session_start();
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <!-- bootstrap css -->
    <link rel="stylesheet" href="../Public/css/bootstrap.min.css" media="screen" />
    <link rel="stylesheet" href="../Public/css/style.css" />
    <!--响应式特性 css-->
    <link href="../Public/css/bootstrap-responsive.min.css" rel="stylesheet">
    <!-- jquery -->
    <script type="text/javascript" src="../Public/js/jquery-1.8.3.min.js"></script>
    <!-- bootstrap.js -->
    <script type="text/javascript" src="../Public/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="row-fluid" id="header">
        <div class="span8 offset2">
            <h1 class="font">蓝鸥(广州)博客后台</h1>
            <p class="lead">欢迎来到蓝鸥博客后台！</p>
        </div>
    </div>

    <div class="row-fluid" id="login">
        <div class="span8 offset2">
            <form class="form-horizontal" action="./action.php?handler=dologin" method="post">
                <h3>登陆</h3>
                <div class="control-group">
                    <label class="control-label" for="inputName">用户名</label>
                    <div class="controls">
                        <input type="text" name="name" id="inputName" placeholder="Name">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputPassword">密码</label>
                    <div class="controls">
                        <input type="password" name="password" id="inputPassword" placeholder="Password">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputPassword">验证码</label>
                    
                    <div class="controls">
                        <input type="text" name="code" id="inputPassword" placeholder="验证码" style="width:60px"><img src="../Public/yzm.php" onclick="this.src='../Public/yzm.php?id=' + Math.random()">
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <label class="checkbox">
                            &nbsp;
                        </label>
                        <button type="submit" class="btn btn-large btn-primary form-submit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;登陆&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php
    include './Public/footer.html';
?>