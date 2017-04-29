<?php
/**
 * 角色删除
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

exit('{"status":0, "msg":"哇喔，系统不允许删除的啦。。如果某个角色不想用，则不启用就行了。"}');

/* 如果需要开启删除功能，需要打开当前块注释
$formtoken = isset($_GET['formtoken']) ? trim($_GET['formtoken']) : '';
if( $formtoken != $wuser->formtoken ) {
    exit('{"status":0, "msg":"非法操作"}');
}

$roleid = isset($_GET['roleid']) ? intval($_GET['roleid']) : 0;

if( $roleid < 1 ) {
    exit('{"status":0, "msg":"非法的角色ID"}');
}
if( $roleid == 1 ) {
    exit('{"status":0, "msg":"不能删除超级管理员角色"}');
}
if( $roleid == $wuser->roleid ) {
    exit('{"status":0, "msg":"不能删除自己的角色"}');
}

$sql = "SELECT `roleid`, `rolename` FROM `{$wconfig['db']['tablepre']}role` WHERE `roleid`='{$roleid}'";
$old = $wdb->get_row($sql);
if( empty($old) ) {
    exit('{"status":0, "msg":"错误，数据库没有您要删除的角色"}');
}

$sql  = "SELECT `uid`, `username`, `roleid` FROM `{$wconfig['db']['tablepre']}user` WHERE `roleid`='{$roleid}'";
$user = $wdb->get_row($sql);
if( !empty($user) ) {
    exit('{"status":0, "msg":"角色已经被用户使用，不能删除"}');
}

$sql = "DELETE FROM `{$wconfig['db']['tablepre']}role` WHERE `roleid`='{$roleid}'";
if( $wdb->query($sql) ) {
    $wuser->actionlog('删除角色：'. $old['rolename']);
    exit('{"status":1, "msg":"操作成功"}');
} else {
    exit('{"status":0, "msg":"操作失败"}');
}
*/
