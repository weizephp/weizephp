<?php
/**
 * 网站配置更新
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

$formtoken = isset($_POST['formtoken']) ? trim($_POST['formtoken']) : '';
if( $formtoken != $wuser->formtoken ) {
    exit('{"status":0, "msg":"非法操作"}');
}

$_POST = w_addslashes($_POST);

foreach($_POST as $key => $val) {
    $sql = "UPDATE `{$wconfig['db']['tablepre']}config` SET `cvalue`='{$val}' WHERE `ckey`='{$key}'";
    $wdb->query($sql);
}

$wuser->actionlog("更新了站点配置");

exit('{"status":1, "msg":"更新成功"}');
