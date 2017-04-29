<?php
/**
 * 单页列表
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

// 定义SQL条件变量
$where = "";

// 定义搜索关键字变量
$wd = "";
if( isset($_GET['wd']) && !empty($_GET['wd']) ) {
	$wd    = htmlspecialchars(trim($_GET['wd']));
	$where = " WHERE `title` LIKE '%{$wd}%' ";
}

// 获取数据
$sql = "SELECT `spid`, `status`, `title` FROM `{$wconfig['db']['tablepre']}singlepage` {$where} ORDER BY `spid` DESC";
$singlepages = $wdb->pagination($sql, 30);
$pagination_output = $wdb->pagination_output();

// 包含模板
include $wconfig['theme_path'] . '/admin/singlepage/list.html.php';
