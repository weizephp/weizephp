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
$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '?m=article&a=list';

// formtoken验证
$result = w_security::check_formtoken($_GET['formtoken']);
if($result == false) {
    w_errormessage("非法操作");
}

// 接收参数
$aid = isset($_GET['aid']) ? intval($_GET['aid']) : 0;
if($aid < 1) {
    w_errormessage("错误，非法索引ID");
}

// 获取标题,pic图片
$sql = "SELECT `aid`, `title`, `pic` FROM `{$_W['db']['tablepre']}articles` WHERE `aid`='{$aid}'";
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
$sql = "SELECT `id`, `aid`, `uid`, `attachment` FROM `{$_W['db']['tablepre']}articlesattachments` WHERE `aid`='{$aid}'";
$attachments = $WDB->get_all($sql);

// 删除附件
foreach($attachments as $v) {
    $attachment_filename = './data/attachments/'. $_W['module'] .'/ueditor/'. $v['attachment'];
    if(is_file($attachment_filename)) {
        unlink($attachment_filename);
    }
}

// 删除数据库数据
$sql = "DELETE FROM `{$_W['db']['tablepre']}articles` WHERE `aid`='{$aid}'";
if($WDB->query($sql)) {
    // 删除内容数据
    $sql = "DELETE FROM `{$_W['db']['tablepre']}articlescontents` WHERE `aid`='{$aid}'";
    $WDB->query($sql);
    // 删除附件数据
    $sql = "DELETE FROM `{$_W['db']['tablepre']}articlesattachments` WHERE `aid`='{$aid}'";
    $WDB->query($sql);
    // 增加管理日志
    w_adminlog("删除了文章：(ID:". $row['aid'] .")". $row['title']);
    // 成功提示
    w_successmessage("操作成功", $redirect);
} else {
    w_errormessage("操作失败");
}

