<?php
/**
 * 错误日志删除
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

$formtoken = isset($_GET['formtoken']) ? trim($_GET['formtoken']) : '';
if( $formtoken != $wuser->formtoken ) {
    exit('{"status":0, "msg":"非法操作"}');
}

if( !in_array($wuser->uid, $wconfig['founder']) ) {
    exit('{"status":0, "msg":"只允许创始人删除管理日志"}');
}

$file = isset($_GET['file']) ? addslashes($_GET['file']) : '';

$log_path = dirname($file);
if( $log_path != './data/log' ) {
    exit('{"status":0, "msg":"哇喔^_^，不能删其他目录的文件哦！"}');
}

if( file_exists($file) ) {
    if( unlink($file) ) {
        $wuser->actionlog("删除错误日志 ". $file);
        exit('{"status":1, "msg":"系统错误日志文件删除成功！"}');
    } else {
        exit('{"status":0, "msg":"系统错误日志文件删除失败！"}');
    }
} else {
    exit('{"status":0, "msg":"系统错误日志文件不存在！"}');
}
