<?php
/**
 * 角色权限分配
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

// 接收roleid
$roleid = isset($_GET['roleid']) ? intval($_GET['roleid']) : 0;
if( $roleid < 1 ) {
    w_error('角色ID非法');
}

// 获取角色数据
$sql  = "SELECT * FROM `{$wconfig['db']['tablepre']}role` WHERE `roleid`='{$roleid}'";
$role = $wdb->get_row($sql);
if( empty($role) ) {
    w_errormessage('错误，没有这个角色');
}
foreach($role as $k => $v) {
    $role[$k] = htmlspecialchars($v);
}

// 超级管理员权限变量定义
$admin_permissions = array();

// 菜单层级提取
$apps    = array();
$modules = array();
$actions = array();

foreach($wmenu as $k =>$v) {
    $admin_permissions[] = $k;
    $arr = explode('/', $k);
    switch(count($arr)) {
        case 1:
            $apps[$k] = $v;
            break;
        case 2:
            $modules[$k] = $v;
            break;
        case 3:
            $actions[$k] = $v;
            break;
    }
}

foreach($modules as $mk => $mv) {
    foreach($actions as $ak => $av) {
        if(strpos($ak, $mk) === 0) {
            $modules[$mk]['children'][$ak] = $av;
        }
    }
}

foreach($apps as $k => $v) {
    foreach($modules as $mk => $mv) {
        if(strpos($mk, $k) === 0) {
            $apps[$k]['children'][$mk] = $mv;
        }
    }
}

$menus = $apps;

// 分割权限字符串成为数组
if($roleid == 1) {
    $permissions = $admin_permissions;
} else {
    $permissions = explode(',', $role['permission']);
}

// 删除用完了得变量
unset($admin_permissions, $apps, $modules, $actions);

// 包含模板
include $wconfig['theme_path'] . '/admin/user/rolepermissionassign.html.php';
