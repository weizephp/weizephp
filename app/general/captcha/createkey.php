<?php
/**
 * 创建 captcha key
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

include W_ROOT_PATH . '/lib/w_captcha.class.php';
$captcha = new w_captcha();
$captchakey = $captcha->createkey();
echo '{"status":1, "msg":"ok", "captchakey":"'. $captchakey .'"}';
