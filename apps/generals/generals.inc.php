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

$cur_app_guest_permission = array();

foreach($_W['guest_permission'] as $v) {
	if(strpos($v, W_APP) === 0) {
		$cur_app_guest_permission[] = $v;
	}
}

if(in_array($_W['permission_flag'], $cur_app_guest_permission)) {
	// ...
} else {
	if(isset($_GET['accesstoken']) || isset($_POST['accesstoken'])) {
		include 'includes/w_accesstoken_user.inc.php';
		$accesstoken_on = true;
	} else {
		include 'includes/w_session_user.inc.php';
		$accesstoken_on = false;
	}
	if( w_user::check_access_permission() == false ) {
		if($accesstoken_on == true) {
			exit('{"status":0, "msg":"Not permission"}');
		}
		if($_W['module'] == 'ueditor') {
		    exit('{"state":"没有权限"}');
		}
		exit('Not permission');
	}
	unset($accesstoken_on);
}

unset($cur_app_guest_permission);
