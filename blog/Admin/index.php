<?php
    require './init.php';
    if (empty($_SESSION['userInfo']['name'])) {
        header('location:./login.php');
    }
    require LOCALPATH . './Public/header.php';
    require LOCALPATH . './Public/nav.php';
    
    
?>

    <div class="row-fluid" id="main">
        <div class="span8 offset2">
            <h4>欢迎您来到后台</h4>
            <script>
                $('.pagination ul a').unwrap('div').wrap('<li></li>');
                $('.pagination ul span').wrap('<li class="active"></li>')
            </script>
        </div>
    </div>

<?php
    require './Public/footer.html';
?>
