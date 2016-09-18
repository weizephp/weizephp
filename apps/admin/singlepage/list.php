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

// formtoken生成
$formtoken = w_security::set_formtoken();

// 定义SQL条件变量
$where = "";

// 定义搜索关键字变量
$wd = "";
if( isset($_GET['wd']) && !empty($_GET['wd']) ) {
	$wd    = htmlspecialchars(trim($_GET['wd']));
	$where = " WHERE `title` LIKE '%{$wd}%' ";
}

// 获取
$sql   = "SELECT `spid`, `status`, `displayorder`, `title` FROM `{$_W['db']['tablepre']}singlepage` {$where} ORDER BY `displayorder` ASC, `spid` DESC";
$lists = $WDB->paged($sql, 30);

// 载入列表页面
include $_W['template_path'].'/singlepage/list.html.php';
