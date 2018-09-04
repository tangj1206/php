<?php
    //导入配置文件
require '../init.php';
require LOCALPATH . './Public/header.php';
require LOCALPATH . './Public/nav.php';

// 接收用户的id用于查询数据
$id = $_GET['id'];
//查询
$sql = 'select * from `'.PIX.'adminuser` where id='.$id;
//echo $sql;
$userInfo = @query($sql)[0];
//var_dump($userInfo);
?>

<div class="row-fluid" id="login">
        <div class="span8 offset2">
            <form class="form-horizontal" action="./action.php?handler=edit&id=<?php echo $userInfo['id']?>" method="post" enctype="multipart/form-data">
                <h3>编辑管理员</h3>
                <div class="control-group">
                    <label class="control-label" for="inputName">用户名</label>
                    <div class="controls">
                        <input value="<?php echo $userInfo['name'] ?>" type="text" name="name" id="inputName" placeholder="登录账号">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputPassword">密码</label>
                    <div class="controls">
                        <input  type="password" name="password" id="inputPassword" placeholder="密码">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="reInputPassword">确认密码</label>
                    <div class="controls">
                        <input type="password" name="repassword" id="reInputPassword" placeholder="确认密码">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="truename">真实姓名</label>
                    <div class="controls">
                        <input value="<?php echo $userInfo['truename'] ?>" type="text" name="truename" id="truename" placeholder="真实姓名">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="email">邮箱</label>
                    <div class="controls">
                        <input value="<?php echo $userInfo['email'] ?>" type="text" name="email" id="email" placeholder="邮箱">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">头像</label>
                    <div class="controls">
                        <input type="file" name="myfile" >
                        <img src="../Public/icon/<?php echo $userInfo['icon']?>" alt="">
                        <!-- 这个隐藏域用于传递头像的图片名 -->
                        <input type="hidden" name="icon" value="<?php echo $userInfo['icon']?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">级别</label>
                    <div class="controls">
                        <select name="status">
                            <option value="2" <?php echo $userInfo['status'] == 2 ? 'selected' :'' ?>>普通用户</option>
                            <option value="1" <?php echo $userInfo['status'] == 1 ? 'selected' :'' ?>>普通管理员</option>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <label class="checkbox">
                            &nbsp;
                        </label>
                        <button type="submit" class="btn btn-large btn-primary form-submit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;确定&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php
require LOCALPATH . './Public/footer.html';
?>