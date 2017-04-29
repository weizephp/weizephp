<?php
/**
 * 角色更新
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

$formtoken = isset($_POST['formtoken']) ? trim($_POST['formtoken']) : '';
if( $formtoken != $wuser->formtoken ) {
    exit('{"status":0, "msg":"非法操作"}');
}

$roleid   = isset($_POST['roleid']) ? intval($_POST['roleid']) : 0;
$status   = isset($_POST['status']) && in_array($_POST['status'], array(0,1)) ? $_POST['status'] : 0;
$rolename = isset($_POST['rolename']) ? addslashes(trim($_POST['rolename'])) : '';

if( $roleid < 1 ) {
    exit('{"status":0, "msg":"角色索引ID非法"}');
}

if( empty($rolename) ) {
    exit('{"status":0, "msg":"角色名称不能为空"}');
}

if( ($roleid == 1) && ($wuser->uid != 1) ) {
    exit('{"status":0, "msg":"您没有权限修改超级管理员角色"}');
}

if( $roleid == $wuser->roleid ) {
    exit('{"status":0, "msg":"不能修改自己的角色"}');
}

$sql = "SELECT `roleid`, `rolename` FROM `{$wconfig['db']['tablepre']}role` WHERE `roleid`='{$roleid}'";
$old = $wdb->get_row($sql);
if( empty($old) ) {
    exit('{"status":0, "msg":"错误，数据库没有您要修改的角色"}');
}

$sql = "UPDATE `{$wconfig['db']['tablepre']}role` SET `status`='{$status}', `rolename`='{$rolename}' WHERE `roleid`='{$roleid}'";
if( $wdb->query($sql) ) {
    if( $old['rolename'] != $rolename ) {
        $wuser->actionlog('把角色“'. $old['rolename'] .'”更新为：'. $rolename);
    } else {
        $wuser->actionlog('更新角色：'. $rolename);
    }
    exit('{"status":1, "msg":"操作成功"}');
} else {
    exit('{"status":0, "msg":"操作失败"}');
}
