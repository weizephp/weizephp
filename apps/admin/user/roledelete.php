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
$result = w_security::check_formtoken($_GET['formtoken']);
if($result == false) {
    w_errormessage('非法操作');
}

// 接收角色ID
$roleid = isset($_GET['roleid']) ? intval($_GET['roleid']) : 0;

// 操作判断
if($roleid < 1) {
    w_errormessage('非法的角色ID');
}
if($roleid == 1) {
    w_errormessage('不能删除超级管理员角色');
}
if($roleid == $_W['user']['roleid']) {
    w_errormessage('不能删除自己的角色');
}

// 获取旧数据
$sql = "SELECT `roleid`, `rolename` FROM `{$_W['db']['tablepre']}roles` WHERE `roleid`='{$roleid}'";
$old = $WDB->get_row($sql);
if(empty($old)) {
    w_errormessage('错误，数据库没有您要删除的角色');
}

// 如果角色已经被用户使用，将不能删除
$sql  = "SELECT `uid`, `username`, `roleid` FROM `{$_W['db']['tablepre']}users` WHERE `roleid`='{$roleid}'";
$user = $WDB->get_row($sql);
if(!empty($user)) {
    w_errormessage('角色已经被用户使用，不能删除');
}

// 删除数据库指定的角色数据
$sql = "DELETE FROM `{$_W['db']['tablepre']}roles` WHERE `roleid`='{$roleid}'";
if($WDB->query($sql)) {
    $formtoken = w_security::set_formtoken();
    w_adminlog('删除角色：'. $old['rolename']);
    w_successmessage('操作成功', '?m=user&a=role');
} else {
    w_errormessage('操作失败');
}
