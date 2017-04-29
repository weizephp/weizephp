<?php
/**
 * 网站配置
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

$sql  = "SELECT `ckey`,`cvalue` FROM `{$wconfig['db']['tablepre']}config`";
$list = $wdb->get_all($sql);

$site_cfg = array();
foreach($list as $val) {
	$site_cfg[$val['ckey']] = htmlspecialchars($val['cvalue']);
}

include $wconfig['theme_path'] . '/admin/system/config.html.php';
