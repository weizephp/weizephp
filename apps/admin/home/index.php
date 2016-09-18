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

$server_info  = PHP_OS . ' / PHP v' . PHP_VERSION;
$server_info .= ini_get('safe_mode') ? ' Safe Mode' : NULL;

$server_soft  = $_SERVER['SERVER_SOFTWARE'];
$db_version   = $WDB->server_info;
$file_upload  = ini_get('file_uploads') ? ini_get('upload_max_filesize') : 0;

$db_size = 0;
$rows = $WDB->get_all("SHOW TABLE STATUS LIKE '{$_W['db']['tablepre']}%'");
foreach($rows as $v) {
    $db_size += $v['Data_length'] + $v['Index_length'];
}
$db_size = w_sizecount($db_size);

include $_W['template_path'].'/home/index.html.php';