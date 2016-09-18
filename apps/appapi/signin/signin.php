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

$username = isset($_POST['username']) ? addslashes(trim($_POST['username'])) : "";
$password = isset($_POST['password']) ? addslashes(trim($_POST['password'])) : "";//这里接收的[客户端MD5之后的密码]

if(empty($username)) {
    exit('{"status":0, "msg":"账号不能为空"}');
}
if(empty($password)) {
    exit('{"status":0, "msg":"密码不能为空"}');
}

$result = w_user::login($username, $password);

if(($result['status'] == -15) || ($result['status'] == -16)) {
    $time_str = $result['timeleft'] < 60 ? $result['timeleft']."秒" : ceil($result['timeleft']/60)."分钟";
}

switch ($result['status']) {
    case 1:
		$json_arr = array(
		    'status'      => 1,
			'msg'         => 'ok',
			'accesstoken' => w_create_accesstoken($_W['user']['uid']),
			'user'        => $_W['user']
		);
		$json_arr['user']['uid']    = intval($json_arr['user']['uid']);
		$json_arr['user']['roleid'] = intval($json_arr['user']['roleid']);
		$json_arr['user']['points'] = intval($json_arr['user']['points']);
		exit( json_encode($json_arr) );
        break;
    case -14:
        exit('{"status":0, "msg":"登录账号不正确"}');
        break;
    case -15:
        exit('{"status":0, "msg":"IP登录错误次数过多,请'.$time_str.'后再试"}');
        break;
    case -16:
        exit('{"status":0, "msg":"账号登录错误次数过多,请'.$time_str.'后再试"}');
        break;
    case -17:
        exit('{"status":0, "msg":"用户不存在,或者已经被删除,您还可以尝试'.$result['remaincount'].'次"}');
        break;
    case -18:
        exit('{"status":0, "msg":"登陆密码错误,您还可以尝试'.$result['remaincount'].'次"}');
        break;
    default:
        exit('{"status":0, "msg":"没有任何操作"}');
}
