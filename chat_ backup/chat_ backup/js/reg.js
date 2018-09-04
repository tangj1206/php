$(function () {
    var $user = $("#user"),     //注册用户名
        $pass = $("#password"),   //注册密码
        $repass = $("#repassword"),   //确认密码
        $reg_btn = $("#reg-btn"),   //注册按钮
        $pass_info = $(".pass-info"),  //密码提示信息
        $head = $("#reg-head"),   //上传头像
        $code = $("#getcode");
    //获取所有输入框 并在获取焦点时 提示输入信息
    $("input[type='text'],input[type='password'],input[type='file]").focus(function () {
        $(this).next("span").show();
        // console.log($(this).next("span"));
    });
    //     .blur(function() {
    //   $(this).next("span").hide();
    // })

    //按键弹起时判断用户名
    var flag = 1;
    $user.keyup(function () {
        var reg = /^[\u4e00-\u9fa50-9a-zA-Z]{2,6}$/;
        var result = reg.exec($(this).val());
        if ($(this).val()) {
            if (!result) {
                nextspan($(this), "用户名格式错误！", 1, 1);
                flag = 2;
            } else {
                //用户名格式合法
                nextspan($(this), "用户名2-6位的汉字、英文或数字组合", 0, 0);
                var username = $(this).val(); //获取用户输入的用户名
                $.ajax({
                    type: "POST",  //参数传递的方式（get/post）
                    url: "./framephp/check.php",  //服务器端的响应文件地址
                    data: {"username": username}, //参数
                    success: function (msg) {  //服务器响应成功之后调用的回调函数
                        if (msg == 1) {
                            nextspan($user, '用户名可用', 0, 1);
                            flag = 3;
                        } else {
                            nextspan($user, '用户名已被占用', 1, 1);
                            flag = 4;
                        }
                    }
                });
            }
        } else {
            nextspan($(this), "2-6位的汉字、英文或数字组合", 0, 1);
        }
    })

    //按键谈起时判断密码
    $pass.keyup(function () {
        var reg = /^[a-zA-Z0-9]{6,16}$/;
        var result = reg.exec($(this).val());
        if ($(this).val()) {
            if (!result) {
                nextspan($(this), "密码格式错误！", 1, 1);
            } else {
                nextspan($(this), "密码格式正确", 0, 1);
            }
        } else {
            nextspan($(this), "密码为6-16位数字、字母组合", 0, 1);
        }
    })

    //按键弹起时 验证两次密码是否一致
    $repass.keyup(function () {
        if ($pass.val() != $repass.val()) {    //密码与确认密码是否相等
            nextspan($repass, "密码不一致", 1, 1);
        } else {
            nextspan($repass, "确认密码", 0, 0);
        }
    })

    //按键弹起时，获取验证码
    var codevalue = null;
    $code.keyup(function(){
        codevalue = $code.val();
        console.log(codevalue);
    })

    //失去焦点执行ajax,判断
    var codebool = 0;
    $code.blur(function(){
        codebool = checkCode();
    })

    //点击注册按钮
    $reg_btn.click(function () {
        if (check() && codebool) {
             $('#reg-form').submit();
        }
    })

    //头像实时预览
    var reg_head = document.getElementById("reg-head"); //头像
    //上传文件时
    $(reg_head).change(function () {
        //上传文件的名字
        var str = this.files[0].name;
        //截取文件名后缀
        var suffix = (str.substr(-3)).toLocaleLowerCase();
        //上传文件后缀名判断
        var reSuffix = /^(png|jpg|gif)$/              //上传文件后缀名判断
        //超过2M判断
        if (this.files[0].size > 2000000) {              //超过2M判断
            nextspan($(reg_head), "头像不能超过2M", 1, 1);
            //后缀名验证
        } else if (!reSuffix.test(suffix)) {            //后缀名验证
            nextspan($(reg_head), "支持格式：jpg/png/gif", 1, 1); //提示信息
        } else {
            nextspan($(reg_head), "上传头像", 0, 0);
            var reader = new FileReader();
            reader.onload = function (evt) {
                $("#head img").prop("src", evt.target.result); //设置预览路径
            }
            reader.readAsDataURL(reg_head.files[0]);   //获取实时预览路径
        }
    })
    //用户名验证  2-8位 字母数字及下划线
    var reName = /^[\u4e00-\u9fa50-9a-zA-Z]{2,6}$/;
    //密码验证  6-16位 字母数字及下划线
    var rePass = /^[a-zA-Z0-9]{6,16}$/;

    /**
     * 验证输入是否正确的函数
     */
    function check() {
        if (flag == 2) {         //用户名格式验证
            nextspan($user, "用户名格式错误！", 1, 1);
            return false;
        } else if (flag == 3) {
            nextspan($user, '用户名可用', 0, 1);
        } else if (flag == 4) {
            nextspan($user, '用户名已被占用', 1, 1);
            return false;
        }
        else {
            nextspan($user, "输入2-6位的汉字、英文或数字组合", 0, 0);
        }

        if (rePass.test($pass.val())) {        //密码格式验证
            nextspan($pass, "输入6-16位的数字、字母组合", 0, 0);
        } else {
            nextspan($pass, "密码格式错误！", 1, 1);
            return false;
        }

        if ($pass.val() != $repass.val()) {    //检查两次密码是否一致
            nextspan($repass, "密码不一致！", 1, 1);
            return false;
        } else {
            nextspan($repass, "重复密码", 0, 0);
        }

        // if(!!!$("#head img").prop("src")){    //图片是否上传
        //   //nextspan($head, "请上传头像！", 1, 1);
        //  return false;
        // }else{
        //   //nextspan($head, "JPG|PNG|GIF类型图片", 0, 0);
        // }
        //所有验证通过时 返回true 用于判断
        return true;
    }

    function checkCode(){
        if (codevalue) {
            $.ajax({
                type: "POST",
                url: "./framephp/checkcode.php",
                data: {"code": codevalue},
                success: function (msg) {
                    if (msg==1) {
                        nextspan($code, '验证码正确', 0, 1);
                        codebool = 1;
                    } else {
                        nextspan($code, '验证码错误', 1, 1);
                        codebool = 0;
                    }
                }
            })
        }
        else {
            nextspan($code, '请输入验证码', 0, 1);
            codebool = 0;
        }
        return codebool;
    }


    /**
     * @param {Object} obj  要提示错误的对象
     * @param {String} msg  错误的信息
     * @param {Boolean} a   布尔值红色或蓝色提示框TRUE红色FALSE蓝色
     * @param {Boolean} b   控制显示隐藏  TRUE显示FALSE隐藏
     * 需要布局
     * author yu
     */
    function nextspan(obj, msg, a, b) {
        $(obj).next("span").text(msg).css({
            "background": a ? '#FA1A2C' : '#abcdef',
            "display": b ? "block" : 'none'
        });
    }

})