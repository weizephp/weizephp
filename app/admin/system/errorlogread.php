<?php
/**
 * 错误日志查看
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

// 检查是否非法查看
$formtoken = isset($_GET['formtoken']) ? trim($_GET['formtoken']) : '';
if( $formtoken != $wuser->formtoken ) {
    w_error("非法操作");
}

// 接收变量
$file = isset($_GET['file']) ? addslashes($_GET['file']) : '';

// 仅允许查看 data/log 下的错误文件
$log_path = dirname($file);
if($log_path != './data/log') {
    w_error('哇喔^_^，不能看其他目录的文件哦！');
}

// 查看系统错误日志
$lines = array();
if(file_exists($file)) {
    $lines = file($file);
} else {
    w_error('系统错误日志文件不存在！');
}

// 转换编码
foreach($lines as $key => $val) {
    $lines[$key] = mb_convert_encoding($val, 'UTF-8', 'GBK');
}

// 包含模板
include $wconfig['theme_path'] . '/admin/system/errorlogread.html.php';
