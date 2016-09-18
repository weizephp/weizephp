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

if(isset($_W['user']['uid']) && ($_W['user']['uid'] > 0)) {
	w_user::accesstoken_logout($_W['accesstoken']['accesstoken']);
}

exit('{"status":1, "msg":"登出成功"}');