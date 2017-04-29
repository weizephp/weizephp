<?php
/**
 * 角色权限控制
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

$sql   = "SELECT `roleid`, `status`, `rolename` FROM `{$wconfig['db']['tablepre']}role` WHERE 1";
$roles = $wdb->get_all($sql);
foreach($roles as $k => $v) {
    $roles[$k]['rolename'] = htmlspecialchars($v['rolename']);
}

include $wconfig['theme_path'] . '/admin/user/rolepermissioncontrol.html.php';
