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

// formtoken生成
$formtoken = w_security::set_formtoken();

// 获取用户
$sql   = "SELECT `uid`, `username`, `email`, `status`, `roleid`, `lastlogintime`, `lastloginip` FROM `{$_W['db']['tablepre']}users` ORDER BY `uid` DESC";
$users = $WDB->paged($sql, 30);

// 获取角色
$sql   = "SELECT `roleid`, `status`, `rolename` FROM `{$_W['db']['tablepre']}roles` WHERE 1";
$roles = $WDB->get_all($sql);

// 获取用户对应的角色
foreach($users['paged_data'] as $uk => $uv) {
    $users['paged_data'][$uk]['rolename'] = '未定义角色';
    foreach($roles as $rv) {
        if($uv['roleid'] == $rv['roleid']) {
			$users['paged_data'][$uk]['rolename'] = htmlspecialchars($rv['rolename']);
		}
    }
}

// 载入列表页面
include $_W['template_path'].'/user/list.html.php';
