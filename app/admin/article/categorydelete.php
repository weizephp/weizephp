<?php
/**
 * 文章分类删除
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

$formtoken = isset($_GET['formtoken']) ? trim($_GET['formtoken']) : '';
if( $formtoken != $wuser->formtoken ) {
    exit('{"status":0, "msg":"非法操作"}');
}

exit('{"status":0, "msg":"^_^系统不允许删除，如果不想使用了，可以修改分类为不显示"}');

/* 如果要启用删除功能，请把当前注释清除
$cid      = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
$sql      = "SELECT `name` FROM `{$wconfig['db']['tablepre']}article_category` WHERE `cid`='{$cid}'";
$category = $wdb->get_row($sql);
if( empty($category) ) {
    exit('{"status":0, "msg":"该ID分类不存在"}');
}

$sql     = "SELECT * FROM `{$wconfig['db']['tablepre']}article` WHERE `cid`='{$cid}'";
$article = $wdb->get_row($sql);
if( !empty($article) ) {
    exit('{"status":0, "msg":"该ID分类存在文章，不能删除"}');
}

$sql = "DELETE FROM `{$wconfig['db']['tablepre']}article_category` WHERE `cid`='{$cid}'";
if( $wdb->query($sql) ) {
    $wuser->actionlog("删除文章分类：". $category['name']);
    exit('{"status":1, "msg":"操作成功"}');
} else {
    exit('{"status":0, "msg":"操作失败"}');
}
*/
