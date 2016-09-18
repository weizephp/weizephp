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

header("X-Frame-Options: DENY");

/* ------------------------------------------------- */

include 'includes/w_session_user.inc.php';
include 'includes/w_captcha.class.php';
include 'includes/w_security.class.php';

/* ------------------------------------------------- */

$user_menus = array();
$cur_app_guest_permission = array();

foreach($_W['guest_permission'] as $v) {
    if(strpos($v, W_APP) === 0) {
        $cur_app_guest_permission[] = $v;
    }
}

if(in_array($_W['permission_flag'], $cur_app_guest_permission)) {
    // ...
} else {
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

/* ------------------------------------------------- */

/**
 * 管理员操作日志函数
 */
function w_adminlog($loginfo) {
    global $_W, $WDB;
    
    if(empty($_W['user']['username'])) {
        return false;
    }
    
    $ip  = w_get_client_ip();
    $sql = "INSERT INTO `{$_W['db']['tablepre']}adminlog`(`username`, `logtime`, `ip`, `loginfo`) VALUES ('{$_W['user']['username']}', '".W_TIMESTAMP."', '{$ip}', '{$loginfo}')";
    
    if($WDB->query($sql)) {
        return true;
    } else {
        return false;
    }
}
