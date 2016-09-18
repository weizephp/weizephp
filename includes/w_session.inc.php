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

$_W['session']              = array();
$_W['session']['sid']       = '';
$_W['session']['ip']        = '';
$_W['session']['uid']       = 0;
$_W['session']['lastvisit'] = 0;
$_W['session']['salt']      = '';
$_W['session']['formtoken'] = '';

/** ------------------------------------------------------------------- */

/**
 * Create session id
 * @return string session id
 */
function w_create_sessionid() {
	global $_W;
	$sid       = w_uniqid_random();
	$microtime = microtime(true);
	$sign      = w_create_sign(array($_W['authkey'], $sid, $microtime, 'session'));
	w_setcookie( $_W['cookie']['prefix'].'skey', $sid.','.$microtime.','.$sign, 0, true );
	unset($microtime, $sign);
	return $sid;
}

/** ------------------------------------------------------------------- */

if( !isset($_COOKIE[ $_W['cookie']['prefix'].'skey' ]) ) {
    
    /**
     * 如果没有客户端没有 session key ，就创建
     */
    w_create_sessionid();
    
} else {
	
    /**
     * 否则就验证 session key 是否正确，并读取 session 数据
     */
    
    // 验证 session key
	$skey = array();
	if(preg_match('/^[a-zA-Z0-9]{19}\,[0-9]{10}.[0-9]{0,5}\,[a-zA-Z0-9]{32}$/', $_COOKIE[ $_W['cookie']['prefix'].'skey' ]) === 1) {
		$skey    = explode(',', $_COOKIE[ $_W['cookie']['prefix'].'skey' ]);
		$skey[1] = (float)$skey[1];//[*]
	}
	
	$client_salt = isset($_COOKIE[ $_W['cookie']['prefix'].'salt' ]) && (preg_match('/^[a-zA-Z0-9]{6}$/', $_COOKIE[ $_W['cookie']['prefix'].'salt' ]) === 1) ? $_COOKIE[ $_W['cookie']['prefix'].'salt' ] : "";
	
	$skey_verification = false;
	if(!empty($skey)) {
		$sign = w_create_sign(array($_W['authkey'], $skey[0], $skey[1], 'session'));
		if(($sign === $skey[2]) && ((W_TIMESTAMP - $skey[1]) < $_W['skey_expire'])) {
			$skey_verification = true;
		}
	}
	
	// 如果 session key 验证通过，就读取 session 信息
	$session = array();
	if($skey_verification === true) {
		$sql = "SELECT `sid`, `ip`, `uid`, `lastvisit`, `salt`, `formtoken` FROM `{$_W['db']['tablepre']}sessions` WHERE `sid`='{$skey[0]}'";
		$session = $WDB->get_row($sql);
	}
	
	// 定义“最后的访问时间戳”和“访问随机盐”
	$lastvisit = W_TIMESTAMP;
	$salt      = w_random();
	
	// “访问随机盐”以 cookie 的方式发送到客户端
	w_setcookie( $_W['cookie']['prefix'].'salt', $salt, 0 );
	
	// 验证 session 的“访问随机盐”
	$session_verification = false;
	if(!empty($session) && ($session['salt'] === $client_salt)) {
		$session_verification = true;
	}
	
	// 如果 session 验证不通过，就删除过期的 session ，然后生成新的 session ，否则就直接更新 session 的“最后的访问时间戳”和“访问随机盐”
	if($session_verification === false) {
		$guest_lastvisit_expire = W_TIMESTAMP - $_W['guest_sid_expire'];
		$user_lastvisit_expire  = W_TIMESTAMP - $_W['user_sid_expire'];
		
		if(empty($session)) {
			$sql = "DELETE FROM `{$_W['db']['tablepre']}sessions` WHERE (`uid`='0' AND `lastvisit`<'{$guest_lastvisit_expire}') OR (`uid`>'0' AND `lastvisit`<'{$user_lastvisit_expire}')";
		} else {
			$sql = "DELETE FROM `{$_W['db']['tablepre']}sessions` WHERE `sid`='{$skey[0]}' OR (`uid`='0' AND `lastvisit`<'{$guest_lastvisit_expire}') OR (`uid`>'0' AND `lastvisit`<'{$user_lastvisit_expire}')";
		}
		$WDB->query($sql);
		
		$sid = w_create_sessionid();
		$ip  = $_SERVER['REMOTE_ADDR'];
		$uid = 0;
		
		$sql = "INSERT INTO `{$_W['db']['tablepre']}sessions`(`sid`, `ip`, `uid`, `lastvisit`, `salt`, `formtoken`) VALUES ('{$sid}', '{$ip}', '{$uid}', '{$lastvisit}', '{$salt}', '')";
		if($WDB->query($sql)) {
			$_W['session']['sid']       = $sid;
			$_W['session']['ip']        = $ip;
			$_W['session']['uid']       = $uid;
			$_W['session']['lastvisit'] = $lastvisit;
			$_W['session']['salt']      = $salt;
		}
		
		unset($guest_lastvisit_expire, $user_lastvisit_expire, $sid, $ip);
	} else {
		$_W['session'] = $session;
		$sql = "UPDATE `{$_W['db']['tablepre']}sessions` SET `lastvisit`='{$lastvisit}',`salt`='{$salt}' WHERE `sid`='{$skey[0]}'";
		$WDB->query($sql);
	}
	
	// 删除用完了的变量
	unset($skey, $client_salt, $skey_verification, $session, $lastvisit, $session_verification);
	
}
