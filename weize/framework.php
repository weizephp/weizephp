<?php
/**
 * +----------------------------------------------------------------------
 * | WeizePHP framework
 * +----------------------------------------------------------------------
 * | Copyright (c) 2013-2113 http://weizephp.75hh.com/ All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: Ze Wei <E-mail:weizesw@gmail.com> <QQ:310472156>
 * +----------------------------------------------------------------------
 * | Description: 框架核心
 * +----------------------------------------------------------------------
 */

if( !defined('W_PERMISSION') || !defined('W_APPNAME') ) {
	exit('Access Denied');
}

/** ------------------------------------------------------------------- */

// 定义常量
define('IN_WEIZEPHP', true);
define('W_ROOT_PATH', dirname(dirname(__FILE__)));

/** ------------------------------------------------------------------- */

// 包含配置文件，并设置时区
include W_ROOT_PATH . '/config/w_config.inc.php';
date_default_timezone_set($wconfig['timezone']);

/** ------------------------------------------------------------------- */

// 定义时间戳常量
define('W_TIMESTAMP', time());

/** ------------------------------------------------------------------- */

// 包含框架函数
include W_ROOT_PATH . '/weize/function.php';

/** ------------------------------------------------------------------- */

// 记录错误日志
if( $wconfig['error_reporting'] === true ) {
    define('W_DISPLAY_DEBUG', true);
	set_error_handler('w_error_handler');
    register_shutdown_function('w_shutdown');
}

/** ------------------------------------------------------------------- */

// 特殊字符转义处理
if( get_magic_quotes_gpc() == 1 ) {
    $_GET    = w_stripslashes($_GET);
    $_POST   = w_stripslashes($_POST);
    $_COOKIE = w_stripslashes($_COOKIE);
}

/** ------------------------------------------------------------------- */

// ...

/** ------------------------------------------------------------------- */

// 定义模块(Module)和操作(Action)
$m = isset($_GET['m']) && (preg_match('/^[a-z_]+$/', $_GET['m']) == 1) ? $_GET['m'] : $wconfig['m'];
$a = isset($_GET['a']) && (preg_match('/^[a-z_]+$/', $_GET['a']) == 1) ? $_GET['a'] : $wconfig['a'];

/** ------------------------------------------------------------------- */

// 是否是AJAX访问（APP客户端和ajax访问，统一为ajax访问模式，其他默认为传统web访问模式）
if( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') ) {
    $isajax = 1;
} else if( isset($_GET['accesstoken']) || isset($_POST['accesstoken']) ) {
	$isajax = 1;
} else if( isset($_GET['isajax']) && ($_GET['isajax'] == 1) ) {
    $isajax = 1;
} else if( isset($_POST['isajax']) && ($_POST['isajax'] == 1) ) {
    $isajax = 1;
} else {
    $isajax = 0;
}

/** ------------------------------------------------------------------- */

// 定义脚本标识、定义要执行的脚本
$wflag   = W_APPNAME . '/' . $m . '/' . $a;
$wscript = W_ROOT_PATH . '/app/' . W_APPNAME . '/' . $m . '/' . $a . '.php';

/** ------------------------------------------------------------------- */

// 如果脚本不存在，提示错误
if( !is_file($wscript) ) {
    if( $isajax == 1 ) {
        exit('{"status":0, "msg":"404. Script Not Found: ./app/'. $wflag .'.php"}');
    } else {
        exit('404. Script Not Found: ./app/'. $wflag .'.php');
    }
}

/** ------------------------------------------------------------------- */

// 定义模板路径
$wconfig['theme_path'] = W_ROOT_PATH . '/theme/' . $wconfig['theme'];
$wconfig['theme_skin'] = $wconfig['public_path'] . '/theme/' . $wconfig['theme'];

/** ------------------------------------------------------------------- */

// 包含语言包里的框架菜单文件
include W_ROOT_PATH . '/lang/' . $wconfig['lang'] . '/w_menu.inc.php';

/** ------------------------------------------------------------------- */

// 包含框架语言包
include W_ROOT_PATH . '/lang/'. $wconfig['lang'] .'/w_framework.lang.php';

/** ------------------------------------------------------------------- */

// 包含操作语言包
$w_act_lang = W_ROOT_PATH . '/lang/'. $wconfig['lang'] .'/'. W_APPNAME .'/'. $m .'/'. $a .'.lang.php';
if( is_file($w_act_lang) ) { include $w_act_lang; }
unset($w_act_lang);

/** ------------------------------------------------------------------- */

// 连接MySQL数据库（为了不让程序显得复杂，在做数据库主从时，建议用MySQL中间件来解决）
include W_ROOT_PATH . '/weize/w_mysqli.class.php';
$wdb = new w_mysqli($wconfig['db']['host'], $wconfig['db']['username'], $wconfig['db']['password'], $wconfig['db']['name'], $wconfig['db']['port']);
if( $wdb->connect_error ) {
    die( 'Connect Error (' . $wdb->connect_errno . ') ' . $wdb->connect_error );
}
if( !$wdb->set_charset('utf8') ) {
    die( "Error loading character set utf8: {$wdb->error}" );
}

/** ------------------------------------------------------------------- */

// 包含用户管理类库
include W_ROOT_PATH . '/weize/w_user.class.php';
$wuser = new w_user();

/** ------------------------------------------------------------------- */

// 用户初始化，默认获取游客
$wuser->init();

/** ------------------------------------------------------------------- */

// 如果权限检查通过，就加载执行的脚本，否则提示没权限
if( $wuser->check_access_permission() === TRUE ) {
    include $wscript;
} else {
    if( $wuser->uid > 0 ) {
        if( ($isajax == 1) || (W_APPNAME == 'general' && $m == 'ueditor') ) {
            echo json_encode(array(
                'status' => 0,
                'msg'    => $wlang['fw_permission_denied'],
                'state'  => $wlang['fw_permission_denied']
            ));
            exit;
        } else {
            w_error($wlang['fw_permission_denied']);
        }
    } else {
        if( ($isajax == 1) || (W_APPNAME == 'general' && $m == 'ueditor') ) {
            echo json_encode(array(
                'status' => 0,
                'msg'    => $wlang['fw_permission_denied_please_login'],
                'state'  => $wlang['fw_permission_denied_please_login']
            ));
            exit;
        }
        if( W_APPNAME == 'admin' ) {
            //w_error($wlang['fw_permission_denied_please_login'], '?m=login');
            header('Location: ?m=login');exit;
        } else {
            //w_error($wlang['fw_permission_denied_please_login'], 'member.php?m=login');
            header('Location: member.php?m=login');exit;
        }
    }
}

/** ------------------------------------------------------------------- */
