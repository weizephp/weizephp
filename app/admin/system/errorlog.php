<?php
/**
 * 错误日志
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

$dir       = W_ROOT_PATH . '/data/log';
$files     = scandir($dir, 1);
$files_arr = array();
foreach ($files as $key => $val) {
    if( !in_array($val, array('index.htm','index.html')) ) {
        if( is_file($dir.'/'.$val) ) {
            $files_arr[] = './data/log/'.$val;
        }
    }
}
unset($files);

include $wconfig['theme_path'] . '/admin/system/errorlog.html.php';
