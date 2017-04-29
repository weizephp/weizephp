<?php
if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}
?><!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
        <meta name="keywords" content=""/>
        <meta name="description" content=""/>
        <title>登陆_后台管理</title>
        <link rel="stylesheet" href="<?php echo $wconfig['public_path'];?>/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="<?php echo $wconfig['theme_skin'];?>/css/global.css"/>
        <link rel="stylesheet" href="<?php echo $wconfig['theme_skin'];?>/css/admin-login.css"/>
        <script>
        // 让登陆页不在框架(iframe)里显示
        if(self.parent.frames.length != 0) {
            self.parent.location = document.location;
        }
        </script>
        <script src="<?php echo $wconfig['public_path'];?>/js/jquery-1.12.4.min.js"></script>
        <script src="<?php echo $wconfig['public_path'];?>/js/bootstrap.min.js"></script>
        <script src="<?php echo $wconfig['public_path'];?>/js/md5.min.js"></script>
    </head>
    <body class="w-login-body">
        <div class="container">
            <form method="post" action="?m=login&a=login" id="w-form-login" autocomplete="off">
                <h2 class="w-form-login-title">WeizePHP后台</h2>
                <label for="username" class="sr-only">Uid/Username/Email/Mobile</label>
                <input type="text" id="username" class="form-control" placeholder="ID号/用户名/邮箱/手机号" required autofocus/>
                <label for="password" class="sr-only">密码</label>
                <input type="password" id="password" class="form-control" placeholder="密码" required/>
                <label for="captchaval" class="sr-only">验证码</label>
                <input type="text" id="captchaval" class="form-control" placeholder="验证码" required/>
                <div class="w-captcha-box">
                    <input type="hidden" name="captchakey" id="captchakey" class="w-captcha-key"/>
                    <span class="w-captcha-img"></span>
                    <span class="w-captcha-update">显示验证码</span>
                </div>
                <button class="btn btn-lg btn-success btn-block" type="submit">登 陆</button>
            </form>
        </div>
        <?php include $wconfig['theme_path'] . '/w_dialog_error.html.php';?>
        <script>
        $(document).ready(function() {
            // 验证码
            var wCaptchaUpdateDom = $(".w-captcha-update");
            var wCaptchaUpdateVal = wCaptchaUpdateDom.html();
            var wCaptchaKeyDom    = wCaptchaUpdateDom.siblings(".w-captcha-key");
            var wCaptchaImgDom    = wCaptchaUpdateDom.siblings(".w-captcha-img");
            function wCaptchaShow() {
                var curVal = wCaptchaUpdateDom.html();
                if(curVal == wCaptchaUpdateVal) {
                    wCaptchaUpdateDom.html("换一个");
                }
                curVal = '';
                var curImgVal = wCaptchaImgDom.html();
                $.getJSON("general.php?m=captcha&a=createkey", function(data) {
                    wCaptchaKeyDom.val( data.captchakey );
                    if(curImgVal == '') {
                        wCaptchaImgDom.html( '<img class="img-thumbnail" src="general.php?m=captcha&a=display&captchakey='+ data.captchakey +'"/>' );
                    } else {
                        wCaptchaImgDom.children("img").attr( "src", "general.php?m=captcha&a=display&captchakey=" + data.captchakey );
                    }
                    curImgVal = '';
                });
            }
            $(".w-captcha-update, .w-captcha-img").on("click", function() {
                wCaptchaShow();
            });
            $("#captchaval").on("focus", function() {
                if( wCaptchaImgDom.html() == '' ) {
                    wCaptchaShow();
                }
            });
            // 登陆
            $( "#w-form-login" ).submit(function() {
                var username   = $.trim( $('#username').val() );
                var password   = $.trim( $('#password').val() );
                var captchakey = $.trim( $('#captchakey').val() );
                var captchaval = $.trim( $('#captchaval').val() );
                if(username == '') {
                    w_dialog_error("账号不能为空");
                    return false;
                }
                if(password == '') {
                    w_dialog_error("密码不能为空");
                    return false;
                }
                if(captchaval == '') {
                    w_dialog_error("验证码不能为空");
                    return false;
                }
                password = md5(password);
                $.post('?m=login&a=login', {username: username, password: password, captchakey: captchakey, captchaval: captchaval}, function(data) {
                    if(data.status == 1) {
                    	window.location.href = '?m=home';
                    } else {
                    	w_dialog_error(data.msg);
                    }
                }, 'json');
                return false;
            });
        });
        </script>
    </body>
</html>