<?php
/**
 * 文章删除
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

// formtoken安全验证
$formtoken = isset($_GET['formtoken']) ? trim($_GET['formtoken']) : '';
if( $formtoken != $wuser->formtoken ) {
    exit('{"status":0, "msg":"非法操作"}');
}

// 不允许删除
exit('{"status":0, "msg":"^_^系统不允许删除，如果不想使用了，可以修改为不显示"}');

/* 如要启用删除功能，请打开本注释
// 接收参数
$aid = isset($_GET['aid']) ? intval($_GET['aid']) : 0;
if( $aid < 1 ) {
    exit('{"status":0, "msg":"错误，非法索引ID"}');
}

// 获取标题,pic图片
$sql = "SELECT `aid`, `title`, `pic` FROM `{$wconfig['db']['tablepre']}article` WHERE `aid`='{$aid}'";
$row = $wdb->get_row($sql);
if( empty($row) ) {
    exit('{"status":0, "msg":"错误，数据库不存在您要删除的数据"}');
}

// 删除pic图片
if( !empty($row['pic']) ) {
    if( is_file($row['pic']) ) {
        unlink($row['pic']);
    }
}

// 获取附件
$sql = "SELECT `id`, `aid`, `uid`, `attachment` FROM `{$wconfig['db']['tablepre']}article_attachment` WHERE `aid`='{$aid}'";
$attachments = $wdb->get_all($sql);

// 删除附件
foreach($attachments as $v) {
    $attachment_filename = './upload/'. $m .'/ueditor/'. $v['attachment'];
    if( is_file($attachment_filename) ) {
        unlink($attachment_filename);
    }
}

// 删除数据库数据
$sql = "DELETE FROM `{$wconfig['db']['tablepre']}article` WHERE `aid`='{$aid}'";
if( $wdb->query($sql) ) {
    // 删除内容数据
    $sql = "DELETE FROM `{$wconfig['db']['tablepre']}article_content` WHERE `aid`='{$aid}'";
    $wdb->query($sql);
    // 删除附件数据
    $sql = "DELETE FROM `{$wconfig['db']['tablepre']}article_attachment` WHERE `aid`='{$aid}'";
    $wdb->query($sql);
    // 增加管理日志
    $wuser->actionlog("删除了文章：(ID:". $row['aid'] .")". $row['title']);
    // 提示成功
    exit('{"status":1, "msg":"操作成功"}');
} else {
    exit('{"status":0, "msg":"操作失败"}');
}
*/
