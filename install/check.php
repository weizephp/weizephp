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

// 载入全局文件
include 'global.inc.php';

// 环境检查
$system_info                = array();
$system_info['php_os']      = PHP_OS;
$system_info['php_version'] = PHP_VERSION;
$system_info['file_upload'] = @ini_get('file_uploads') ? ini_get('upload_max_filesize') : 0;
$gd_info                    = gd_info();
$system_info['gd']          = $gd_info['GD Version'];
unset($gd_info);

// 载入模板
include 'template/check.html.php';
?>
