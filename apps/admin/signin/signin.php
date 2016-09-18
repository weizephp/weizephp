<?php
/**
 * +----------------------------------------------------------------------
 * | WeizePHP framework
 * +----------------------------------------------------------------------
 * | Copyright (c) 2013-2113 http://weizephp.75hh.com/ All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: Ze Wei <E-mail:weizesw@gmail.com> <QQ:310472156>
 * +----------------------------------------------------------------------
 */

if(!defined('IN_WEIZEPHP')){exit('Access Denied');}

$username = isset($_POST['username']) ? addslashes(trim($_POST['username'])) : "";
$password = isset($_POST['password']) ? addslashes(trim($_POST['password'])) : "";
$captcha  = isset($_POST['captcha']) ? addslashes(trim($_POST['captcha'])) : "";

if(empty($username)) {
    exit('{"status":0, "msg":"账号不能为空"}');
}
if(empty($password)) {
    exit('{"status":0, "msg":"密码不能为空"}');
}
if(empty($captcha)) {
    exit('{"status":0, "msg":"验证码不能为空"}');
}

$ckey = isset($_COOKIE[ $_W['cookie']['prefix'].'ckey' ]) ? $_COOKIE[ $_W['cookie']['prefix'].'ckey' ] : '';
$cid  = w_captcha::check_code($ckey, $captcha);
if($cid == false) {
    exit('{"status":0, "msg":"验证码错误"}');
}

$result = w_user::login($username, $password);

if(($result['status'] == -15) || ($result['status'] == -16)) {
    $time_str = $result['timeleft'] < 60 ? $result['timeleft']."秒" : ceil($result['timeleft']/60)."分钟";
}

switch ($result['status']) {
    case 1:
        // [!]把uid写进session,同时为了安全考虑,必须重置验证码
        w_captcha::verified($cid);
        $sql = "UPDATE `{$_W['db']['tablepre']}sessions` SET `uid`='{$_W['user']['uid']}' WHERE `sid`='{$_W['session']['sid']}'";
        $WDB->query($sql);
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
        exit('{"status":0, "msg":"用户不存在,或者已经被删除,您还可以尝试'.$result['remaincount'].'次"}');
        break;
    case -18:
        exit('{"status":0, "msg":"登陆密码错误,您还可以尝试'.$result['remaincount'].'次"}');
        break;
    default:
        exit('{"status":0, "msg":"没有任何操作"}');
}
