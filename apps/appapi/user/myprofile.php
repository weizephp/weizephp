<?php
/**
 * 获取自己的个人信息. 例子
 */

if(!defined('IN_WEIZEPHP')){exit('Access Denied');}

$sql  = "SELECT * FROM `{$_W['db']['tablepre']}users` WHERE `uid`='{$_W['user']['uid']}'";
$info = $WDB->get_row($sql);

$json_arr = array(
    'status' => 1,     // 成功状态。0为错误，1为成功（必须）
	'msg'    => 'ok',  // 成功或者错误消息
	'info'   => $info  // 个人
);

exit( json_encode($json_arr) );
