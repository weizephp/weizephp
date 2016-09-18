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

$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("apps/generals/ueditor/w-config.json")), true);
$action = $_GET['action'];

switch ($action) {
    case 'config':
        //------------------
        // 控制插入到编辑器的路径 By WeizePHP
        $CONFIG['imageUrlPrefix']        = dirname($_SERVER['SCRIPT_NAME']);
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
        $result = include("action_upload.inc.php");
        break;

        /* 列出图片 */
    case 'listimage':
        $result = include("action_list.inc.php");
        break;
        /* 列出文件 */
    case 'listfile':
        $result = include("action_list.inc.php");
        break;

        /* 抓取远程文件 */
    case 'catchimage':
        $result = include("action_crawler.inc.php");
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
