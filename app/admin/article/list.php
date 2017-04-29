<?php
/**
 * 文章列表
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

// 包含扩展函数
include W_ROOT_PATH . '/lib/w_extend.func.php';

// 获取分类
$sql        = "SELECT `cid`, `pid`, `name`, `status`, `displayorder` FROM `{$wconfig['db']['tablepre']}article_category`";
$categories = $wdb->get_all($sql);

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
$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
if( $cid > 0 ) {
	$cid = intval($_GET['cid']);
	$where      = " WHERE `cid`='{$cid}' ";
	// 获取当前分类名
	foreach($categories as $v) {
	    if($v['cid'] == $cid) {
	        $cur_categoryname = htmlspecialchars($v['name']);
	        break;
	    }
	}
}

// 获取数据
$sql      = "SELECT `aid`, `cid`, `status`, `title` FROM `{$wconfig['db']['tablepre']}article` {$where} ORDER BY `aid` DESC";
$articles = $wdb->pagination($sql, 30);
$pagination_output = $wdb->pagination_output();

// 包含模板
include $wconfig['theme_path'] . '/admin/article/list.html.php';
