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

//-------------------------

include 'includes/w_accesstoken_user.inc.php';
include 'includes/w_captcha.class.php';

//-------------------------

$cur_app_guest_permission = array();

foreach($_W['guest_permission'] as $v) {
	if(strpos($v, W_APP) === 0) {
		$cur_app_guest_permission[] = $v;
	}
}

//-------------------------

if(in_array($_W['permission_flag'], $cur_app_guest_permission)) {
	// 游客操作...
} else {
	// 登录用户操作
	if( w_user::check_access_permission() == false ) {
		exit('{"status":0, "msg":"Not permission"}');
	}
}

unset($cur_app_guest_permission);
