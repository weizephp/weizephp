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

include 'includes/w_extend.func.php';

/* ===================================================================
 * 特别提醒：在使用 w_rmdir 函数前，请先备份程序。一旦误删，都是血和泪。
 * =================================================================== */

$year_month_day = date('Ymd', W_TIMESTAMP);         // 当前年日月 //echo $year_month_day;exit;
$two_days_ago   = date('Ymd', strtotime("-2 day")); // 两天前的年月日

$operation = isset($_GET['operation']) ? $_GET['operation'] : 'default';

if($operation == 'default') {
    // formtoken生成
    $formtoken = w_security::set_formtoken();
} else {
    // formtoken验证
    $result = w_security::check_formtoken($_GET['formtoken']);
    if($result == false) {
        w_errormessage("非法操作", "?m=system&a=cleardata");
    }
    $formtoken = w_security::set_formtoken();
}

switch($operation) {
    // 默认页。主要显示清理按钮。
    case 'default':
        include $_W['template_path'].'/system/cleardata.html.php';
        break;
        
    // 清理上传的临时文件（删除前一天的数据）
    case 'upload':
        $last_time = W_TIMESTAMP - 86400; // 前一天时间
        $sql       = "SELECT `id`, `filepath`, `status` FROM `{$_W['db']['tablepre']}upload` WHERE `uploadtime`<'{$last_time}' AND `status`='0'";
        $uploads   = $WDB->get_all($sql);
        
        $sql = "DELETE FROM `{$_W['db']['tablepre']}upload` WHERE `uploadtime`<'{$last_time}' AND `status`='0'";
        $WDB->query($sql);
        
        foreach($uploads as $v) {
            if(is_file('./' . $v['filepath'])) {
                unlink('./' . $v['filepath']);
            }
        }
        w_adminlog("数据清理：清理上传的临时文件");
        w_successmessage("成功清理上传的临时文件！", "?m=system&a=cleardata");
        break;
        
    // 清理编辑器的临时文件（删除两天前的数据）
    case 'ueditor':
        // 删除文件
        $directory = './data/temporaries/ueditor/file';
        $dir_list  = w_scandir($directory);
        foreach($dir_list as $v) {
            if( is_numeric($v) && ($v < $two_days_ago)) { // 只允许数字年月日文件夹才能操作
                if(is_dir($directory . '/' .$v)) {
                    w_rmdir($directory . '/' .$v);
                }
            }
        }
        // 删除图片
        $directory = './data/temporaries/ueditor/image';
        $dir_list  = w_scandir($directory);
        foreach($dir_list as $v) {
            if( is_numeric($v) && ($v < $two_days_ago)) { // 只允许数字年月日文件夹才能操作
                if(is_dir($directory . '/' .$v)) {
                    w_rmdir($directory . '/' .$v);
                }
            }
        }
        // 删除视频
        $directory = './data/temporaries/ueditor/video';
        $dir_list  = w_scandir($directory);
        foreach($dir_list as $v) {
            if( is_numeric($v) && ($v < $two_days_ago)) { // 只允许数字年月日文件夹才能操作
                if(is_dir($directory . '/' .$v)) {
                    w_rmdir($directory . '/' .$v);
                }
            }
        }
        // 提示信息
        w_adminlog("数据清理：清理编辑器的临时文件");
        w_successmessage("成功清理编辑器的临时文件！", "?m=system&a=cleardata");
        break;
        
    // 清理缩略图
    case 'thumb':
        w_rmdir('./data/thumbnails');
        w_adminlog("数据清理：清理缩略图");
        w_successmessage("成功清理缩略图！", "?m=system&a=cleardata");
        break;
        
    // 清理缓存
    case 'cache':
        w_rmdir('./data/caches');
        w_adminlog("数据清理：清理缓存");
        w_successmessage("成功清理缓存！", "?m=system&a=cleardata");
        break;
}
