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

$_W['accesstoken']                 = array();
$_W['accesstoken']['accesstoken']  = '';
$_W['accesstoken']['expires']      = 0;
$_W['accesstoken']['ip']           = '';
$_W['accesstoken']['uid']          = 0;
$_W['accesstoken']['lastvisit']    = 0;

/** ------------------------------------------------------------------- */

/**
 * Create access token
 * @return string client access token
 */
function w_create_accesstoken($uid) {
    global $_W, $WDB;
	
    $accesstoken        = w_uniqid_random();
    $microtime          = microtime(true);
    $sign               = w_create_sign(array($_W['authkey'], $accesstoken, $microtime, 'accesstoken'));
    $client_accesstoken = $accesstoken.','.$microtime.','.$sign;
	
	$expires            = W_TIMESTAMP + $_W['accesstoken_key_expire'];
	$ip                 = $_SERVER['REMOTE_ADDR'];
	
    $sql = "INSERT INTO `{$_W['db']['tablepre']}accesstokens`(`accesstoken`, `expires`, `ip`, `uid`, `lastvisit`) VALUES ('{$accesstoken}', '{$expires}', '{$ip}', '{$uid}', '". W_TIMESTAMP ."')";
    $WDB->query($sql);
	
	return $client_accesstoken;
}

/** ------------------------------------------------------------------- */

/**
 * 接收 accesstoken ,然后验证 accesstoken
 */

$accesstoken = '';
if(isset($_GET['accesstoken'])) {
	$accesstoken = trim($_GET['accesstoken']);
} else if(isset($_POST['accesstoken'])) {
	$accesstoken = trim($_POST['accesstoken']);
}

$accesstoken_arr = array();
if(preg_match('/^[a-zA-Z0-9]{19}\,[0-9]{10}.[0-9]{0,5}\,[a-zA-Z0-9]{32}$/', $accesstoken) === 1) {
	$accesstoken_arr    = explode(',', $accesstoken);
	$accesstoken_arr[1] = (float)$accesstoken_arr[1];//[*]
}

$accesstoken_key_verification = false;
if(!empty($accesstoken_arr)) {
	$sign = w_create_sign(array($_W['authkey'], $accesstoken_arr[0], $accesstoken_arr[1], 'accesstoken'));
	if(($sign === $accesstoken_arr[2]) && ((W_TIMESTAMP - $accesstoken_arr[1]) < $_W['accesstoken_key_expire'])) {
		$accesstoken_key_verification = true;
	}
}

/**
 * 如果 accesstoken 验证通过，就读取 accesstoken 信息
 */

$db_accesstoken = array();
if($accesstoken_key_verification === true) {
	// 读取 accesstoken
	$sql = "SELECT `accesstoken`, `expires`, `ip`, `uid`, `lastvisit` FROM `{$_W['db']['tablepre']}accesstokens` WHERE `accesstoken`='{$accesstoken_arr[0]}'";
	$db_accesstoken = $WDB->get_row($sql);
	if(!empty($db_accesstoken) && ($db_accesstoken['expires'] > W_TIMESTAMP)) {
		// 获取数据
		$_W['accesstoken'] = $db_accesstoken;
		$sql = "UPDATE `{$_W['db']['tablepre']}accesstokens` SET `lastvisit`='". W_TIMESTAMP ."' WHERE `accesstoken`='{$accesstoken_arr[0]}'";
		$WDB->query($sql);
	} else {
		// 删除过期的 accesstoken
	    $sql = "DELETE FROM `{$_W['db']['tablepre']}accesstokens` WHERE `expires`<'". W_TIMESTAMP ."'";
	    $WDB->query($sql);
	}
}

/**
 * 删除用完的变量
 */

unset($accesstoken, $accesstoken_arr, $accesstoken_key_verification, $db_accesstoken);
