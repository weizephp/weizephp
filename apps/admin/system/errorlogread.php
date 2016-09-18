<?php
/**
 * +----------------------------------------------------------------------
 * | WeizePHP framework
 * +----------------------------------------------------------------------
 * | Copyright (c) 2013-2113 http://weizephp.75hh.com/ All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: Ze Wei <E-mail:weizesw@gmail.com> <QQ:310472156>
 * +----------------------------------------------------------------------
 */

if(!defined('IN_WEIZEPHP')){exit('Access Denied');}

// 接收变量
$file = isset($_GET['file']) ? addslashes($_GET['file']) : '';

// 仅允许查看 data/log 下的错误文件
$log_path = dirname($file);
if($log_path != './data/logs') {
    w_errormessage('哇喔^_^，不能看其他目录的文件哦！');
}

// 查看系统错误日志
$lines = array();
if(file_exists($file)) {
    $lines = file($file);
} else {
    w_errormessage('系统错误日志文件不存在！');
}

// 转换编码
foreach($lines as $k => $v) {
    $lines[$k] = mb_convert_encoding($v, 'UTF-8', 'GBK');
}

include $_W['template_path'].'/system/errorlogread.html.php';
