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

$ckey    = isset($_GET['ckey']) ? $_GET['ckey'] : '';
$captcha = w_captcha::check_ckey($ckey);
$cid     = $captcha !== false ? $captcha['cid'] : 0;

w_captcha::default_img($cid);
