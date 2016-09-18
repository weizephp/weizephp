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

if(!isset($_POST['uid'])) {
    
    /**
     * 用户更新 - 页面
     */
    
    // 接收UID
    $uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0;
    if($uid < 1) {
        w_errormessage('错误，UID索引非法');
    }
    
    // 获取用户信息
    $sql = "SELECT * FROM `{$_W['db']['tablepre']}users` WHERE `uid`='{$uid}'";
    $user = $WDB->get_row($sql);
    if(empty($user)) {
        w_errormessage('错误，没有这个用户或者已经被删除');
    }
    
    // 生产 formtoken
    $formtoken = w_security::set_formtoken();
    
    // 返回上一页的重定向URL
    $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '?m=article&a=list';
    
    // 获取角色
    $sql   = "SELECT `roleid`, `status`, `rolename` FROM `{$_W['db']['tablepre']}roles` WHERE 1";
    $arr   = $WDB->get_all($sql);
    $roles = array();
    foreach($arr as $k => $v) {
        if($v['status'] == 1) {
            $v['rolename'] = htmlspecialchars($v['rolename']);
            $roles[] = $v;
        }
    }
    
    include $_W['template_path'].'/user/update.html.php';
    
} else {
    
    /**
     * 用户更新 - 数据入库
     */
    
    // formtoken验证
    $formtoken = isset($_POST['formtoken']) ? $_POST['formtoken'] : '';
    $result    = w_security::check_formtoken($formtoken);
    if($result == false) {
        exit('{"status":0, "msg":"非法操作"}');
    }
    
    // 接收参数
    $uid            = isset($_POST['uid']) ? intval($_POST['uid']) : 0;
    $roleid         = isset($_POST['roleid']) ? intval($_POST['roleid']) : 0;
    $password       = isset($_POST['password']) ? trim($_POST['password']) : '';
    $password_again = isset($_POST['password_again']) ? trim($_POST['password_again']) : '';
    $email          = isset($_POST['email']) ? addslashes(trim($_POST['email'])) : '';
    $mobile         = isset($_POST['mobile']) ? addslashes(trim($_POST['mobile'])) : 0;
    $status         = isset($_POST['status']) ? intval($_POST['status']) : 0;
    $realname       = isset($_POST['realname']) ? addslashes(trim($_POST['realname'])) : '';
    $nickname       = isset($_POST['nickname']) ? addslashes(trim($_POST['nickname'])) : '';
    
    // 是否允许操作创始人信息
    if(in_array($uid, $_W['founder']) && !in_array($_W['user']['uid'], $_W['founder'])) {
        exit('{"status":0, "msg":"您没有操作创始人信息的权限！"}');
    }
    
    // 不能修改自己的角色
    if(($_W['user']['uid'] == $uid) && ($_W['user']['roleid'] != $roleid)) {
        exit('{"status":0, "msg":"您不能修改自己角色！"}');
    }
    
    // 获取用户信息
    $sql  = "SELECT * FROM `{$_W['db']['tablepre']}users` WHERE `uid`='{$uid}'";
    $user = $WDB->get_row($sql);
    if(empty($user)) {
        exit('{"status":0, "msg":"错误，没有这个用户或者已经被删除！"}');
    }
    
    // email检查
    if($user['email'] != $email) {
        $email_result = w_user::check_email($email);
        switch($email_result) {
            case -9:
                exit('{"status":0, "msg":"E-mail格式有误！"}');
                break;
            case -10:
                exit('{"status":0, "msg":"该E-mail已经被注册！"}');
                break;
        }
    }
    
    // 密码检查
    if(!empty($password)) {
        $password = addslashes(trim(stripslashes($password)));
        if($password != $password_again) {
            exit('{"status":0, "msg":"两次输入的密码不一致！"}');
        }
        $password_result = w_user::check_password($password);
        switch($password_result) {
            case -9:
                exit('{"status":0, "msg":"密码不能小于6个字符！"}');
                break;
            case -10:
                exit('{"status":0, "msg":"密码不能大于15个字符！"}');
                break;
        }
        $salt     = w_user::salt();
        $password = w_user::create_password($password, $salt);
    }
    
    // 手机检查
    if(!empty($mobile)) {
        $mobile_result = w_user::check_email($mobile);
        switch($mobile_result) {
            case -12:
                exit('{"status":0, "msg":"手机格式不正确！"}');
                break;
            case -13:
                exit('{"status":0, "msg":"手机已经被占用！"}');
                break;
        }
    }
    
    // 把更新好的数据保存进数据库
    if(!empty($password) && !empty($salt)) {
        $sql = "UPDATE `{$_W['db']['tablepre']}users` SET `email`='{$email}', `mobile`='{$mobile}', `password`='{$password}', `status`='{$status}', `roleid`='{$roleid}', `salt`='{$salt}', `realname`='{$realname}', `nickname`='{$nickname}' WHERE `uid`='{$uid}'";
    } else {
        $sql = "UPDATE `{$_W['db']['tablepre']}users` SET `email`='{$email}', `mobile`='{$mobile}', `status`='{$status}', `roleid`='{$roleid}', `realname`='{$realname}', `nickname`='{$nickname}' WHERE `uid`='{$uid}'";
    }
    
    if($WDB->query($sql)) {
        $formtoken = w_security::set_formtoken();
        w_adminlog('更新用户：'. $user['username']);
        exit('{"status":1, "msg":"操作成功！", "formtoken":"'. $formtoken .'"}');
    } else {
        exit('{"status":0, "msg":"操作失败！"}');
    }
    
}
