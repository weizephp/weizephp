<?php
/**
 * 用户更新
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

if( !isset($_POST['uid']) ) {
    
    /**
     * 用户更新-页面
     */
    $uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0;
    if( $uid < 1 ) {
        w_error('错误，uid索引非法');
    }
    
    $sql = "SELECT * FROM `{$wconfig['db']['tablepre']}user` WHERE `uid`='{$uid}'";
    $user = $wdb->get_row($sql);
    if( empty($user) ) {
        w_error('错误，没有这个用户或者已经被删除');
    }
    
    $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '?m=user&a=list';
    
    $sql   = "SELECT `roleid`, `status`, `rolename` FROM `{$wconfig['db']['tablepre']}role` WHERE 1";
    $arr   = $wdb->get_all($sql);
    $roles = array();
    foreach($arr as $k => $v) {
        if($v['status'] == 1) {
            $v['rolename'] = htmlspecialchars($v['rolename']);
            $roles[] = $v;
        }
    }
    
    include $wconfig['theme_path'] . '/admin/user/update.html.php';
    
} else {
    
    /**
     * 用户更新-数据入库
     */
    
    $formtoken = isset($_POST['formtoken']) ? trim($_POST['formtoken']) : '';
    if( $formtoken != $wuser->formtoken ) {
        exit('{"status":0, "msg":"非法操作"}');
    }
    
    $uid            = isset($_POST['uid']) ? intval($_POST['uid']) : 0;
    $roleid         = isset($_POST['roleid']) ? intval($_POST['roleid']) : 0;
    $password       = isset($_POST['password']) ? trim($_POST['password']) : '';
    $password_again = isset($_POST['password_again']) ? trim($_POST['password_again']) : '';
    $email          = isset($_POST['email']) ? addslashes(trim($_POST['email'])) : '';
    $mobile         = isset($_POST['mobile']) ? addslashes(trim($_POST['mobile'])) : 0;
    $status         = isset($_POST['status']) ? intval($_POST['status']) : 0;
    $realname       = isset($_POST['realname']) ? addslashes(trim($_POST['realname'])) : '';
    $nickname       = isset($_POST['nickname']) ? addslashes(trim($_POST['nickname'])) : '';
    
    if( in_array($uid, $wconfig['founder']) && !in_array($wuser->uid, $wconfig['founder']) ) {
        exit('{"status":0, "msg":"您没有操作创始人信息的权限！"}');
    }
    
    if( ($wuser->uid == $uid) && ($wuser->roleid != $roleid) ) {
        exit('{"status":0, "msg":"您不能修改自己角色！"}');
    }
    
    $sql  = "SELECT * FROM `{$wconfig['db']['tablepre']}user` WHERE `uid`='{$uid}'";
    $user = $wdb->get_row($sql);
    if( empty($user) ) {
        exit('{"status":0, "msg":"错误，没有这个用户或者已经被删除！"}');
    }
    
    if( $user['email'] != $email ) {
        $email_result = $wuser->check_email($email);
        switch($email_result) {
            case -9:
                exit('{"status":0, "msg":"E-mail格式有误！"}');
                break;
            case -10:
                exit('{"status":0, "msg":"该E-mail已经被注册！"}');
                break;
        }
    }
    
    if( !empty($password) ) {
        $password = addslashes(trim(stripslashes($password)));
        if( $password != $password_again ) {
            exit('{"status":0, "msg":"两次输入的密码不一致！"}');
        }
        $password_result = $wuser->check_password($password);
        switch($password_result) {
            case -9:
                exit('{"status":0, "msg":"密码不能小于6个字符！"}');
                break;
            case -10:
                exit('{"status":0, "msg":"密码不能大于15个字符！"}');
                break;
        }
        $salt     = $wuser->salt();
        $password = $wuser->create_password($password, $salt);
    }
    
    if( !empty($mobile) ) {
        $mobile_result = $wuser->check_email($mobile);
        switch($mobile_result) {
            case -12:
                exit('{"status":0, "msg":"手机格式不正确！"}');
                break;
            case -13:
                exit('{"status":0, "msg":"手机已经被占用！"}');
                break;
        }
    }
    
    if( !empty($password) && !empty($salt) ) {
        $sql = "UPDATE `{$wconfig['db']['tablepre']}user` SET `email`='{$email}', `mobile`='{$mobile}', `password`='{$password}', `status`='{$status}', `roleid`='{$roleid}', `salt`='{$salt}', `realname`='{$realname}', `nickname`='{$nickname}' WHERE `uid`='{$uid}'";
    } else {
        $sql = "UPDATE `{$wconfig['db']['tablepre']}user` SET `email`='{$email}', `mobile`='{$mobile}', `status`='{$status}', `roleid`='{$roleid}', `realname`='{$realname}', `nickname`='{$nickname}' WHERE `uid`='{$uid}'";
    }
    if( $wdb->query($sql) ) {
        $wuser->actionlog('更新用户：'. $user['username']);
        exit('{"status":1, "msg":"操作成功！"}');
    } else {
        exit('{"status":0, "msg":"操作失败！"}');
    }
    
}
