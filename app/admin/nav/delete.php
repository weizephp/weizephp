<?php
/**
 * 导航菜单-删除
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

$formtoken = isset($_GET['formtoken']) ? trim($_GET['formtoken']) : '';
if( $formtoken != $wuser->formtoken ) {
    exit('{"status":0, "msg":"非法操作"}');
}

exit('{"status":0, "msg":"^_^系统不允许删除，如果不想使用了，可以修改为不显示"}');

/*
$nid = isset($_GET['nid']) ? intval($_GET['nid']) : 0;
if( $nid < 1 ) {
    exit('{"status":0, "msg":"错误，非法索引ID"}');
}

$sql = "SELECT * FROM `{$wconfig['db']['tablepre']}nav` WHERE `nid`='{$nid}'";
$row = $wdb->get_row($sql);
if( empty($row) ) {
    exit('{"status":0, "msg":"错误，数据库不存在您要删除的数据"}');
}

if( !empty($row['pic']) && is_file($row['pic']) ) {
    unlink($row['pic']);
}

$sql = "DELETE FROM `{$wconfig['db']['tablepre']}nav` WHERE `nid`='{$nid}'";
if( $wdb->query($sql) ) {
    $wuser->actionlog("删除了导航：(ID:". $row['nid'] .")". $row['name']);
    exit('{"status":1, "msg":"操作成功"}');
} else {
    exit('{"status":0, "msg":"操作失败"}');
}
*/
