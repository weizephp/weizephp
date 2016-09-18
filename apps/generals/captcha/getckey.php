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

include 'includes/w_captcha.class.php';

$ckey = w_captcha::create_ckey();

if(!empty($ckey)) {
	exit('{"status":1, "msg":"ok", "ckey":"'.$ckey.'", "expire":'.$_W['captchaexpire'].'}');
} else {
	exit('{"status":0, "msg":"Request Denied"}');
}
