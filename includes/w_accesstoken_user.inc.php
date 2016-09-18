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

// 1、载入w_accesstoken.inc.php
include 'includes/w_accesstoken.inc.php';
// 2、载入w_menus.inc.php
include 'includes/w_menus.inc.php';
// 3、载入w_user.class.php
include 'includes/w_user.class.php';

// 读取用户信息
if($_W['accesstoken']['uid'] > 0) {
	$sql = "SELECT `uid`, `username`, `roleid`, `points`, `balances`, `realname` FROM `{$_W['db']['tablepre']}users` WHERE `uid`='{$_W['accesstoken']['uid']}'";
	$row = $WDB->get_row($sql);
	if(!empty($row)) {
		$_W['user'] = $row;
	}
	unset($sql, $row);
}
