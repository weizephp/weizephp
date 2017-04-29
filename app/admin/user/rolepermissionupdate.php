<?php
/**
 * 角色权限更新
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

// formtoken验证
$formtoken = isset($_POST['formtoken']) ? trim($_POST['formtoken']) : '';
if( $formtoken != $wuser->formtoken ) {
    exit('{"status":0, "msg":"非法操作"}');
}

// 接收参数
$roleid     = isset($_POST['roleid']) ? intval($_POST['roleid']): 0;
$permission = isset($_POST['permission']) && is_array($_POST['permission']) ? w_addslashes($_POST['permission']) : array();

// 输入安全检查
if( $roleid < 1 ) {
    exit('{"status":0, "msg":"非法的角色索引ID"}');
}
//if( empty($permission) ) {
//    exit('{"status":0, "msg":"必须选择权限"}');
//}
if( $roleid == 1 ) {
    exit('{"status":0, "msg":"超级管理员角色不能操作"}');
}
if( $roleid == $wuser->roleid ) {
    exit('{"status":0, "msg":"不能修改自己的角色"}');
}

// 获取角色信息
$sql  = "SELECT `roleid`, `rolename` FROM `{$wconfig['db']['tablepre']}role` WHERE `roleid`='{$roleid}'";
$role = $wdb->get_row($sql);
if( empty($role) ) {
    exit('{"status":0, "msg":"错误，没有这个角色，不用更新"}');
}

// 拼接权限数据为字符串
$permission_str = implode(',', $permission);

// 保存入库
$sql = "UPDATE `{$wconfig['db']['tablepre']}role` SET `permission`='{$permission_str}' WHERE `roleid`='{$roleid}'";
if( $wdb->query($sql) ) {
    unset($permission, $permission_str);
    $wuser->actionlog('分配权限给：'. $role['rolename']);
    exit('{"status":1, "msg":"操作成功"}');
} else {
    exit('{"status":0, "msg":"操作失败"}');
}
