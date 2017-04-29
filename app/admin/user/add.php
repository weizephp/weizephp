<?php
/**
 * 用户添加
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

if( !isset($_POST['username']) ) {
    
    /**
     * 用户添加-页面
     */
    $sql   = "SELECT `roleid`, `status`, `rolename` FROM `{$wconfig['db']['tablepre']}role` WHERE 1";
    $arr   = $wdb->get_all($sql);
    $roles = array();
    foreach($arr as $k => $v) {
        if($v['status'] == 1) {
            $v['rolename'] = htmlspecialchars($v['rolename']);
            $roles[] = $v;
        }
    }
    include $wconfig['theme_path'] . '/admin/user/add.html.php';
    
} else {
    
    /**
     * 用户添加-数据入库
     */
    $formtoken = isset($_POST['formtoken']) ? trim($_POST['formtoken']) : '';
    if( $formtoken != $wuser->formtoken ) {
        exit('{"status":0, "msg":"非法操作"}');
    }
    
    $username       = isset($_POST['username']) ? trim($_POST['username']) : '';
    $username_again = isset($_POST['username_again']) ? trim($_POST['username_again']) : '';
    $password       = isset($_POST['password']) ? trim($_POST['password']) : '';
    $password_again = isset($_POST['password_again']) ? trim($_POST['password_again']) : '';
    $email          = isset($_POST['email']) ? trim($_POST['email']) : '';
    $email_again    = isset($_POST['email_again']) ? trim($_POST['email_again']) : '';
    $roleid         = isset($_POST['roleid']) ? intval($_POST['roleid']) : 0;
    $mobile         = isset($_POST['mobile']) ? trim($_POST['mobile']) : 0;
    
    if( ($roleid == 1) && !in_array($wuser->uid, $wconfig['founder']) ) {
        exit('{"status":0, "msg":"您不是创始人，不能创建超级管理员！"}');
    }
    
    $result = $wuser->register($username, $username_again, $password, $password_again, $email, $email_again, $roleid, $mobile);
    
    switch($result) {
        case 0:
            exit('{"status":0, "msg":"未知错误"}');
            break;
        case -1:
            exit('{"status":0, "msg":"用户名只允许使用字母、数字、下划线，且必须需以字母开头"}');
            break;
        case -2:
            exit('{"status":0, "msg":"用户名不能小于3个字符"}');
            break;
        case -3:
            exit('{"status":0, "msg":"用户名不能大于15个字符"}');
            break;
        case -4:
            exit('{"status":0, "msg":"该用户名已经被注册"}');
            break;
        case -5:
            exit('{"status":0, "msg":"两次输入的用户名不一致"}');
            break;
        case -6:
            exit('{"status":0, "msg":"两次输入的密码不一致"}');
            break;
        case -7:
            exit('{"status":0, "msg":"密码不能小于6个字符"}');
            break;
        case -8:
            exit('{"status":0, "msg":"密码不能大于15个字符"}');
            break;
        case -9:
            exit('{"status":0, "msg":"E-mail格式有误"}');
            break;
        case -10:
            exit('{"status":0, "msg":"该E-mail已经被注册"}');
            break;
        case -11:
            exit('{"status":0, "msg":"两次输入的邮箱不一致"}');
            break;
        case -12:
            exit('{"status":0, "msg":"手机格式不正确"}');
            break;
        case -13:
            exit('{"status":0, "msg":"手机已经被占用"}');
            break;
    }
    
    if( $result > 0 ) {
        $wuser->actionlog('添加用户：'. $username);
        exit('{"status":1, "msg":"操作成功", "uid":"'. $result .'"}');
    } else {
        exit('{"status":0, "msg":"未知错误"}');
    }
    
}
