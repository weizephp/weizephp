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

/*
+--------------------------

= 根据url生成缩略图的设计思路 =

通过 generals.php?m=image&a=thumb&f=article&s=20151124/1448345731_6446.jpg&w=200&h=100 方式的URL来显示缩略图片

参数说明：f(folder),s(source),w(width),h(height)

1、根据f来区分要读取哪个文件夹下的图片，如果没有，就返回404；
2、如果已经存在缩略图，就返回缩略图，否则就返回404；

+--------------------------
*/

include 'includes/w_image.class.php';

$allow_extension = array('gif', 'jpg', 'jpeg', 'png', 'bmp');// 允许的扩展名

$folder = isset($_GET['f']) && in_array($_GET['f'], $_W['thumb_allow_folder']) ? $_GET['f'] : NULL; // 图片目录
$source = isset($_GET['s']) && (preg_match('/^([0-9]+)\/([0-9_]+).([a-zA-Z]+)$/', $_GET['s']) == 1) ? $_GET['s'] : ''; // 图片资源路径
$width  = isset($_GET['w']) ? intval($_GET['w']) : 0; // 宽
$height = isset($_GET['h']) ? intval($_GET['h']) : 0; // 高

// 不允许空的类型
if(empty($folder)) {
    header('HTTP/1.0 404 Not Found Error Folder');
    header('Status: 404 Not Found Error Folder');
    exit('404 Not Found Error Folder');
}

// 不允许空的图片资源
if(empty($source)) {
    header('HTTP/1.0 404 Not Found Error Source');
    header('Status: 404 Not Found Error Source');
    exit('404 Not Found Error Source');
}

// 不允许宽度大于 800 的参数
if($width > 800) {
    header('HTTP/1.0 404 Not Found Error Width');
    header('Status: 404 Not Found Error Width');
    exit('404 Not Found Error Width');
}

// 不允许高度大于 600 的参数
if($height > 600) {
    header('HTTP/1.0 404 Not Found Error Height');
    header('Status: 404 Not Found Error Height');
    exit('404 Not Found Error Height');
}

// 不允许不合法的扩展名通过
$pathinfo  = pathinfo($source);
$extension = isset($pathinfo['extension']) ? strtolower($pathinfo['extension']) : '';
if( !in_array($extension, $allow_extension, TRUE) ) {
    header('HTTP/1.0 404 Not Found Ext');
    header('Status: 404 Not Found Ext');
    exit('404 Not Found Ext');
}

// 图片具体路径
$source_path = "data/attachments/". $folder ."/". $source; // 原图资源路径
$thumb_path  = "data/thumbnails/". $folder ."/". $source .".". $width ."x". $height .".". $extension; // 缩略图路径. x 是小写字母

// 如果原图资源不存在，返回404
if( !is_file($source_path) ) {
    header('HTTP/1.0 404 Not Found Source Image');
    header('Status: 404 Not Found Source Image');
    exit;
}

// 如果缩略图不存在，就生成
if( !is_file($thumb_path) ) {
    // 创建缩略图路径
    $thumb_dir = dirname($thumb_path);
    if( !is_dir($thumb_dir) ) {
        mkdir($thumb_dir, 0777, TRUE);
    }
    // 创建缩略图片
    $result = w_image::thumb($source_path, $thumb_path, $width, $height, TRUE);
}

// 删除用完了的变量
//unset($width, $height, $pathinfo, $extension);

// 输出图片
$image_type = $extension == 'jpg' ? 'jpeg' : $extension;
header('Content-Type: image/'.$image_type);
ob_clean();
flush();
readfile($thumb_path);
