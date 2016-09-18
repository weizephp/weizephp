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

if(!defined('W_APP')) {
	exit('Access Denied');
}

define('IN_WEIZEPHP', true);
define('W_ROOT_PATH', dirname(__FILE__));
define('W_SCRIPT',    basename($_SERVER['SCRIPT_NAME']));
define('W_TIMESTAMP', time());

include 'config.inc.php';

date_default_timezone_set( $_W['timezone'] );

/** ------------------------------------------------------------------- */

/**
 * Un-quotes a quoted string
 * @param string or array $string
 * @return string or array
 */
function w_stripslashes($string) {
    if(empty($string)) {
        return $string;
    }
    if(is_array($string)) {
        foreach($string as $key => $val) {
            $string[$key] = w_stripslashes($val);
        }
    } else {
        $string = stripslashes($string);
    }
    return $string;
}

/**
 * Quote string with slashes
 * @param string or array $string
 * @return string or array
 */
function w_addslashes($string) {
    if(empty($string)) {
        return $string;
    }
    if(is_array($string)) {
        foreach($string as $key => $val) {
            unset($string[$key]);
            $string[addslashes($key)] = w_addslashes($val);
        }
    } else {
        $string = addslashes($string);
    }
    return $string;
}

/**
 * Send a cookie
 * @param string $name
 * @param string $value
 * @param int $expire
 * @param boolean $httponly
 */
function w_setcookie($name, $value, $expire = 0, $httponly = false) {
    global $_W;
    $secure = $_W['protocol'] == 'https://' ? true : false;
    $path   = $httponly && PHP_VERSION < '5.2.0' ? $_W['cookie']['path'].'; HttpOnly' : $_W['cookie']['path'];
    if(PHP_VERSION < '5.2.0') {
        setcookie($name, $value, $expire, $path, $_W['cookie']['domain'], $secure);
    } else {
        setcookie($name, $value, $expire, $path, $_W['cookie']['domain'], $secure, $httponly);
    }
}

/**
 * Get part of string
 * @param string $str
 * @param int $start
 * @param int $length
 * @param string $charset
 * @param boolean $suffix
 * @return string
 */
function w_substr($str, $start = 0, $length = 30, $charset = "utf-8", $suffix = false) {
    $suffix_str = $suffix ? '…' : '';
    if(function_exists('mb_substr')) {
        return mb_substr($str, $start, $length, $charset) . $suffix_str;
    } elseif(function_exists('iconv_substr')) {
        return iconv_substr($str, $start, $length, $charset) . $suffix_str;
    } else {
        $pattern = array();
        $pattern['utf-8'] = '/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/';
        $pattern['gb2312'] = '/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/';
        $pattern['gbk'] = '/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/';
        $pattern['big5'] = '/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/';
        preg_match_all($pattern[$charset], $str, $matches);
        $slice = implode("", array_slice($matches[0], $start, $length));
        return $slice . $suffix_str;
    }
}

/**
 * Count data sizes
 * @param int $size
 * @return string
 */
function w_sizecount($size) {
    if($size >= 1073741824) {
        $size = round($size / 1073741824 * 100) / 100 . ' GB';
    } elseif($size >= 1048576) {
        $size = round($size / 1048576 * 100) / 100 . ' MB';
    } elseif($size >= 1024) {
        $size = round($size / 1024 * 100) / 100 . ' KB';
    } else {
        $size = $size . ' Bytes';
    }
    return $size;
}

/**
 * Get client ip
 * @return string
 */
function w_get_client_ip() {
    $ip = $_SERVER['REMOTE_ADDR'];
    if( isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP']) ) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches) ) {
        foreach ($matches[0] as $value_ip) {
            if (!preg_match('/^(10|172\.16|192\.168)\./', $value_ip)) {
                $ip = $value_ip;
                break;
            }
        }
    }
    return $ip;
}

/**
 * Random string
 * @param int $length
 * @return string
 */
function w_random($length = 6) {
    if($length > 32) {
        $length = 32;
    }
    return substr(str_shuffle('abcdefghijklmnpqrtuvwxyzABCDEFGHIJKLMNPQRTUVWXYZ123456789'), -$length);
}

/**
 * Uniqid random string
 * @param int $length
 * @return string
 */
function w_uniqid_random($length = 6) {
    return uniqid(w_random($length));
}

/**
 * Create sign
 * @param array $arr
 * @return string
 */
function w_create_sign($arr) {
	sort($arr);
	$str = "";
	foreach($arr as $v) {
		$str .= $v;
	}
	return md5($str);
}

/**
 * Replace language
 * @param string $lang
 * @param array  $array
 * @return string
 *
 * Use：
 * $_lang['hello'] = '你好{name}';
 * $_lang['hello'] = w_lang($_lang['hello'], array('name'=>'韦泽')); // output：你好韦泽
 */
function w_lang($lang, $array = array()) {
    if(!empty($array)) {
        foreach($array as $k => $v) {
            $lang = str_replace('{'.$k.'}', $v, $lang);
        }
        return $lang;
    } else {
        return $lang;
    }
}

/** ------------------------------------------------------------------- */

/**
 * Send an error message to the defined error handling routines
 * @param int $errno
 * @param string $errstr
 * @param string $errfile
 * @param int $errline
 */
function w_error_log($errno, $errstr, $errfile, $errline) {
    $current_url = htmlspecialchars($_SERVER['REQUEST_URI']);
    $date        = date('Y-m-d H:i:s', W_TIMESTAMP);
    $message     = "<?php exit; ?> [DATE]{$date} [URL]{$current_url} [ERRNO]{$errno} [ERRSTR]{$errstr} [ERRFILE]{$errfile} [ERRLINE]{$errline}\n";
    error_log($message, 3, ROOT_PATH.'/data/log/'.date('Ymd', W_TIMESTAMP).'_php_error.php');
}

/**
 * Defined error handler function
 * @param int $errno
 * @param string $errstr
 * @param string $errfile
 * @param int $errline
 */
function w_error_handler($errno, $errstr, $errfile, $errline) {
    w_error_log($errno, $errstr, $errfile, $errline);
}

/**
 * Get the last occurred error
 */
function w_shutdown() {
    $errinfo = error_get_last();
    if(!empty($errinfo) && isset($errinfo['type'])) {
        w_error_log($errinfo['type'], $errinfo['message'], $errinfo['file'], $errinfo['line']);
    }
}

/** ------------------------------------------------------------------- */

/**
 * URL rewrite
 * @param string $url
 * @return string
 */
function w_url($url) {
    global $_W;
    if( ($_W['rewrite']['on'] === true) && isset($_W['rewrite'][W_APP]) ) {
        $patterns = $replacements = array();
        foreach($_W['rewrite'][W_APP] as $key => $value) {
            $patterns[]     = $key;
            $replacements[] = $value;
        }
        $url = preg_replace($patterns, $replacements, $url);
        unset($patterns, $replacements);
    }
    return $url;
}

/**
 * Thumb url
 * @param string $folder
 * @param string $source
 * @param int $width
 * @param int $height
 * @return string
 */
function w_thumb_url($folder, $source, $width, $height) {
    global $_W;
    $pathinfo   = pathinfo($source);
    $extension  = isset($pathinfo['extension']) ? strtolower($pathinfo['extension']) : '';
    $thumb_path = "data/thumbnails/". $folder ."/". $source .".". $width ."x". $height .".". $extension;
    if(is_file($thumb_path)) {
        $thumb_url = $_W['thumb_path'] . "/". $folder ."/". $source .".". $width ."x". $height .".". $extension;
    } else {
        $thumb_url = 'generals.php?m=image&a=thumb&f='. $folder .'&s='. $source .'&w='. $width .'&h='. $height;
    }
    return $thumb_url;
}

/** ------------------------------------------------------------------- */

/**
 * Success message
 * @param string $message
 * @param string $url
 */
function w_successmessage($message, $url = '') {
    global $_W, $_lang;
    include $_W['template_path'] . '/w_successmessage.html.php';
    exit;
}

/**
 * Error message
 * @param string $message
 * @param string $url
 */
function w_errormessage($message, $url = '') {
    global $_W, $_lang;
    include $_W['template_path'] . '/w_errormessage.html.php';
    exit;
}

/**
 * Error 404
 * @param string $url
 */
function w_error404($url = '') {
    global $_W, $_lang;
    include $_W['template_path'] . '/w_error404.html.php';
    exit;
}

/** ------------------------------------------------------------------- */

if($_W['error_reporting'] === true) {
    set_error_handler('w_error_handler');
    register_shutdown_function('w_shutdown');
}

if(get_magic_quotes_gpc() == 1) {
    $_GET    = w_stripslashes($_GET);
    $_POST   = w_stripslashes($_POST);
    $_COOKIE = w_stripslashes($_COOKIE);
}

/** ------------------------------------------------------------------- */

include 'includes/w_mysqli.class.php';
$WDB = new w_mysqli($_W['db']['host'], $_W['db']['username'], $_W['db']['password'], $_W['db']['name'], $_W['db']['port']);
if($WDB->connect_error) {
    $WDB->w_mysql_error_log( 'Connect Error (' . $WDB->connect_errno . ') ' . $WDB->connect_error );
}
if(!$WDB->set_charset($_W['db']['charset'])) {
    $WDB->w_mysql_error_log( "Error loading character set {$_W['db']['charset']}: {$WDB->error}" );
}

/** ------------------------------------------------------------------- */

if( empty($_W['protocol']) ) {
    if( isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off') ) {
        $_W['protocol'] = 'https';
    } elseif( isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] == 443) ) {
        $_W['protocol'] = 'https';
    } else {
        $_W['protocol'] = 'http';
    }
}

$_W['script_dir'] = dirname($_SERVER['SCRIPT_NAME']);
if($_W['script_dir'] == '\\') {
    $_W['script_dir'] = '/';
} else {
    $_W['script_dir'] = $_W['script_dir'] . '/';
}

$_W['siteurl'] = $_W['protocol'] . '://' . $_SERVER['HTTP_HOST'] . $_W['script_dir'];

/** ------------------------------------------------------------------- */

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    $_W['ajaxrequest'] = 1;
} else if(isset($_GET['ajaxrequest']) && ($_GET['ajaxrequest'] == 1)) {
    $_W['ajaxrequest'] = 1;
} else if(isset($_POST['ajaxrequest']) && ($_POST['ajaxrequest'] == 1)) {
    $_W['ajaxrequest'] = 1;
} else {
    $_W['ajaxrequest'] = 0;
}

/** ------------------------------------------------------------------- */

$_W['module']          = isset($_GET['m']) && (preg_match('/^[a-z]+$/', $_GET['m']) == 1) ? $_GET['m'] : $_W['module'];
$_W['action']          = isset($_GET['a']) && (preg_match('/^[a-z]+$/', $_GET['a']) == 1) ? $_GET['a'] : $_W['action'];

$_W['permission_flag'] = W_APP .'/'. $_W['module'] .'/'. $_W['action'];

$_W['run_file']        = 'apps/'. W_APP .'/'. $_W['module'] .'/'. $_W['action'] .'.php';

$_W['public_path']     = empty($_W['public_path']) ? 'public' : $_W['public_path'];
$_W['skin_path']       = $_W['public_path'] .'/skins/'. W_APP .'/'. $_W['template'];
$_W['template_path']   = 'templates/'. W_APP .'/'. $_W['template'];
$_W['attachment_path'] = empty($_W['attachment_path']) ? 'data/attachments' : $_W['attachment_path'];
$_W['thumb_path']      = empty($_W['thumb_path']) ? 'data/thumbnails' : $_W['thumb_path'];

/** ------------------------------------------------------------------- */

include 'languages/'. $_W['language_pack'] .'/common.lang.php';
include 'languages/'. $_W['language_pack'] .'/'. W_APP . '.lang.php';

include 'apps/'. W_APP .'/'. W_APP .'.inc.php';

if(is_file($_W['run_file'])) {
    include $_W['run_file'];
} else {
    exit('404 Page Not Found');
}