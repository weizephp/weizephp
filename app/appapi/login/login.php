<?php
/**
 * accesstoken 登陆方式
 */
if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

// 接收数据
$username = isset($_POST['username']) ? addslashes(trim($_POST['username'])) : "";
$password = isset($_POST['password']) ? addslashes(trim($_POST['password'])) : ""; // 这里是客户端MD5之后的密码

// 检查数据输入
if(empty($username)) {
    exit('{"status":0, "msg":"账号不能为空"}');
}
if(empty($password)) {
    exit('{"status":0, "msg":"密码不能为空"}');
}

// 登录验证
$login_result = $wuser->login($username, $password, "accesstoken");

if(($login_result['status'] == -15) || ($login_result['status'] == -16)) {
    $time_str = $login_result['timeleft'] < 60 ? $login_result['timeleft']."秒" : ceil($login_result['timeleft']/60)."分钟";
}

switch ($login_result['status']) {
    case 1:
        $data = array(
            "status"      => 1,
            "msg"         => "ok",
            "accesstoken" => $login_result['accesstoken'],
            "uid"         => $wuser->uid,
            "username"    => $wuser->username,
            "roleid"      => $wuser->roleid,
            "point"       => $wuser->point,
            "balance"     => $wuser->balance,
            "realname"    => $wuser->realname
        );
        exit( json_encode($data) );
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
        exit('{"status":0, "msg":"用户不存在,或者已经被删除,您还可以尝试'.$login_result['remaincount'].'次"}');
        break;
    case -18:
        exit('{"status":0, "msg":"登陆密码错误,您还可以尝试'.$login_result['remaincount'].'次"}');
        break;
    default:
        exit('{"status":0, "msg":"没有任何操作"}');
}