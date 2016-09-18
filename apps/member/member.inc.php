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

/**
 * 载入用户会话文件、验证码类、安全类
 */

include 'includes/w_session_user.inc.php';
include 'includes/w_captcha.class.php';
include 'includes/w_security.class.php';

/**
 * 权限验证
 */

$user_menus = array();
$cur_app_guest_permission = array();

foreach($_W['guest_permission'] as $v) {
    if(strpos($v, W_APP) === 0) {
        $cur_app_guest_permission[] = $v;
    }
}

if(in_array($_W['permission_flag'], $cur_app_guest_permission)) {
    // 游客和用户访问的程序逻辑（这里没什么要处理的，留空了）...
} else {
    // 如果是要登录的用户才能访问，这里做权限判断
	if($_W['session']['uid'] == 0) {
        if($_W['ajaxrequest'] == 1) {
            exit('{"status":0, "msg":"没有登陆，或者登陆已经失效"}');
        } else {
            header("Location: ?m=signin");
            exit;
        }
    }
    if(w_user::check_access_permission() == false) {
        if($_W['ajaxrequest'] == 1) {
            exit('{"status":0, "msg":"没权限操作"}');
        } else {
            w_errormessage("没权限操作");
            exit;
        }
    }
    $user_menus = w_user::get_user_menus();
}

unset($cur_app_guest_permission);
