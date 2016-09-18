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
w_errormessage("^_^系统不允许删除，如果不想使用了，可以修改为不显示");

// 定义返回URL
$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '?m=singlepage&a=list';

// formtoken验证
$result = w_security::check_formtoken($_GET['formtoken']);
if($result == false) {
    w_errormessage("非法操作");
}

// 接收参数
$spid = isset($_GET['spid']) ? intval($_GET['spid']) : 0;
if($spid < 1) {
    w_errormessage("错误，非法索引ID");
}

// 获取标题,pic图片
$sql = "SELECT `spid`, `title`, `pic` FROM `{$_W['db']['tablepre']}singlepage` WHERE `spid`='{$spid}'";
$row = $WDB->get_row($sql);
if(empty($row)) {
    w_errormessage("错误，数据库不存在您要删除的数据");
}

// 删除pic图片
if(!empty($row['pic'])) {
    $pic = './data/attachments/'. $_W['module'] .'/'. $row['pic'];
    if(is_file($pic)) {
        unlink($pic);
    }
}

// 获取附件
$sql = "SELECT `id`, `spid`, `uid`, `attachment` FROM `{$_W['db']['tablepre']}singlepageattachment` WHERE `spid`='{$spid}'";
$attachments = $WDB->get_all($sql);

// 删除附件
foreach($attachments as $v) {
    $attachment_filename = './data/attachments/'. $_W['module'] .'/ueditor/'. $v['attachment'];
    if(is_file($attachment_filename)) {
        unlink($attachment_filename);
    }
}

// 删除数据库数据
$sql = "DELETE FROM `{$_W['db']['tablepre']}singlepage` WHERE `spid`='{$spid}'";
if($WDB->query($sql)) {
    // 删除内容数据
    $sql = "DELETE FROM `{$_W['db']['tablepre']}singlepagecontent` WHERE `spid`='{$spid}'";
    $WDB->query($sql);
    // 删除附件数据
    $sql = "DELETE FROM `{$_W['db']['tablepre']}singlepageattachment` WHERE `spid`='{$spid}'";
    $WDB->query($sql);
    // 增加管理日志
    w_adminlog("删除了单页：(ID:". $row['spid'] .")". $row['title']);
    // 成功提示
    w_successmessage("操作成功", $redirect);
} else {
    w_errormessage("操作失败");
}

