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

// 如果没有安装，先安装
if(is_dir('./install') && !is_file('./install/install.lock')) {
	header("Location: ./install/index.php");
	exit;
}

// 定义默认加载的应用
define('W_APP', 'content');
include 'weize.framework.php';
?>
