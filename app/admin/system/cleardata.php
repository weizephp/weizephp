<?php
/**
 * 清除数据
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

// 包含扩展函数
include W_ROOT_PATH . '/lib/w_extend.func.php';

/* ===================================================================
 * 特别提醒：在使用 w_rmdir 函数前，请先备份程序。一旦误删，都是血和泪。
 * =================================================================== */

// 定义时间
$year_month_day = date('Ymd', W_TIMESTAMP);         // 当前年日月
$two_days_ago   = date('Ymd', strtotime("-2 day")); // 两天前的年月日

// 定义操作变量
$operation = isset($_GET['operation']) ? $_GET['operation'] : 'default';

// 检查是否非法操作
if( $operation != 'default' ) {
    $formtoken = isset($_GET['formtoken']) ? trim($_GET['formtoken']) : '';
    if( $formtoken != $wuser->formtoken ) {
        exit('{"status":0, "msg":"非法操作"}');
    }
}

// 执行具体的清除操作
switch($operation) {
    // 默认页。主要显示清理按钮。
    case 'default':
        include $wconfig['theme_path'] . '/admin/system/cleardata.html.php';
        break;
        
    // 清理上传的临时文件（删除前一天的数据）
    case 'upload':
        $last_time = W_TIMESTAMP - 86400; // 前一天时间
        $sql       = "SELECT `id`, `filepath`, `status` FROM `{$wconfig['db']['tablepre']}upload` WHERE `uploadtime`<'{$last_time}' AND `status`='0'";
        $uploads   = $wdb->get_all($sql);
        
        $sql = "DELETE FROM `{$wconfig['db']['tablepre']}upload` WHERE `uploadtime`<'{$last_time}' AND `status`='0'";
        $wdb->query($sql);
        
        foreach($uploads as $v) {
            if(is_file('./' . $v['filepath'])) {
                unlink('./' . $v['filepath']);
            }
        }
        $wuser->actionlog("数据清理：清理上传的临时文件");
        exit('{"status":1, "msg":"成功清理上传的临时文件！"}');
        break;
        
    // 清理编辑器的临时文件（删除两天前的数据）
    case 'ueditor':
        // 删除文件
        $directory = './data/tmp/ueditor/file';
        $dir_list  = w_scandir($directory);
        foreach($dir_list as $v) {
            if( is_numeric($v) && ($v < $two_days_ago)) { // 只允许数字年月日文件夹才能操作
                if(is_dir($directory . '/' .$v)) {
                    w_rmdir($directory . '/' .$v);
                }
            }
        }
        // 删除图片
        $directory = './data/tmp/ueditor/image';
        $dir_list  = w_scandir($directory);
        foreach($dir_list as $v) {
            if( is_numeric($v) && ($v < $two_days_ago)) { // 只允许数字年月日文件夹才能操作
                if(is_dir($directory . '/' .$v)) {
                    w_rmdir($directory . '/' .$v);
                }
            }
        }
        // 删除视频
        $directory = './data/tmp/ueditor/video';
        $dir_list  = w_scandir($directory);
        foreach($dir_list as $v) {
            if( is_numeric($v) && ($v < $two_days_ago)) { // 只允许数字年月日文件夹才能操作
                if(is_dir($directory . '/' .$v)) {
                    w_rmdir($directory . '/' .$v);
                }
            }
        }
        // 提示信息
        $wuser->actionlog("数据清理：清理编辑器的临时文件");
        exit('{"status":1, "msg":"成功清理编辑器的临时文件！"}');
        break;
        
    // 清理缩略图
    case 'thumb':
        w_rmdir('./upload/thumb');
        $wuser->actionlog("数据清理：清理缩略图");
        exit('{"status":1, "msg":"成功清理缩略图！"}');
        break;
        
    // 清理缓存
    case 'cache':
        w_rmdir('./data/cache');
        $wuser->actionlog("数据清理：清理缓存");
        exit('{"status":1, "msg":"成功清理缓存！"}');
        break;
}
