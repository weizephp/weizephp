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

/**
 * 安装锁定
 */

if(is_file('install.lock')) {
	exit('Installed!');
}

/**
 * 定义常量
 */

define('INSTALL_WEIZEPHP',  true);          // 定义安装初始化常量
define('INSTALL_TIMESTAMP', time());        // 安装时间戳
define('DB_CHARSET',        'utf8');        // 数据库字符集
define('SQL_FILE',          'install.sql'); // SQL安装文件路径
define('SOURCE_PREFIX',     'w_');          // “替换前”的表前缀

/**
 * 目录、文件读写权限检查数组
 */
$checking_dirs = array(
    'config',
    'config/w_config.inc.php',
    'config/config_nav.inc.php',
    'data',
    'data/cache',
    'data/log',
    'data/tmp',
    'upload',
    'upload/article',
    'upload/avatar',
    'upload/singlepage',
    'upload/thumb',
    'install'
);

/**
 * 成功提示函数
 */
function w_install_success($message, $url = '') {
    if($url == '') {
        $url = 'javascript:window.history.back();';
    }
    echo <<<HTML
<!DOCTYPE HTML>
    <html>
    <head>
        <meta charset="utf-8"/>
        <title>成功提示</title>
        <style type="text/css">body{text-align:center}</style>
    </head>
    
    <body>
        <h1>√ 成功提示</h1>
        <p>{$message}</p>
        <p><a href="{$url}">[返 回]</a></p>
    </body>
</html>
HTML;
    exit;
}

/**
 * 错误提示函数
 */
function w_install_error($message, $url = '') {
    if($url == '') {
        $url = 'javascript:window.history.back();';
    }
    echo <<<HTML
<!DOCTYPE HTML>
    <html>
    <head>
        <meta charset="utf-8"/>
        <title>错误提示</title>
        <style type="text/css">body{text-align:center}</style>
    </head>
    
    <body>
        <h1>× 错误提示</h1>
        <p>{$message}</p>
        <p><a href="{$url}">[返 回]</a></p>
    </body>
</html>
HTML;
    exit;
}
