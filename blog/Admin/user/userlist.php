<?php
   // 导入初始化文件
require '../init.php';
require LOCALPATH . '/Public/header.php';
require LOCALPATH . '/Public/nav.php';
    // 先查所有的用户数据
    // 1.准备SQL语句
$sql = 'select * from ' . PIX . 'adminuser';
    // 2.发送执行
$userlist = query($sql);
    //var_dump($userlist);
    // 准备格式化数据
$status = array('超级管理员', '普通管理员', '普通会员');
?>

    <div class="row-fluid" id="main">
        <div class="span8 offset2">
        <!-- 小作业，自行完成搜索 -->
            <form class="form-search fr" action="./action.php?handler=search" method="post">
                <input type="text" name="name" class="input-medium" placeholder="Name">
                <button type="submit" class="btn">搜索</button>
            </form>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>编号</th>
                    <th>头像</th>
                    <th>账户名</th>
                    <th>真实姓名</th>
                    <th>邮箱</th>
                    <th>添加时间</th>
                    <th>级别</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($userlist as $key => $val) : ?>
                        <tr>
                            <td> <?php echo $val['id']; ?></td>
                            <td><img class="img-thumbnail" src="../Public/icon/<?php echo $val['icon']; ?>" alt=""></td>
                            <td><?php echo $val['name']; ?></td>
                            <td><?php echo $val['truename']; ?></td>
                            <td><?php echo $val['email']; ?></td>
                            <td><?php echo date("Y-m-d:h:i:sa", $val['addtime']); ?></td>
                            <td><?php echo $status[$val['status']]; ?></td>
                            <td>
                                <?php if ($_SESSION['userInfo']['status'] == 0) : ?>
                                    <?php if ($val['status'] == 0) : ?>
                                    <?php else : ?>
                                    <a href="./edit.php?id=<?php echo $val['id']; ?>">编辑</a>
                                    <a href="./action.php?handler=userdel&id=<?php echo $val['id']; ?>">删除</a>
                                    <?php endif ?>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <div class="pagination">
                <ul>
                    
                </ul>
            </div>
            <script>
                $('.pagination ul a').unwrap('div').wrap('<li></li>');
                $('.pagination ul span').wrap('<li class="active"></li>')
            </script>
        </div>
    </div>

<?php
require LOCALPATH . './Public/footer.html';
?>
