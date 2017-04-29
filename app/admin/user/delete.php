<?php
/**
 * 用户删除
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

// [*]^_^不允许删除的啦
exit('{"status":0, "msg":"^_^系统不允许删除，如果不想使用了，可以修改为不启用"}');

/*
$formtoken = isset($_GET['formtoken']) ? trim($_GET['formtoken']) : '';
if( $formtoken != $wuser->formtoken ) {
    exit('{"status":0, "msg":"非法操作"}');
}

$uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0;

if( $uid <= 0 ) {
    exit('{"status":0, "msg":"uid必须大于0！"}');
}

if( in_array($uid, $wconfig['founder']) ) {
    exit('{"status":0, "msg":"不允许删除创始人账号！"}');
}

$sql = "SELECT `username` FROM `{$wconfig['db']['tablepre']}user` WHERE `uid`='{$uid}'";
$row = $wdb->get_row($sql);

$sql = "DELETE FROM `{$wconfig['db']['tablepre']}user` WHERE `uid`='{$uid}'";
if( $wdb->query($sql) ) {
	// 这里还要删除用户的附件等数据...
    $wuser->actionlog("删除{$row['username']}用户");
    exit('{"status":1, "msg":"删除成功！"}');
} else {
    exit('{"status":0, "msg":"删除有误！"}');
}
*/
