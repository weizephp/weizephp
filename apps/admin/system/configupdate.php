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
    exit('{"status":0, "msg":"非法操作"}');
}

unset($_POST['formtoken']);

$_POST = w_addslashes($_POST);
foreach($_POST as $k => $v) {
    $sql = "UPDATE `{$_W['db']['tablepre']}config` SET `cvalue`='{$v}' WHERE `ckey`='{$k}'";
    $WDB->query($sql);
}

$formtoken = w_security::set_formtoken();

w_adminlog('更新了站点配置');

exit('{"status":1, "msg":"ok", "formtoken":"'.$formtoken.'"}');
