<?php
/**
 * 后台用户 session 登陆方式
 */
if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

// 接收数据
$username   = isset($_POST['username']) ? addslashes(trim($_POST['username'])) : "";
$password   = isset($_POST['password']) ? addslashes(trim($_POST['password'])) : ""; // 这里是客户端MD5之后的密码
$captchakey = isset($_POST['captchakey']) ? addslashes(trim($_POST['captchakey'])) : "";
$captchaval = isset($_POST['captchaval']) ? addslashes(trim($_POST['captchaval'])) : "";

// 检查数据输入
if(empty($username)) {
    exit('{"status":0, "msg":"账号不能为空"}');
}
if(empty($password)) {
    exit('{"status":0, "msg":"密码不能为空"}');
}
if(empty($captchakey)) {
   exit('{"status":0, "msg":"请先显示验证码，再输入"}');
}
if(empty($captchaval)) {
    exit('{"status":0, "msg":"验证码值不能为空"}');
}

// 检查验证码
include W_ROOT_PATH . '/lib/w_captcha.class.php';
$captcha = new w_captcha();

$captcha_check = $captcha->check($captchakey, $captchaval);
switch($captcha_check) {
    case -1:
        exit('{"status":0, "msg":"验证码无效或已过期，请换一个"}');
        break;
    case 0:
        $captcha->check_error_count($captchakey); // [!]为了安全考虑，需要更新检查计数，如果验证码输入错误超过6次，验证码则失效
        exit('{"status":0, "msg":"验证码输入错误"}');
        break;
}

// 登录验证
$login_result = $wuser->login($username, $password);

if(($login_result['status'] == -15) || ($login_result['status'] == -16)) {
    $time_str = $login_result['timeleft'] < 60 ? $login_result['timeleft']."秒" : ceil($login_result['timeleft']/60)."分钟";
}

switch ($login_result['status']) {
    case 1:
        $captcha->succeed($captchakey); // [!]为了安全考虑，验证码验证完成后，必须锁定，让验证码失效
        exit('{"status":1, "msg":"ok"}');
        break;
    case -14:
        exit('{"status":0, "msg":"登录账号不正确"}');
        break;
    case -15:
        exit('{"status":0, "msg":"IP登录错误次数过多,请'.$time_str.'后再试"}');
        break;
    case -16:
        exit('{"status":0, "msg":"账号登录错误次数过多,请'.$time_str.'后再试"}');
        break;
    case -17:
        exit('{"status":0, "msg":"用户不存在,或者已经被删除,您还可以尝试'.$login_result['remaincount'].'次"}');
        break;
    case -18:
        exit('{"status":0, "msg":"登陆密码错误,您还可以尝试'.$login_result['remaincount'].'次"}');
        break;
    default:
        exit('{"status":0, "msg":"没有任何操作"}');
}