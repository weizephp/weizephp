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

$_W['user'] = array();
$_W['user']['uid']      = 0;
$_W['user']['username'] = '';
$_W['user']['roleid']   = 0;
$_W['user']['points']   = 0;
$_W['user']['balances'] = '0.00';
$_W['user']['realname'] = '';

/*
#状态码#
-----------------------------------------
1   正确状态
-----------------------------------------
0   未知错误
-----------------------------------------
-1  用户名只允许使用字母、数字、下划线，且必须需以字母开头
-2  用户名不能小于3个字符
-3  用户名不能大于15个字符
-4  该用户名已经被注册
-5  两次输入的用户名不一致
-6  两次输入的密码不一致
-7  密码不能小于6个字符
-8  密码不能大于15个字符
-9  E-mail格式有误
-10 该E-mail已经被注册
-11 两次输入的邮箱不一致
-----------------------------------------
-12 手机格式不正确
-13 手机已经被占用
-----------------------------------------
-14 登录账号不正确
-15 IP登录错误次数过多,请N分钟后再试
-16 账号登录错误次数过多,请N分钟后再试
-17 用户不存在,或者已经被删除,您还可以尝试N次
-18 登陆密码错误,您还可以尝试N次
-----------------------------------------
*/

class w_user {
    /**
     * 检查用户名
     * @param string $username
     * @return int
     */
    public static function check_username($username) {
        global $_W, $WDB;
        if(preg_match('/^[a-zA-Z]+[a-zA-Z0-9_]+$/', $username) == 0) {
            return -1;
        }
        $strlen = mb_strlen($username, 'UTF-8');
        if($strlen < 3) {
            return -2;
        }
        if($strlen > 15) {
            return -3;
        }
        $sql = "SELECT `uid` FROM `{$_W['db']['tablepre']}users` WHERE `username`='{$username}'";
        $row = $WDB->get_row($sql);
        if(!empty($row)) {
            return -4;
        } else {
            return 1;
        }
    }
    
    /**
     * 检查密码
     * @param string $password
     * @return int
     */
    public static function check_password($password) {
        $strlen = mb_strlen($password, 'UTF-8');
        if($strlen < 6) {
            return -7;
        }
        if($strlen > 15) {
            return -8;
        }
        return 1;
    }
    
    /**
     * 检查邮箱
     * @param string $email
     * @return int
     */
    public static function check_email($email) {
        global $_W, $WDB;
        if(strlen($email) < 6) {
            return -9;
        }
        if(!preg_match('/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/', $email)) {
            return -9;
        }
        $sql = "SELECT `uid` FROM `{$_W['db']['tablepre']}users` WHERE `email`='{$email}'";
        $row = $WDB->get_row($sql);
        if(!empty($row)) {
            return -10;
        } else {
            return 1;
        }
    }
    
    /**
     * 检查手机号码
     * @param string $mobile
     * @return int
     */
    public static function check_mobile($mobile) {
        global $_W, $WDB;
        if(strlen($mobile) != 11) {
            return -12;
        }
        if(!is_numeric($mobile)) {
            return -12;
        }
        $sql = "SELECT `uid` FROM `{$_W['db']['tablepre']}users` WHERE `mobile`='{$mobile}'";
        $row = $WDB->get_row($sql);
        if(!empty($row)) {
            return -13;
        } else {
            return 1;
        }
    }
    
    /**
     * 随机的6位散列盐
     * @return string
     */
    public static function salt() {
        return substr(uniqid(rand()), -6);
    }
    
    /**
     * 密码生成
     * @param string $password
     * @param string $salt
     * @return string
     */
    public static function create_password($password, $salt) {
        return md5(md5($password).$salt);
    }
    
    /**
     * 注册
     * @param string $username
     * @param string $username_again
     * @param string $password
     * @param string $password_again
     * @param string $email
     * @param string $email_again
     * @param int $roleid = 0
     * @param string $mobile = ''
     * @return int 如果大于0，说明注册成功，否则说明失败，然后返回指定的错误码
     */
    public static function register($username, $username_again, $password, $password_again, $email, $email_again, $roleid = 0, $mobile = '') {
        global $_W, $WDB;
        // Check username
        $result = self::check_username($username);
        if($result != 1) {
            return $result;
        }
        if($username != $username_again) {
            return -5;
        }
        // Check password
        $password = addslashes(trim(stripslashes($password)));
        if($password != $password_again) {
            return -6;
        }
        $result = self::check_password($password);
        if($result != 1) {
            return $result;
        }
        // Check email
        $result = self::check_email($email);
        if($result != 1) {
            return $result;
        }
        if($email != $email_again) {
            return -11;
        }
        // Check mobile
        if($mobile != '') {
            $result = self::check_mobile($mobile);
            if($result != 1) {
                return $result;
            }
        }
        // Password salt
        $salt = self::salt();
        // User information
        $password = self::create_password($password, $salt);
        $regip    = w_get_client_ip();
        // Insert DB
        $sql = "INSERT INTO `{$_W['db']['tablepre']}users`(`email`, `username`, `mobile`, `password`, `status`, `roleid`, `regtime`, `regip`, `lastlogintime`, `lastloginip`, `logincount`, `points`, `balances`, `salt`) VALUES ('{$email}', '{$username}', '{$mobile}', '{$password}', '1', '{$roleid}', '".W_TIMESTAMP."', '{$regip}', '0', '', '0', '0', '0.00', '{$salt}')";
        if($WDB->query($sql)) {
            return $WDB->insert_id;
        } else {
            return 0;
        }
    }
    
    /**
     * 登录.如果错误，这里限制15分钟锁定
     * @param string $username
     * @param string $password
     * @return array 
     */
    public static function login($username, $password) {
        global $_W, $WDB;
        // 判断用户账号类型
        $username_type = 'username'; // uid,username,email,mobile
        if(is_numeric($username)) {
            if(strlen($username) == 11) {
                $username_type = 'mobile';
            } else {
                $username_type = 'uid';
            }
        } else {
            if(preg_match('/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/', $username)) {
                $username_type = 'email';
            } else {
                $username_type = 'username';
            }
        }
        // 检查用户名输入安全
        if($username_type == 'username') {
            if(!preg_match('/^[a-zA-Z]+[a-zA-Z0-9_]+$/', $username)) {
                return array('status' => -14, 'timeleft' => 0, 'remaincount'=> $_W['lock_count']);
            }
        }
        // 变量申明
        //$lock_seconds = 900;
        //$lock_count   = 6;
        $ip = $_SERVER['REMOTE_ADDR'];
        //$ip = w_get_client_ip();
        // 检查ip是否被锁定
        $sql = "SELECT `ip`, `count`, `logintime` FROM `{$_W['db']['tablepre']}loginfailedip` WHERE `ip`='{$ip}'";
        $loginfailedip_row = $WDB->get_row($sql);
        if(!empty($loginfailedip_row)) {
            $interval = W_TIMESTAMP - $loginfailedip_row['logintime'];
            if($interval > $_W['lock_seconds']) {
                $loginfailedip_row['count'] = 0;
            } else {
                if($loginfailedip_row['count'] >= $_W['lock_count']) {
                    return array('status' => -15, 'timeleft' => $_W['lock_seconds'] - $interval, 'remaincount'=> 0);
                }
            }
        }
        // 检查用户是否被锁定
        $sql = "SELECT `uid`, `count`, `logintime` FROM `{$_W['db']['tablepre']}loginfailedaccount` WHERE `{$username_type}`='{$username}'";
        $loginfailedaccount_row = $WDB->get_row($sql);
        if(!empty($loginfailedaccount_row)) {
            $interval = W_TIMESTAMP - $loginfailedaccount_row['logintime'];
            if($interval > $_W['lock_seconds']) {
                $loginfailedaccount_row['count'] = 0;
            } else {
                if($loginfailedaccount_row['count'] >= $_W['lock_count']) {
                    return array('status' => -16, 'timeleft' => $_W['lock_seconds'] - $interval, 'remaincount'=> 0);
                }
            }
        }
        // 根据登录账号查出用户信息
        $sql = "SELECT `uid`, `email`, `username`, `mobile`, `password`, `roleid`, `points`, `balances`, `salt`, `realname` FROM `{$_W['db']['tablepre']}users` WHERE `{$username_type}`='{$username}'";
        $user_row = $WDB->get_row($sql);
        // 如果登录账号不对，记录登陆错误ip次数
        if(empty($user_row)) {
            $remaincount = 0;
            if(empty($loginfailedip_row)) {
                $remaincount = $_W['lock_count'] - 1;
                $sql = "INSERT INTO `{$_W['db']['tablepre']}loginfailedip`(`ip`, `count`, `logintime`) VALUES ('{$ip}', '1', '".W_TIMESTAMP."')";
                $WDB->query($sql);
            } else {
                $remaincount = $_W['lock_count'] - ($loginfailedip_row['count']+1);
                if($loginfailedip_row['count'] < $_W['lock_count']) {
                    $sql = "UPDATE `{$_W['db']['tablepre']}loginfailedip` SET `count`='".($loginfailedip_row['count']+1)."', `logintime`='".W_TIMESTAMP."' WHERE `ip`='{$loginfailedip_row['ip']}'";
                    $WDB->query($sql);
                }
            }
            if($remaincount == 0) {
                return array('status' => -15, 'timeleft' => $_W['lock_seconds'], 'remaincount'=> $remaincount);
            } else {
                return array('status' => -17, 'timeleft' => 0, 'remaincount'=> $remaincount);
            }
        }
        // 如果密码不对，记录登陆错误账号次数
        if($user_row['password'] != md5($password.$user_row['salt'])) {
            $remaincount = 0;
            if(empty($loginfailedaccount_row)) {
                $remaincount = $_W['lock_count'] - 1;
                $sql = "INSERT INTO `{$_W['db']['tablepre']}loginfailedaccount`(`uid`, `email`, `username`, `mobile`, `ip`, `count`, `logintime`) VALUES ('{$user_row['uid']}', '{$user_row['email']}', '{$user_row['username']}', '{$user_row['mobile']}', '{$ip}', '1', '".W_TIMESTAMP."')";
                $WDB->query($sql);
            } else {
                $remaincount = $_W['lock_count'] - ($loginfailedaccount_row['count']+1);
                if($loginfailedaccount_row['count'] < $_W['lock_count']) {
                    $sql = "UPDATE `{$_W['db']['tablepre']}loginfailedaccount` SET `ip`='{$ip}', `count`='".($loginfailedaccount_row['count']+1)."', `logintime`='".W_TIMESTAMP."' WHERE `uid`='{$loginfailedaccount_row['uid']}'";
                    $WDB->query($sql);
                }
            }
            if($remaincount == 0) {
                return array('status' => -16, 'timeleft' => $_W['lock_seconds'], 'remaincount'=> $remaincount);
            } else {
                return array('status' => -18, 'timeleft' => 0, 'remaincount'=> $remaincount);
            }
        }
        // 登陆成功，清除登录错误ip，清除登录错误账号
        if(!empty($loginfailedip_row)) {
            $sql = "DELETE FROM `{$_W['db']['tablepre']}loginfailedip` WHERE `ip`='{$ip}'";
            $WDB->query($sql);
        }
        if(!empty($loginfailedaccount_row)) {
            $sql = "UPDATE `{$_W['db']['tablepre']}loginfailedaccount` SET `count`='0' WHERE `uid`='{$user_row['uid']}'";
            $WDB->query($sql);
        }
        // 更新登录时间、更新登录ip、更新登录次数
        $sql = "UPDATE `{$_W['db']['tablepre']}users` SET `lastlogintime`='".W_TIMESTAMP."',`lastloginip`='{$ip}',`logincount`=`logincount`+1 WHERE `uid`='{$user_row['uid']}'";
        $WDB->query($sql);
        // 给 $_W['user'] 赋值
        $_W['user']['uid']      = $user_row['uid'];
        $_W['user']['username'] = $user_row['username'];
        $_W['user']['roleid']   = $user_row['roleid'];
        $_W['user']['points']   = $user_row['points'];
        $_W['user']['balances'] = $user_row['balances'];
		$_W['user']['realname'] = $user_row['realname'];
        // 删除用完了的变量
        unset($loginfailedip_row, $loginfailedaccount_row, $user_row);
        // 返回成功结果
        return array('status' => 1, 'timeleft' => 0, 'remaincount'=> $_W['lock_count']);
    }
    
    /**
     * session登出
     */
    public static function session_logout($sid) {
        global $_W, $WDB;
        $sql = "DELETE FROM `{$_W['db']['tablepre']}sessions` WHERE `sid`='{$sid}'";
        $WDB->query($sql);
    }
    
    /**
     * accesstoken登出
     */
    public static function accesstoken_logout($accesstoken) {
        global $_W, $WDB;
        $sql = "DELETE FROM `{$_W['db']['tablepre']}accesstokens` WHERE `accesstoken`='{$accesstoken}'";
        $WDB->query($sql);
    }
    
    /**
     * 检查访问权限
     * @return boolean
     */
    public static function check_access_permission() {
        global $_W, $WDB;
        if($_W['user']['uid'] == 0) {
            if(in_array($_W['permission_flag'], $_W['guest_permission'])) {
                return true;
            } else {
                return false;
            }
        } else {
            if($_W['user']['roleid'] == 1) {
                return true;
            }
            if(in_array($_W['permission_flag'], $_W['guest_permission'])) {
                return true;
            }
            $sql = "SELECT `permissions` FROM `{$_W['db']['tablepre']}roles` WHERE `roleid`='{$_W['user']['roleid']}'";
            $row = $WDB->get_row($sql);
            if(empty($row)) {
                return false;
            }
            $permissions = explode(',', $row['permissions']);
            if(in_array($_W['permission_flag'], $permissions)) {
                return true;
            } else {
                return false;
            }
        }
    }
    
    /**
     * 获取用户菜单
     * @return array
     */
    public static function get_user_menus() {
        global $_W, $WDB, $_menus;
        // 如果没有当前应用菜单，直接返回空数组
        if(!isset($_menus[W_APP])) {
            return array();
        }
        // 获取权限列表
		$cur_app_user_permissions = array();
        if($_W['user']['roleid'] == 1) {
			// 如果是超级管理员，直接返回当前应用的所有菜单
			foreach($_menus as $k => $v) {
				if(strpos($k, W_APP) === 0) {
					$cur_app_user_permissions[] = $k;
				}
			}
        } else {
			// 否则，从数据库里获取当前用户角色的所有权限
			$sql = "SELECT `roleid`, `rolename`, `permissions` FROM `{$_W['db']['tablepre']}roles` WHERE `roleid`='{$_W['user']['roleid']}'";
			$role_row = $WDB->get_row($sql);
			if(empty($role_row)) {
				return array();
			}
			$permissions = explode(',', $role_row['permissions']);
			// 提取当前应用用户权限
			foreach($permissions as $k => $v) {
				if(strpos($v, W_APP) === 0) {
					$cur_app_user_permissions[] = $v;
				}
			}
			unset($permissions);
		}
        // 提取“APP层”
		$menus                 = array();
        $menus[W_APP]          = $_menus[W_APP];
        $menus[W_APP]['url']   = W_SCRIPT;
        $menus[W_APP]['child'] = array();
        // 提取“module层”和“action层”，并创建url字段
        $modules = array();
        $actions = array();
        foreach($cur_app_user_permissions as $v) {
            if(isset($_menus[$v])) {
                $arr = explode('/', $v);
                switch(count($arr)) {
                    case 2:
                        $modules[$v] = array(
                            'name'    => $_menus[$v]['name'],
                            'display' => $_menus[$v]['display'],
                            'url'     => W_SCRIPT.'?m='.$arr[1],
                            'child'   => array()
                        );
                        break;
                    case 3:
                        if($_menus[$v]['display'] == 1) {
                            $actions[$v] = array(
                                'name'    => $_menus[$v]['name'],
                                'display' => $_menus[$v]['display'],
                                'url'     => W_SCRIPT.'?m='.$arr[1].'&a='.$arr[2]
                            );
                        }
                        break;
                }
            }
        }
        // 让“module层”从“action层”中找到自己的子节点
        foreach($modules as $mk => $mv) {
            foreach($actions as $ak => $av) {
                if(strpos($ak, $mk) === 0) {
                    $modules[$mk]['child'][$ak] = $av;
                }
            }
        }
        // 把“module层”挂在“APP层”的子节点下面
        $menus[W_APP]['child'] = $modules;
        // 把用完的变量删除
        unset($role_row, $cur_app_user_permissions, $modules, $actions);
        // 返回最终结果
        return $menus[W_APP]['child'];
    }
}