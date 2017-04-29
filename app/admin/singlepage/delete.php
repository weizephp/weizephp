<?php
/**
 * 单页删除
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
$spid = isset($_GET['spid']) ? intval($_GET['spid']) : 0;
if( $spid < 1 ) {
    exit('{"status":0, "msg":"错误，非法索引ID"}');
}

// 获取标题,pic图片
$sql = "SELECT `spid`, `title`, `pic` FROM `{$wconfig['db']['tablepre']}singlepage` WHERE `spid`='{$spid}'";
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
$sql = "SELECT `id`, `spid`, `uid`, `attachment` FROM `{$wconfig['db']['tablepre']}singlepage_attachment` WHERE `spid`='{$spid}'";
$attachments = $wdb->get_all($sql);

// 删除附件
foreach($attachments as $v) {
    $attachment_filename = './upload/'. $m .'/ueditor/'. $v['attachment'];
    if( is_file($attachment_filename) ) {
        unlink($attachment_filename);
    }
}

// 删除数据库数据
$sql = "DELETE FROM `{$wconfig['db']['tablepre']}singlepage` WHERE `spid`='{$spid}'";
if( $wdb->query($sql) ) {
    // 删除内容数据
    $sql = "DELETE FROM `{$wconfig['db']['tablepre']}singlepage_content` WHERE `spid`='{$spid}'";
    $wdb->query($sql);
    // 删除附件数据
    $sql = "DELETE FROM `{$wconfig['db']['tablepre']}singlepage_attachment` WHERE `spid`='{$spid}'";
    $wdb->query($sql);
    // 增加管理日志
    $wuser->actionlog("删除了单页：(ID:". $row['spid'] .")". $row['title']);
    // 提示成功
    exit('{"status":1, "msg":"操作成功"}');
} else {
    exit('{"status":0, "msg":"操作失败"}');
}
*/
