<?php
/**
 * 文章通用上传
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

/*
使用方式
---------------------------------
上传URL ：general.php?m=upload&a=article
*/

// 定义保存目录
$upload_save_path = './upload/article';

// 包含通用上传代码文件
include W_ROOT_PATH . '/app/general/upload/upload.inc.php';
