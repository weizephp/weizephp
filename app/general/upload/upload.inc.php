<?php
/**
 * 通用上传-包含文件
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

if( isset($_GET['accesstoken']) || isset($_POST['accesstoken']) ) {
    // ...
} else {
    if( isset($_GET['formtoken']) ) {
        $formtoken = trim($_GET['formtoken']);
    } else if( isset($_POST['formtoken']) ) {
        $formtoken = trim($_POST['formtoken']);
    } else {
        $formtoken = '';
    }
    if( $formtoken != $wuser->formtoken ) {
        exit('{"status":0, "msg":"非法操作", "id":0, "data":[]}');
    }
}

if( !isset($upload_save_path) ) {
    exit('{"status":0, "msg":"请设置上传目录!", "id":0, "data":[]}');
}

if( !isset($_FILES['file']) ) {
    exit('{"status":0, "msg":"请设置上传的文件域为 file !", "id":0, "data":[]}');
}

if( W_APPNAME != "admin" ) {
    // 上传时间间隔限制...
    // 限制用户一天时间内上传的大小...
}

include W_ROOT_PATH . '/lib/w_upload.class.php';
$upload = new w_upload();
$upload->max_size = $wconfig['general_upload_max_size'];
$upload->allow_extension = array(
    'gif', 'jpg', 'jpeg', 'png', 'bmp',
    'rar', 'zip', 'pdf', 'xls', 'txt', 'doc',
    'wav', 'aif', 'au', 'mp3', 'ram', 'wma', 'mmf', 'amr', 'aac', 'flac',
    'avi', 'rmvb', 'rm', 'asf', 'divx', 'mpeg', 'mpe', 'wmv', 'mp4', 'mkv', 'vob', '3gp', 'ogg'
);
$upload->save_path = $upload_save_path; // Example: './upload/article'

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

$filepath = str_replace('./upload/', 'upload/', $result['filename']);
$sql = "INSERT INTO `{$wconfig['db']['tablepre']}upload` (`uid`, `uploadtime`, `format`, `filename`, `filepath`, `filesize`, `status`, `width`, `height`, `title`, `description`) VALUES ('{$wuser->uid}', '". W_TIMESTAMP ."', '{$result['extension']}', '{$result['name']}', '{$filepath}', '{$result['size']}', '0', '{$result['width']}', '{$result['height']}', '', '')";
if( $wdb->query($sql) ) {
    exit( json_encode(array('status' => 1, 'msg' => '上传成功', 'id' => $wdb->insert_id, 'data' => $result)) );
} else {
    exit('{"status":0, "msg":"数据库写入错误", "id":0, "data":[]}');
}
