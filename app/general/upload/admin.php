<?php
/**
 * 后台通用上传
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

/*
使用方式
---------------------------------
后台上传地址     ：general.php?m=upload&a=admin&folder=英文字母文件夹
后台文章上传示例 ：general.php?m=upload&a=admin&folder=article
后台单页上传示例 ：general.php?m=upload&a=admin&folder=singlepage
*/

// 定义目录名
$folder = isset($_GET['folder']) && (preg_match('/^[a-zA-Z0-9_]+$/', $_GET['folder']) == 1) ? $_GET['folder'] : '';
if( empty($folder) ) {
    exit('{"status":0, "msg":"请设置正确的上传文件夹名称", "id":0, "data":[]}');
}

// 定义保存目录
$upload_save_path = './upload/'. $folder;

// 包含通用上传代码文件
include W_ROOT_PATH . '/app/general/upload/upload.inc.php';
