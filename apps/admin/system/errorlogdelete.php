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

// 验证 formtoken
$result = w_security::check_formtoken($_GET['formtoken']);
if($result == false) {
    w_errormessage("非法操作");
}

// 只允许创始人删除管理日志
if(!in_array($_W['user']['uid'], $_W['founder'])) {
    w_errormessage("只允许创始人删除管理日志");
}

// 接收变量
$file = isset($_GET['file']) ? addslashes($_GET['file']) : '';

// 仅允许查看 data/log 下的错误文件
$log_path = dirname($file);
if($log_path != './data/logs') {
    w_errormessage('哇喔^_^，不能删其他目录的文件哦！');
}

if(file_exists($file)) {
    if(unlink($file)) {
        w_security::set_formtoken(); // 重置 formtoken
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '?m=system&a=adminlog';
        w_adminlog('删除错误日志 '.$file);
        w_successmessage('系统错误日志文件删除成功！', $redirect);
    } else {
        w_errormessage('系统错误日志文件删除失败！');
    }
} else {
    w_errormessage('系统错误日志文件不存在！');
}
