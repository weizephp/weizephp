<?php
/**
 * 管理日志
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

$sql    = "SELECT * FROM `{$wconfig['db']['tablepre']}adminlog` ORDER BY `logid` DESC";
$logs   = $wdb->pagination($sql, 30);
$output = $wdb->pagination_output();

include $wconfig['theme_path'] . '/admin/system/adminlog.html.php';
