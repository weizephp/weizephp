<?php
/**
 * UEditor控制入口
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

define('IN_UEDITOR', true); // WeizePHP

//header('Access-Control-Allow-Origin: http://www.baidu.com'); //设置http://www.baidu.com允许跨域访问
//header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
//date_default_timezone_set("Asia/chongqing");
//error_reporting(E_ERROR);
header("Content-Type: text/html; charset=utf-8");

//------------------
// formtoken 安全验证
if( isset($_GET['formtoken']) ) {
    $formtoken = trim($_GET['formtoken']);
} else if( isset($_POST['formtoken']) ) {
    $formtoken = trim($_POST['formtoken']);
} else {
    $formtoken = '';
}
if( $formtoken != $wuser->formtoken ) {
    echo json_encode(array(
        'status' => 0,
        'msg'    => '非法操作，formtoken错误',
        'state'  => '非法操作，formtoken错误'
    ));
    exit;
}
//------------------


$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("./app/general/ueditor/config.json")), true);
$action = $_GET['action'];

switch ($action) {
    case 'config':
        //------------------
        // 控制插入到编辑器的路径 WeizePHP
        $CONFIG['imageUrlPrefix']        = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
        $CONFIG['scrawlUrlPrefix']       = $CONFIG['imageUrlPrefix'];
        $CONFIG['snapscreenUrlPrefix']   = $CONFIG['imageUrlPrefix'];
        $CONFIG['catcherUrlPrefix']      = $CONFIG['imageUrlPrefix'];
        $CONFIG['videoUrlPrefix']        = $CONFIG['imageUrlPrefix'];
        $CONFIG['fileUrlPrefix']         = $CONFIG['imageUrlPrefix'];
        $CONFIG['imageManagerUrlPrefix'] = $CONFIG['imageUrlPrefix'];
        $CONFIG['fileManagerUrlPrefix']  = $CONFIG['imageUrlPrefix'];
        //------------------
        $result = json_encode($CONFIG);
        break;

    /* 上传图片 */
    case 'uploadimage':
    /* 上传涂鸦 */
    case 'uploadscrawl':
    /* 上传视频 */
    case 'uploadvideo':
    /* 上传文件 */
    case 'uploadfile':
        $result = include("action_upload.php");
        break;

    /* 列出图片 */
    case 'listimage':
        $result = include("action_list.php");
        break;
    /* 列出文件 */
    case 'listfile':
        $result = include("action_list.php");
        break;

    /* 抓取远程文件 */
    case 'catchimage':
        $result = include("action_crawler.php");
        break;

    default:
        $result = json_encode(array(
            'state'=> '请求地址出错'
        ));
        break;
}

/* 输出结果 */
if (isset($_GET["callback"])) {
    if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
        echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
    } else {
        echo json_encode(array(
            'state'=> 'callback参数不合法'
        ));
    }
} else {
    echo $result;
}
