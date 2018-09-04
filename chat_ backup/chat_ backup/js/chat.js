$(function(){
    // 内容区
    $('#wrap-content .content').scrollTop($('#wrap-content .content')[0].scrollHeight);
    setInterval(function () {
        var username = $('#wrap-content #user').html();
        $.ajax({
            type: "get",
            url: "./framephp/content.php",
            data: {
                "friend": username
            },
            dataType: "json",
            success: function (msg) {
                if (!msg) {
                    $('#wrap-content #msg').html('<span>创建数据表成功!</span>');
                }
                else {
                    // console.log(msg);
                    var nr = '';
                    for (var i = 0; i < msg.length; i++) {
                        nr += '<li>';
                        nr += '<ul ';
                        if (msg[i].is_mine) {
                            nr += 'class="content-right"';
                            nr += '>';
                            nr += '<li><img src="' + msg[i].profile + '" alt="" width="50px" height="50px"/></li>';
                            nr += '<li><span>' + msg[i].systime + '</span><span style="margin-right:15px;">' + msg[i].username + '</span><p>' + msg[i].content + '</p></li>';
                            nr += '</ul>';
                            nr += '</li>';
                        } else {
                            nr += 'class="content-left"';
                            nr += '>';
                            nr += '<li><img src="' + msg[i].profile + '" alt="" width="50px" height="50px"/></li>';
                            nr += '<li><span style="margin-right:15px;">' + msg[i].username + '</span><span>' + msg[i].systime + '</span><p>' + msg[i].content + '</p></li>';
                            nr += '</ul>';
                            nr += '</li>';
                        }

                    }
                    $('#wrap-content #msg').html(nr);
                }
            },
            error: function (msg) {
                $('#wrap-content #msg').html('<span>数据请求错误，请请稍后重试!</span>');
                // console.log(msg);
            }
        });
    }, 100);
    var timer = setInterval(function () {
        $("#wrap-content .content").animate({
            scrollTop: $('#wrap-content .content')[0].scrollHeight
        }, 100);
    }, 100);
    $("#wrap-content .content").mouseenter(function () {
        clearInterval(timer);
    })
    $("#wrap-content .content").mouseleave(function () {
        timer = setInterval(function () {
            $("#wrap-content .content").animate({
                scrollTop: $('#wrap-content .content')[0].scrollHeight
            }, 100);
        }, 100);
    })

// 发送消息区
    var content_title = null;
//点击回车,发送消息
    document.onkeydown=function(event){
        var e = event || window.event || arguments.callee.caller.arguments[0];
        if(e && e.keyCode==13){ // enter 键
            content_title = $('#wrap-content #user').html();
            sendAjax();
        }
    };
//点击发送按钮,发送消息
    $('#sendMsg #sendbtn').click(function(){
        content_title = $('#wrap-content #user').html();
        sendAjax();
        // console.log(content_title);
    });
//点击下载按钮,默认下载当天内容
    var now =  new Date();
    var year = now.getFullYear();
    var month = (now.getMonth()+1)<10?'0'+(now.getMonth()+1):(now.getMonth()+1);
    var day = now.getDate()<10?'0'+now.getDate():now.getDate();
    var selval = year+'-'+month+'-'+day;
    $('#sendMsg #date_d').val(selval);
    $('#sendMsg #date_d').change(function(){
        selval = this.value;
    });
// 选择日期和时间
    $('#sendMsg #date_d').cxCalendar();
//定时更换下载按钮的href
    setInterval(function(){
        var selval = $('#sendMsg #date_d').val();
        content_title = content_title = $('#wrap-content  #user').html();
        var href = "./framephp/download.php?content_title="+content_title+"&selval="+selval;
        $("#sendMsg  #download").attr("href",href);
    },100)

//tip是提示信息，type:'success'是成功信息，'danger'是失败信息,'info'是普通信息,'warning'是警告信息
    function showTip(tip, type) {
        var $tip = $('#sendMsg #tip');
        $tip.stop(true).prop('class', 'alert alert-' + type).text(tip).css('margin-left', - $tip.outerWidth() / 2).fadeIn(500).delay(1000).fadeOut(500);
    }
    function sendAjax(){
        var content = $('#sendMsg #contentMsg').val();
        if(content){
            //$('form')[0].submit();
            $.post("./framephp/send.php", { 'content': content,'content_title':content_title },
                function(data){
                    $('#sendMsg #contentMsg').val('');
                    // console.log(data);
                });
        }else{
            showTip('消息不能为空！','warning');
        }
    }

// 列表区
//获取登录页面设置的cookie值
    function getCookie(c_name) {
        if (document.cookie.length > 0) {
            c_start = document.cookie.indexOf(c_name + "=");
            if (c_start != -1) {
                c_start = c_start + c_name.length + 1;
                c_end = document.cookie.indexOf(";", c_start);
                if (c_end == -1) {
                    c_end = document.cookie.length;
                }
                return unescape(document.cookie.substring(c_start, c_end));
            }
        }

        return "";
    }
    var currentname = getCookie('currentname');
    console.log(currentname);
    /*判断是群聊还是好友聊天,目前只针对群聊
    $('#friends').click(function(){
        getinfo(this);
        $(this).css('background-color','#acc2a5');
        $('#group').css('background-color','#fff');

    })*/
    $('#listwrap #group').click(function(){
        // $('#friends').css('background-color','#fff');
        var self = this;
        $(self).css('background-color','#9FF048');
        var str = '<h3><span id="user">正在群聊</span></h3>';
        $('#wrap-content #title').html(str);
    })
//定时器，每隔300毫秒从数据库中请求数据，刷新群聊列表
    setInterval(function(){
        getinfo($('#listwrap #group'));
    },100);

//定义变量，接受点击好友列表时的name值，传递给content.html的.user
    var friendName = null;
//好友还是群聊
    function getinfo(obj){
        $.ajax({
            url: './framephp/list.php',
            type: 'get',
            dataType:"json",
            data:{
                'flag': 1
                // 'flag': obj.getAttribute('value')
            },
            success:function(msg){
                // console.log('数据返回成功');
                // console.log(msg);
                if(!msg){
                    $('#listwrap .user-list').html() ;
                }
                else {
                    var text = '';
                    for(var i = 0;i<msg.length;i++){
                        if((msg[i].username) == getCookie('currentname')){
                            // console.log('msg[i].username='+msg[i].username) ;
                            // console.log('currentname='+currentname) ;
                            continue;
                        }
                        text += '<li><a href="javascript:;" name="';
                        text += msg[i].username+'"><img src=';
                        text += msg[i].profile;
                        text += ' width="30px" height="30px"';
                        text += '/><span>';
                        text += msg[i].username;
                        text += '</span> </a>';
                        if(msg[i].state==1){
                            text +='<span class="state" id="online"><span/></li>';
                        }else {
                            text +='<span class="state" id="offline"><span/></li>';
                        }
                    }
                    $('#listwrap .user-list').html(text) ;
                    $('#listwrap .user-list a').click(function(){
                        //console.log($(this).attr('name'));
                        friendName = $(this).attr('name');
                        var str2 = '<h3>正在与好友<span id="user">'+friendName+'</span>聊天</h3>';
                        //console.log($(window.parent.frames["content"].document.getElementById('talkwidth')).html());
                        $('#wrap-content #title').html(str2);
                    })
                }
            },
            error:function(msg){
                $('#listwrap .user-list').html('数据请求错误') ;
            }
        })
    }
})
