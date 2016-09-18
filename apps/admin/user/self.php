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

if(!isset($_POST['nickname'])) {
    
    /**
     * 个人信息修改 - 页面
     */
    
    // 获取用户信息
    $sql  = "SELECT * FROM `{$_W['db']['tablepre']}users` WHERE `uid`='{$_W['user']['uid']}'";
    $user = $WDB->get_row($sql);
    if(empty($user)) {
        w_errormessage('错误，没有这个用户或者已经被删除');
    }
    
    // 生产 formtoken
    $formtoken = w_security::set_formtoken();
    
    // 获取角色
    $sql  = "SELECT `roleid`, `status`, `rolename` FROM `{$_W['db']['tablepre']}roles` WHERE `roleid`='{$_W['user']['roleid']}'";
    $role = $WDB->get_row($sql);
    $user['rolename'] = !empty($role) ? $role['rolename'] : '未定义角色';
    
    include $_W['template_path'].'/user/self.html.php';
    
} else {
    
    /**
     * 个人信息修改 - 数据入库
     */
    
    // formtoken验证
    $formtoken = isset($_POST['formtoken']) ? $_POST['formtoken'] : '';
    $result    = w_security::check_formtoken($formtoken);
    if($result == false) {
        exit('{"status":0, "msg":"非法操作"}');
    }
    
    // 接收参数
    $oldpassword    = isset($_POST['oldpassword']) ? trim($_POST['oldpassword']) : '';
    $password       = isset($_POST['password']) ? trim($_POST['password']) : '';
    $password_again = isset($_POST['password_again']) ? trim($_POST['password_again']) : '';
    $nickname       = isset($_POST['nickname']) ? addslashes(trim($_POST['nickname'])) : '';
    
    // 密码修改
    if(!empty($password)) {
        // 旧密码检查
        $sql = "SELECT `uid`, `password`, `salt` FROM `{$_W['db']['tablepre']}users` WHERE `uid`='{$_W['user']['uid']}'";
        $row = $WDB->get_row($sql);
        $oldpassword_md5 = w_user::create_password($oldpassword, $row['salt']);
        if($row['password'] != $oldpassword_md5) {
            exit('{"status":0, "msg":"旧密码错误！"}');
        }
        // 新密码检查
        $password = addslashes(trim(stripslashes($password)));
        if($password != $password_again) {
            exit('{"status":0, "msg":"新密码两次输入不一致！"}');
        }
        $password_result = w_user::check_password($password);
        switch($password_result) {
            case -9:
                exit('{"status":0, "msg":"新密码不能小于6个字符！"}');
                break;
            case -10:
                exit('{"status":0, "msg":"新密码不能大于15个字符！"}');
                break;
        }
        $salt     = w_user::salt();
        $password = w_user::create_password($password, $salt);
    }
    
    // 把更新好的数据保存进数据库
    if(!empty($password) && !empty($salt)) {
        $sql = "UPDATE `{$_W['db']['tablepre']}users` SET `password`='{$password}', `salt`='{$salt}', `nickname`='{$nickname}' WHERE `uid`='{$_W['user']['uid']}'";
    } else {
        $sql = "UPDATE `{$_W['db']['tablepre']}users` SET `nickname`='{$nickname}' WHERE `uid`='{$_W['user']['uid']}'";
    }
    
    if($WDB->query($sql)) {
        $formtoken = w_security::set_formtoken();
        w_adminlog('修改自己的信息');
        if(!empty($password) && !empty($salt)) {
            w_user::session_logout($_W['session']['sid']);// 如果修改了密码，就要重新登陆
            exit('{"status":1, "msg":"操作成功！", "logout":"yes", "formtoken":"'. $formtoken .'"}');
        } else {
            exit('{"status":1, "msg":"操作成功！", "logout":"no", "formtoken":"'. $formtoken .'"}');
        }
    } else {
        exit('{"status":0, "msg":"操作失败！"}');
    }
    
}
