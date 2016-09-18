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

// formtoken验证
$result = w_security::check_formtoken($_POST['formtoken']);
if($result == false) {
    exit('{"status":0, "msg":"非法操作"}');
}

// 接收参数
$roleid     = isset($_POST['roleid']) ? intval($_POST['roleid']): 0;
$permission = isset($_POST['permission']) && is_array($_POST['permission']) ? w_addslashes($_POST['permission']) : array();

// 输入安全检查
if($roleid < 1) {
    exit('{"status":0, "msg":"非法的角色索引ID"}');
}
if(empty($permission)) {
    exit('{"status":0, "msg":"必须选择权限"}');
}
if($roleid == 1) {
    exit('{"status":0, "msg":"超级管理员角色不能操作"}');
}
if($roleid == $_W['user']['roleid']) {
    exit('{"status":0, "msg":"不能修改自己的角色"}');
}

// 获取角色信息
$sql  = "SELECT `roleid`, `rolename` FROM `{$_W['db']['tablepre']}roles` WHERE `roleid`='{$roleid}'";
$role = $WDB->get_row($sql);
if(empty($role)) {
    exit('{"status":0, "msg":"错误，没有这个角色，不用更新"}');
}

// 拼接权限数据为字符串
$permission_str = implode(',', $permission);

// 保存入库
$sql = "UPDATE `{$_W['db']['tablepre']}roles` SET `permissions`='{$permission_str}' WHERE `roleid`='{$roleid}'";
if($WDB->query($sql)) {
    unset($permission, $permission_str);
    $formtoken = w_security::set_formtoken();
    w_adminlog('分配权限给：'. $role['rolename']);
    exit('{"status":1, "msg":"操作成功", "formtoken":"'. $formtoken .'"}');
} else {
    exit('{"status":0, "msg":"操作失败"}');
}
