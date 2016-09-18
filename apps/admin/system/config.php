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

$formtoken = w_security::set_formtoken();

$sql  = "SELECT `ckey`,`cvalue` FROM `{$_W['db']['tablepre']}config`";
$list = $WDB->get_all($sql);

$site_cfg = array();
foreach($list as $k => $v) {
	$site_cfg[$v['ckey']] = htmlspecialchars($v['cvalue']);
}

include $_W['template_path'].'/system/config.html.php';
