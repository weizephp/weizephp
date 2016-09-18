<?php if(!defined('IN_WEIZEPHP')){exit('Access Denied');}?><!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes"/>
        <title>会员登录_WeizePHP框架</title>
        <link href="<?php echo $_W['public_path'];?>/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="<?php echo $_W['skin_path'];?>/css/signin.css" rel="stylesheet"/>
    </head>
    
    <body>
        <div class="container">
            <form id="w-form-signin" class="form-signin">
                <h2 class="form-signin-heading">会员登录</h2>
                <label for="username" class="sr-only">Uid/Username/Email/Mobile</label>
                <input type="text" id="username" class="form-control" placeholder="ID号/用户名/邮箱/手机号" required autofocus/>
                <label for="password" class="sr-only">密码</label>
                <input type="password" id="password" class="form-control" placeholder="密码" required/>
                <label for="captcha" class="sr-only">验证码</label>
                <input type="text" id="captcha" class="form-control" placeholder="验证码" required/>
                <div class="w-captcha-img-box">
					<img id="w-captcha-img" class="img-thumbnail"/>
                    <span id="w-captcha-update">换一个</span>
                </div>
                <button class="btn btn-lg btn-success btn-block" type="submit">登 陆</button>
            </form>
        </div>
        <script src="<?php echo $_W['public_path'];?>/js/jquery.min.js"></script>
        <script src="<?php echo $_W['public_path'];?>/js/md5.min.js"></script>
        <script src="<?php echo $_W['public_path'];?>/js/bootstrap.min.js"></script>
		<script src="<?php echo $_W['public_path'];?>/js/w_captcha.js"></script>
        <?php include W_ROOT_PATH . '/templates/w_modalerror.html.php';?>
        <script type="text/javascript">
        $(document).ready(function() {
            // 让登陆框框不在iframe里显示
        	if(self.parent.frames.length != 0) {
        		self.parent.location = document.location;
        	}
			// 验证码
			$("#w-captcha-img").wCaptchaInit();
			$('#w-captcha-img,#w-captcha-update').click(function(e) {
				$("#w-captcha-img").wCaptchaReset();
			});
        	// 提交表单
            $('#w-form-signin').submit(function() {
                var username = $.trim( $('#username').val() );
                var password = $.trim( $('#password').val() );
                var captcha  = $.trim( $('#captcha').val() );
                if(username == '') {
                    w_b_modal_error("账号不能为空");
                    return false;
                }
                if(password == '') {
                    w_b_modal_error("密码不能为空");
                    return false;
                }
                if(captcha == '') {
                    w_b_modal_error("验证码不能为空");
                    return false;
                }
                password = md5(password);
                $.post('?m=signin&a=signin', {username: username, password: password, captcha: captcha}, function(data) {
                    if(data.status == 1) {
                    	window.location.href = '?m=home';
                    } else {
                    	w_b_modal_error(data.msg);
                    }
                }, 'json');
                return false;
            });
        });
        </script>
    </body>
</html>