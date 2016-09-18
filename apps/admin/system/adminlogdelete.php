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

$result = w_security::check_formtoken($_POST['formtoken']);
if($result == false) {
    w_errormessage("非法操作");
}

if(!in_array($_W['user']['uid'], $_W['founder'])) {
    w_errormessage("只允许创始人删除管理日志");
}

if(empty($_POST['logid'])) {
    w_errormessage("没有选择要删除的管理日志");
}

if(!is_array($_POST['logid'])) {
    w_errormessage("删除管理日志操作无效");
}

foreach($_POST['logid'] as $v) {
    $logid = intval($v);
    $sql   = "DELETE FROM `{$_W['db']['tablepre']}adminlog` WHERE `logid`='{$logid}'";
    $WDB->query($sql);
}

w_security::set_formtoken(); // 重置 formtoken

w_adminlog("管理日志删除");

$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '?m=system&a=adminlog';

w_successmessage("管理日志删除成功", $redirect);
