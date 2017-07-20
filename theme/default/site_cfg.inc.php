<?php
/**
 * 网站配置
 */
if( !defined('IN_WEIZEPHP') ) {
    exit('Access Denied');
}

$sql_cfg = "SELECT `ckey`,`cvalue` FROM `{$wconfig['db']['tablepre']}config`";
$list_cfg = $wdb->get_all($sql_cfg);

$site_cfg = array();
foreach($list_cfg as $val) {
    $site_cfg[$val['ckey']] = htmlspecialchars($val['cvalue']);
}

unset($sql_cfg, $list_cfg);