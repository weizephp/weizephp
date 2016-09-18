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

$sql   = "SELECT `roleid`, `status`, `rolename` FROM `{$_W['db']['tablepre']}roles` WHERE 1";
$roles = $WDB->get_all($sql);
foreach($roles as $k => $v) {
    $roles[$k]['rolename'] = htmlspecialchars($v['rolename']);
}

include $_W['template_path'].'/user/rolepermissioncontrol.html.php';
