<?php
/**
 * 登出
 */
if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

$wuser->logout();

exit('{"status":1, "msg":"登出成功！"}');