<?php
/**
 * 文章分类列表
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

include W_ROOT_PATH . '/lib/w_extend.func.php';

$sql        = "SELECT `cid`, `pid`, `name`, `status`, `displayorder` FROM `{$wconfig['db']['tablepre']}article_category`";
$categories = $wdb->get_all($sql);

$categories = w_category_tree_html($categories);

include $wconfig['theme_path'] . '/admin/article/categorylist.html.php';
