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
$roleid   = isset($_POST['roleid']) ? intval($_POST['roleid']) : 0;
$status   = isset($_POST['status']) && in_array($_POST['status'], array(0,1)) ? $_POST['status'] : 0;
$rolename = isset($_POST['rolename']) ? addslashes(trim($_POST['rolename'])) : '';

// 输入数据检查
if($roleid < 1) {
    w_errormessage('角色索引ID非法');
}
if(empty($rolename)) {
    w_errormessage('角色名称不能为空');
}

// 只允许创始人修改超级管理员角色
if(($roleid == 1) && ($_W['user']['uid'] != 1)) {
    w_errormessage('您没有权限修改超级管理员角色');
}

// 不允许修改自己的角色
if($roleid == $_W['user']['roleid']) {
    w_errormessage('不能修改自己的角色');
}

// 获取旧角色
$sql = "SELECT `roleid`, `rolename` FROM `{$_W['db']['tablepre']}roles` WHERE `roleid`='{$roleid}'";
$old = $WDB->get_row($sql);
if(empty($old)) {
    w_errormessage('错误，数据库没有您要修改的角色');
}

// 把更新的数据保存进数据库
$sql = "UPDATE `{$_W['db']['tablepre']}roles` SET `status`='{$status}', `rolename`='{$rolename}' WHERE `roleid`='{$roleid}'";
if($WDB->query($sql)) {
    $formtoken = w_security::set_formtoken();
    if($old['rolename'] != $rolename) {
        w_adminlog('把角色“'. $old['rolename'] .'”更新为：'. $rolename);
    } else {
        w_adminlog('更新角色：'. $rolename);
    }
    w_successmessage('操作成功', '?m=user&a=role');
} else {
    w_errormessage('操作失败');
}
