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
-------------------------
使用url示例：generals.php?m=upload&a=upload&folder=英文文件夹
-------------------------
*/

// 检查文件域
if(!isset($_FILES['file'])) {
    exit('{"status":0, "msg":"请设置上传的文件域为 file !", "id":0, "data":[]}');
}

// 检查文件夹
$folder = isset($_GET['folder']) && (preg_match('/^[a-zA-Z0-9_]+$/', $_GET['folder']) == 1) ? $_GET['folder'] : '';
if(empty($folder)) {
    exit('{"status":0, "msg":"请设置正确的上传文件夹", "id":0, "data":[]}');
}

// [!]上传时间间隔限制
//...

// [!]限制用户一天时间内上传的大小
//...

// 载入上传类
include 'includes/w_upload.class.php';

// 实例化上传类
$upload = new w_upload();

// 上传设置
$upload->max_size        = 2; // 2M
$upload->allow_extension = array(
    // 图片
    'gif', 'jpg', 'jpeg', 'png', 'bmp',
    // 文档
    'rar', 'zip', 'pdf', 'xls', 'txt', 'doc',
    // 语音
    'wav', 'aif', 'au', 'mp3', 'ram', 'wma', 'mmf', 'amr', 'aac', 'flac',
    // 视频
    'avi', 'rmvb', 'rm', 'asf', 'divx', 'mpeg', 'mpe', 'wmv', 'mp4', 'mkv', 'vob', '3gp', 'ogg'
);
$upload->save_path       = './data/attachments/'. $folder;

// 正式上传
$result = $upload->upload($_FILES['file']);
switch($result) {
    case 1:
        exit('{"status":0, "msg":"上传的文件大小超过了系统配置的限制", "id":0, "data":[]}');
        break;
    case 2:
        exit('{"status":0, "msg":"上传文件的大小超过了表单规定的最大值", "id":0, "data":[]}');
        break;
    case 3:
        exit('{"status":0, "msg":"文件只有部分被上传", "id":0, "data":[]}');
        break;
    case 4:
        exit('{"status":0, "msg":"没有文件被上传", "id":0, "data":[]}');
        break;
    case 6:
        exit('{"status":0, "msg":"找不到临时文件夹", "id":0, "data":[]}');
        break;
    case 7:
        exit('{"status":0, "msg":"文件写入失败", "id":0, "data":[]}');
        break;
    case 8:
        exit('{"status":0, "msg":"上传文件大小不符", "id":0, "data":[]}');
        break;
    case 9:
        exit('{"status":0, "msg":"上传文件MIME类型不允许", "id":0, "data":[]}');
        break;
    case 10:
        exit('{"status":0, "msg":"上传的文件后缀类型不允许", "id":0, "data":[]}');
        break;
    case 11:
        exit('{"status":0, "msg":"非法上传文件", "id":0, "data":[]}');
        break;
    case 12:
        exit('{"status":0, "msg":"非法图像文件", "id":0, "data":[]}');
        break;
    case 13:
        exit('{"status":0, "msg":"文件夹不可写", "id":0, "data":[]}');
        break;
    case 14:
        exit('{"status":0, "msg":"同名文件已经存在", "id":0, "data":[]}');
        break;
    case 15:
        exit('{"status":0, "msg":"保存文件夹创建失败", "id":0, "data":[]}');
        break;
    case 16:
        exit('{"status":0, "msg":"将上传的文件移动到新位置出错", "id":0, "data":[]}');
        break;
}

// 上传文件信息保存进数据库
$filepath = str_replace('./data/attachments/', 'data/attachments/', $result['filename']);
$sql      = "INSERT INTO `{$_W['db']['tablepre']}upload`(`uid`, `uploadtime`, `format`, `filename`, `filepath`, `filesize`, `status`, `width`, `height`, `title`, `description`) VALUES ('{$_W['user']['uid']}', '".W_TIMESTAMP."', '{$result['extension']}', '{$result['name']}', '{$filepath}', '{$result['size']}', '0', '{$result['width']}', '{$result['height']}', '', '')";
if($WDB->query($sql)) {
    exit( json_encode(array('status' => 1, 'msg' => '上传成功', 'id' => $WDB->insert_id, 'data' => $result)) );
} else {
    exit('{"status":0, "msg":"数据库写入错误", "id":0, "data":[]}');
}
