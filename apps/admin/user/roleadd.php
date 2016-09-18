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

// formtoken验证
$result = w_security::check_formtoken($_POST['formtoken']);
if($result == false) {
    w_errormessage('非法操作');
}

// 接收参数
$rolename = isset($_POST['rolename']) ? addslashes(trim($_POST['rolename'])) : '';
if(empty($rolename)) {
    w_errormessage('角色名称不能为空');
}

// 数据保存
$sql = "INSERT INTO `{$_W['db']['tablepre']}roles`(`status`, `rolename`, `permissions`) VALUES ('1', '{$rolename}', '')";
if($WDB->query($sql)) {
    $formtoken = w_security::set_formtoken();
    w_adminlog('添加角色：'. $rolename);
    w_successmessage('操作成功', '?m=user&a=role');
} else {
    w_errormessage('操作失败');
}
