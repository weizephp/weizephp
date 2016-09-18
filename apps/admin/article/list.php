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

// 载入扩展函数
include 'includes/w_extend.func.php';

// formtoken生成
$formtoken = w_security::set_formtoken();

// 获取新闻分类
$sql        = "SELECT `categoryid`, `pid`, `categoryname`, `status`, `displayorder` FROM `{$_W['db']['tablepre']}articlescategories`";
$categories = $WDB->get_all($sql);

// 分类树处理
$categories = w_category_tree_html($categories);

// 定义SQL条件变量
$where = "";

// 定义搜索关键字变量
$wd = "";
if( isset($_GET['wd']) && !empty($_GET['wd']) ) {
	$wd    = htmlspecialchars(trim($_GET['wd']));
	$where = " WHERE `title` LIKE '%{$wd}%' ";
}

// 按分类查看
$cur_categoryname = '≡ 全部分类 ≡';
$categoryid        = isset($_GET['categoryid']) ? intval($_GET['categoryid']) : 0;
if($categoryid > 0) {
	$categoryid = intval($_GET['categoryid']);
	$where      = " WHERE `categoryid`='{$categoryid}' ";
	// 获取当前分类名
	foreach($categories as $v) {
	    if($v['categoryid'] == $categoryid) {
	        $cur_categoryname = htmlspecialchars($v['categoryname']);
	        break;
	    }
	}
}

// 获取数据
$sql      = "SELECT `aid`, `categoryid`, `status`, `title` FROM `{$_W['db']['tablepre']}articles` {$where} ORDER BY `aid` DESC";
$articles = $WDB->paged($sql, 30);

// 载入列表页面
include $_W['template_path'].'/article/list.html.php';
