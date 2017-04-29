<?php
/**
 * 显示验证码
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

include W_ROOT_PATH . '/lib/w_captcha.class.php';
$captcha = new w_captcha();
$captcha->display();
