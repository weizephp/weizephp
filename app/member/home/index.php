<?php
/**
 * 会员中心
 */
if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

//$mu = $wuser->get_curapp_user_menu();
//print_r($mu);exit;

include $wconfig['theme_path'] . '/member/home/index.html.php';
