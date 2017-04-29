<?php
/**
 * 登出
 */
if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

$wuser->logout();

w_success('登出成功！', '?m=login');