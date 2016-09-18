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

$formtoken = w_security::set_formtoken();

$dir       = W_ROOT_PATH . '/data/logs';
$files     = scandir($dir, 1);
$files_arr = array();
foreach ($files as $k => $v) {
    if( !in_array($v, array('index.htm','index.html')) ) {
        if(is_file($dir.'/'.$v)) {
            $files_arr[] = './data/logs/'.$v;
        }
    }
}
unset($files);

include $_W['template_path'].'/system/errorlog.html.php';
