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

$sql  = "SELECT * FROM `{$_W['db']['tablepre']}adminlog` ORDER BY `logid` DESC";
$logs = $WDB->paged($sql, 30);

include $_W['template_path'].'/system/adminlog.html.php';
