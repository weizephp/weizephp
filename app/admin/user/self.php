<?php
/**
 * 个人信息修改
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

if( !isset($_POST['nickname']) ) {
    
    /**
     * 个人信息修改-页面
     */
    $sql  = "SELECT * FROM `{$wconfig['db']['tablepre']}user` WHERE `uid`='{$wuser->uid}'";
    $user = $wdb->get_row($sql);
    if( empty($user) ) {
        w_error('错误，没有这个用户或者已经被删除');
    }
    
    $sql  = "SELECT `roleid`, `status`, `rolename` FROM `{$wconfig['db']['tablepre']}role` WHERE `roleid`='{$wuser->uid}'";
    $role = $wdb->get_row($sql);
    
    $user['rolename'] = !empty($role) ? $role['rolename'] : '未定义角色';
    
    include $wconfig['theme_path'] . '/admin/user/self.html.php';
    
} else {
    
    /**
     * 个人信息修改-数据入库
     */
    $formtoken = isset($_POST['formtoken']) ? trim($_POST['formtoken']) : '';
    if( $formtoken != $wuser->formtoken ) {
        exit('{"status":0, "msg":"非法操作"}');
    }
    
    $oldpassword    = isset($_POST['oldpassword']) ? trim($_POST['oldpassword']) : '';
    $password       = isset($_POST['password']) ? trim($_POST['password']) : '';
    $password_again = isset($_POST['password_again']) ? trim($_POST['password_again']) : '';
    $nickname       = isset($_POST['nickname']) ? addslashes(trim($_POST['nickname'])) : '';
    
    if( !empty($password) ) {
        $sql = "SELECT `uid`, `password`, `salt` FROM `{$wconfig['db']['tablepre']}user` WHERE `uid`='{$wuser->uid}'";
        $row = $wdb->get_row($sql);
        
        $oldpassword_md5 = $wuser->create_password($oldpassword, $row['salt']);
        if( $row['password'] != $oldpassword_md5 ) {
            exit('{"status":0, "msg":"旧密码错误！"}');
        }
        
        $password = addslashes(trim(stripslashes($password)));
        if( $password != $password_again ) {
            exit('{"status":0, "msg":"新密码两次输入不一致！"}');
        }
        
        $password_result = $wuser->check_password($password);
        switch($password_result) {
            case -9:
                exit('{"status":0, "msg":"新密码不能小于6个字符！"}');
                break;
            case -10:
                exit('{"status":0, "msg":"新密码不能大于15个字符！"}');
                break;
        }
        $salt     = $wuser->salt();
        $password = $wuser->create_password($password, $salt);
    }
    
    if( !empty($password) && !empty($salt) ) {
        $sql = "UPDATE `{$wconfig['db']['tablepre']}user` SET `password`='{$password}', `salt`='{$salt}', `nickname`='{$nickname}' WHERE `uid`='{$wuser->uid}'";
    } else {
        $sql = "UPDATE `{$wconfig['db']['tablepre']}user` SET `nickname`='{$nickname}' WHERE `uid`='{$wuser->uid}'";
    }
    
    if( $wdb->query($sql) ) {
        $wuser->actionlog('修改自己的信息');
        if( !empty($password) && !empty($salt) ) {
            $wuser->logout(); // 如果修改了密码，就要重新登陆
            exit('{"status":1, "msg":"操作成功！", "logout":"yes"}');
        } else {
            exit('{"status":1, "msg":"操作成功！", "logout":"no"}');
        }
    } else {
        exit('{"status":0, "msg":"操作失败！"}');
    }
    
}
