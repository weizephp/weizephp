<?php
/**
 * 管理日志删除
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

$formtoken = isset($_POST['formtoken']) ? trim($_POST['formtoken']) : '';
if( $formtoken != $wuser->formtoken ) {
    exit('{"status":0, "msg":"非法操作"}');
}

if( !in_array($wuser->uid, $wconfig['founder']) ) {
    exit('{"status":0, "msg":"只允许创始人删除管理日志"}');
}

if( empty($_POST['logid']) ) {
    exit('{"status":0, "msg":"没有选择要删除的管理日志"}');
}

if( !is_array($_POST['logid']) ) {
    exit('{"status":0, "msg":"删除管理日志操作无效"}');
}

foreach($_POST['logid'] as $v) {
    $logid = intval($v);
    $sql   = "DELETE FROM `{$wconfig['db']['tablepre']}adminlog` WHERE `logid`='{$logid}'";
    $wdb->query($sql);
}

$wuser->actionlog("管理日志删除");

exit('{"status":1, "msg":"管理日志删除成功"}');
