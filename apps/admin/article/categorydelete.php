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

// [*]^_^不允许删除的啦
w_errormessage("^_^系统不允许删除，如果不想使用了，可以修改分类为不显示");

// formtoken验证
$result = w_security::check_formtoken($_GET['formtoken']);
if($result == false) {
    w_errormessage("非法操作", "?m=article&a=categorylist");
}

// 获取分类
$categoryid = isset($_GET['categoryid']) ? intval($_GET['categoryid']) : 0;
$sql        = "SELECT `categoryname` FROM `{$_W['db']['tablepre']}articlescategories` WHERE `categoryid`='{$categoryid}'";
$category   = $WDB->get_row($sql);
if(empty($category)) {
    w_errormessage("该ID分类不存在", "?m=article&a=categorylist");
}

// 永久删除
$sql = "DELETE FROM `{$_W['db']['tablepre']}articlescategories` WHERE `categoryid`='{$categoryid}'";
if($WDB->query($sql)) {
    $formtoken = w_security::set_formtoken();
    w_adminlog("删除文章分类：". $category['categoryname']);
    w_successmessage("操作成功", "?m=article&a=categorylist");
} else {
    w_errormessage("操作失败", "?m=article&a=categorylist");
}

