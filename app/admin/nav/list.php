<?php
/**
 * 导航菜单-列表
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

$sql = "SELECT `nid`, `pid`, `level`, `name`, `status` FROM `{$wconfig['db']['tablepre']}nav` ORDER BY `listorder` ASC, `nid` ASC";
$nav_arr = $wdb->get_all($sql);

include W_ROOT_PATH . '/app/admin/nav/nav.inc.php';
$nav_list = set_nav_level($nav_arr);
unset($nav_arr);

include $wconfig['theme_path'] . '/admin/nav/list.html.php';
