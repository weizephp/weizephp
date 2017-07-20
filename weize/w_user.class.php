<?php
/**
 * +----------------------------------------------------------------------
 * | WeizePHP framework
 * +----------------------------------------------------------------------
 * | Copyright (c) 2013-2113 http://weizephp.75hh.com/ All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: Ze Wei <E-mail:weizesw@gmail.com> <QQ:310472156>
 * +----------------------------------------------------------------------
 * | Description: 用户管理类库
 * +----------------------------------------------------------------------
 */

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


/**
使用示例:
---------------------------------
$wuser = new w_user();
---------------------------------
*/


class w_user {
    
    public $sid         = '';
    public $session     = '';
    
    public $aid         = '';
    public $accesstoken = '';
    
    public $ip          = '';
    public $uid         = 0;
    public $lastvisit   = 0;
    public $formtoken   = '';
    
    public $username    = '';
    public $roleid      = 0;
    public $point       = 0;
    public $balance     = '0.00';
    public $realname    = '';
    public $nickname    = '';
    
    public $lastvisit_old = 0;
    
    /* ------------------------------------------------- */
    
    /**
     * session 初始化
     * @return int
     */
    protected function init_session() {
        global $wconfig, $m, $wdb;
        
        // 定义签名变量
        $salt = '';
        $sign = '';
        
        // 定义cookie的key
        $sessionkey = $wconfig['cookie']['prefix'] . 'session';
        $saltkey    = $wconfig['cookie']['prefix'] . 'salt';
        
        // 读取客户端cookie
        $client_session = isset($_COOKIE[$sessionkey]) ? trim($_COOKIE[$sessionkey]) : '';
        $client_salt    = isset($_COOKIE[$saltkey])    ? trim($_COOKIE[$saltkey])    : '';
        
        // 如果$session字符长度为50时，获取$client_sid,$client_time,$client_sign值，否则定义空值
        if( strlen($client_session) == 50 ) {
            $client_sid  = substr($client_session, 0, 8);
            $client_time = intval(substr($client_session, 8, 10));
            $client_sign = substr($client_session, 18);
        } else {
            $client_sid  = w_random(8);
            $client_time = time();
            $client_sign = '';
            $client_salt = w_random(6);
        }
        
        // 给对象变量赋值
        $this->ip = $_SERVER['REMOTE_ADDR'];
        
        // 校验签名 cookie
        $verify_sign = w_sign( array($client_sid, $client_time, $client_salt, 'session', $wconfig['authkey']) );
        if( $verify_sign === $client_sign ) {
            $sql = "SELECT * FROM `{$wconfig['db']['tablepre']}session` WHERE `sid`='{$client_sid}'";
            $row = $wdb->get_row($sql);
            if( !empty($row['lastvisit']) ) {
                $this->lastvisit = $row['lastvisit'];
            }
            $session_expire = !empty($row) && ($row['uid'] > 0) ? $wconfig['session']['expire'] : 60;
            if( !empty($row) && (($row['lastvisit'] + $session_expire) > W_TIMESTAMP) ) {
                // 更新 session , 并发送 cookie
                $this->sid       = $row['sid'];
                $this->uid       = $row['uid'];
                $this->formtoken = $row['formtoken'];
                $sql = "UPDATE `{$wconfig['db']['tablepre']}session` SET `lastvisit`='". W_TIMESTAMP ."' WHERE `sid`='{$this->sid}'";
                $wdb->query($sql);
            } else {
                // 删除旧 session
                $lastvisit_expire = W_TIMESTAMP - $wconfig['session']['expire'];
                $guest_expire     = W_TIMESTAMP - 60;
                $sql = "DELETE FROM `{$wconfig['db']['tablepre']}session` WHERE `sid`='{$client_sid}' OR `lastvisit`<'{$lastvisit_expire}' OR (uid='0' AND `lastvisit`<'{$guest_expire}')";
                $wdb->query($sql);
                // 创建新 session
                $this->sid       = w_random(8);
                $this->uid       = 0;
                $salt            = w_random(6);
                $sign            = w_sign( array($this->sid, W_TIMESTAMP, $salt, 'session', $wconfig['authkey']) );
                $this->formtoken = w_random(6);
                $this->session   = $this->sid . W_TIMESTAMP . $sign;
                $sql = "REPLACE INTO `{$wconfig['db']['tablepre']}session`(`sid`, `ip`, `uid`, `lastvisit`, `formtoken`) VALUES ('{$this->sid}', '{$this->ip}', '{$this->uid}', '". W_TIMESTAMP ."', '{$this->formtoken}')";
                $wdb->query($sql);
                // 发送 cookie 给客户端
                w_setcookie($sessionkey, $this->session, $wconfig['session']['cookie_lifetime'], true);
                w_setcookie($saltkey, $salt, $wconfig['session']['cookie_lifetime'], true);
            }
            return 1;
        } else {
            // 给客户端发送 cookie
            $this->sid       = w_random(8);
            $salt            = w_random(6);
            $sign            = w_sign( array($this->sid, W_TIMESTAMP, $salt, 'session', $wconfig['authkey']) );
            $this->session   = $this->sid . W_TIMESTAMP . $sign;
            $this->lastvisit = W_TIMESTAMP; // 上次访问时间没有，就取当前时间
            w_setcookie($sessionkey, $this->session, $wconfig['session']['cookie_lifetime'], true);
            w_setcookie($saltkey, $salt, $wconfig['session']['cookie_lifetime'], true);
            return 0;
        }
    }
    
    /**
     * accesstoken 初始化
     * @return int
     */
    protected function init_accesstoken() {
        global $wconfig, $wdb;
        
        if( isset($_GET['accesstoken']) ) {
            $accesstoken = trim($_GET['accesstoken']);
        } else if( isset($_POST['accesstoken']) ) {
            $accesstoken = trim($_POST['accesstoken']);
        } else {
            $accesstoken = '';
        }
        
        if( strlen($accesstoken) != 56 ) {
            unset($accesstoken);
            return 0;
        }
        
        $client_aid  = substr($accesstoken, 0, 8);
        $client_time = intval(substr($accesstoken, 8, 10));
        $client_salt = substr($accesstoken, 18, 6);
        $client_sign = substr($accesstoken, 24);
        
        if( (W_TIMESTAMP - $client_time) > $wconfig['accesstoken']['expire'] ) {
            $client_time = W_TIMESTAMP;
        }
        
        $this->ip = $_SERVER['REMOTE_ADDR'];
        
        $verify_sign = w_sign( array($client_aid, $client_time, $client_salt, 'accesstoken', $wconfig['authkey']) );
        if( $verify_sign !== $client_sign ) {
            unset($accesstoken, $client_aid, $client_time, $client_salt, $client_sign, $verify_sign);
            return 0;
        }
        
        $sql = "SELECT * FROM `{$wconfig['db']['tablepre']}accesstoken` WHERE `aid`='{$client_aid}'";
        $row = $wdb->get_row($sql);
        
        if( !empty($row['lastvisit']) ) {
            $this->lastvisit = $row['lastvisit'];
        }
        
        if( !empty($row) && ($row['expire'] > W_TIMESTAMP) ) {
            $this->aid         = $row['aid'];
            $this->accesstoken = $accesstoken;
            $this->uid         = $row['uid'];
            $sql = "UPDATE `{$wconfig['db']['tablepre']}accesstoken` SET `lastvisit`='". W_TIMESTAMP ."' WHERE `aid`='{$this->aid}'";
            $wdb->query($sql);
        } else {
            $sql = "DELETE FROM `{$wconfig['db']['tablepre']}accesstoken` WHERE `expire`<'" . W_TIMESTAMP . "'";
            $wdb->query($sql);
        }
        
        unset($accesstoken, $client_aid, $client_time, $client_salt, $client_sign, $verify_sign, $sql, $row);
        
        return 1;
    }
    
    /**
     * accesstoken 创建
     * @return string
     */
    public function create_accesstoken() {
        global $wconfig, $wdb;
        $this->aid         = w_random(8);
        $salt              = w_random(6);
        $sign              = w_sign( array($this->aid, W_TIMESTAMP, $salt, 'accesstoken', $wconfig['authkey']) );
        $expire            = W_TIMESTAMP + $wconfig['accesstoken']['expire'];
        $this->ip          = $_SERVER['REMOTE_ADDR'];
        $this->lastvisit   = W_TIMESTAMP; // 上次访问时间没有，就取当前时间
        $this->accesstoken = $this->aid . W_TIMESTAMP . $salt . $sign;
        $sql = "REPLACE INTO `{$wconfig['db']['tablepre']}accesstoken`(`aid`, `expire`, `ip`, `uid`, `lastvisit`) VALUES ('{$this->aid}', '{$expire}', '{$this->ip}', '{$this->uid}', '". W_TIMESTAMP ."')";
        $wdb->query($sql);
        unset($expire, $sql);
        return $this->accesstoken;
    }
    
    /**
     * user 类初始化
     * 备注：如果想做访问统计，需要自己加访问统计表来做
     */
    public function init() {
        global $wconfig, $m, $wdb;
        // 除了验证码之外，其他的要检查 accesstoken 还是 session 模式访问
        if( W_APPNAME == 'general' && $m == 'captcha' ) {
            // 啥也不操作...
        } else {
            if( (W_APPNAME == 'appapi') || isset($_GET['accesstoken']) || isset($_POST['accesstoken']) ) {
                $this->init_accesstoken();
            } else {
                $this->init_session();
            }
        }
        // 获取用户信息
        if( $this->uid > 0 ) {
            $sql = "SELECT `uid`, `username`, `roleid`, `point`, `balance`, `realname` FROM `{$wconfig['db']['tablepre']}user` WHERE `uid`='{$this->uid}'";
            $user = $wdb->get_row($sql);
            if( !empty($user) ) {
                $this->uid      = $user['uid'];
                $this->username = $user['username'];
                $this->roleid   = $user['roleid'];
                $this->point    = $user['point'];
                $this->balance  = $user['balance'];
                $this->realname = $user['realname'];
            }
            unset($sql, $user);
        }
    }
    
    /* ------------------------------------------------- */
    
    /**
     * 检查用户名
     * @param string $username
     * @return int
     */
    public function check_username($username) {
        global $wconfig, $wdb;
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
        $sql = "SELECT `uid` FROM `{$wconfig['db']['tablepre']}user` WHERE `username`='{$username}'";
        $row = $wdb->get_row($sql);
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
    public function check_password($password) {
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
    public function check_email($email) {
        global $wconfig, $wdb;
        if(strlen($email) < 6) {
            return -9;
        }
        if(!preg_match('/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/', $email)) {
            return -9;
        }
        $sql = "SELECT `uid` FROM `{$wconfig['db']['tablepre']}user` WHERE `email`='{$email}'";
        $row = $wdb->get_row($sql);
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
    public function check_mobile($mobile) {
        global $wconfig, $wdb;
        if(strlen($mobile) != 11) {
            return -12;
        }
        if(!is_numeric($mobile)) {
            return -12;
        }
        $sql = "SELECT `uid` FROM `{$wconfig['db']['tablepre']}user` WHERE `mobile`='{$mobile}'";
        $row = $wdb->get_row($sql);
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
    public function salt() {
        return substr(uniqid(rand()), -6);
    }
    
    /**
     * 密码生成
     * @param string $password
     * @param string $salt
     * @return string
     */
    public function create_password($password, $salt) {
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
    public function register($username, $username_again, $password, $password_again, $email, $email_again, $roleid = 0, $mobile = '') {
        global $wconfig, $wdb;
        // Check username 检查用户名
        $result = $this->check_username($username);
        if($result != 1) {
            return $result;
        }
        if($username != $username_again) {
            return -5;
        }
        // Check password 检查密码
        $password = addslashes(trim(stripslashes($password)));
        if($password != $password_again) {
            return -6;
        }
        $result = $this->check_password($password);
        if($result != 1) {
            return $result;
        }
        // Check email 检查邮箱
        $result = $this->check_email($email);
        if($result != 1) {
            return $result;
        }
        if($email != $email_again) {
            return -11;
        }
        // Check mobile 检查手机号码
        if($mobile != '') {
            $result = $this->check_mobile($mobile);
            if($result != 1) {
                return $result;
            }
        }
        // Password salt 密码盐
        $salt = $this->salt();
        // User information 用户信息
        $password = $this->create_password($password, $salt);
        $regip    = w_get_client_ip();
        // Insert DB 插入数据库
        $sql = "INSERT INTO `{$wconfig['db']['tablepre']}user` (`email`, `username`, `mobile`, `password`, `status`, `roleid`, `regtime`, `regip`, `lastlogintime`, `lastloginip`, `logincount`, `point`, `balance`, `salt`) VALUES ('{$email}', '{$username}', '{$mobile}', '{$password}', '1', '{$roleid}', '".W_TIMESTAMP."', '{$regip}', '0', '', '0', '0', '0.00', '{$salt}')";
        if($wdb->query($sql)) {
            return $wdb->insert_id;
        } else {
            return 0;
        }
    }
    
    /**
     * 登录.如果登陆错误，这里限制15分钟锁定
     * @param string $username
     * @param string $password
     * @param string $mode
     * @return array 
     */
    public function login($username, $password, $mode = "session") {
        global $wconfig, $wdb;
        // 定义返回数据结构
        $data = array(
            'status'      => -14,
            'timeleft'    => 0,
            'remaincount' => 0,
            'accesstoken' => ''
        );
        // 判断用户账号类型
        $username_type = 'username'; // uid,username,email,mobile
        if( is_numeric($username) && (strlen($username) == 11) ) {
            $username_type = 'mobile';
        } else if( is_numeric($username) ) {
            $username_type = 'uid';
        } else if( preg_match('/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/', $username) == 1 ) {
            $username_type = 'email';
        } else {
            $username_type = 'username';
        }
        // 检查用户名输入安全
        if( ($username_type == 'username') && !preg_match('/^[a-zA-Z]+[a-zA-Z0-9_]+$/', $username) ) {
            $data = array(
                'status'      => -14,
                'timeleft'    => 0,
                'remaincount' => $wconfig['login_lock_number'],
                'accesstoken' => ''
            );
            return $data;
        }
        // 获取用户IP
        $ip = $_SERVER['REMOTE_ADDR'];
        //$ip = w_get_client_ip();
        // 定义“IP锁”、“用户名锁”数组变量
        $ip_lock_arr = $username_lock_arr = array(
            'ipusername' => '',
            'logintime' => 0,
            'count' => 0
        );
        // 从数据表里查出“IP锁”、“用户名锁”数据
        $sql = "SELECT * FROM `{$wconfig['db']['tablepre']}loginfailed` WHERE `ipusername`='{$ip}' OR `ipusername`='{$username}'";
        $result = $wdb->query($sql);
        if( $result !== FALSE ) {
            while( $row = $result->fetch_assoc() ) {
                if( $row['ipusername'] === $username ) {
                    $ip_lock_arr = $row;
                } elseif( $row['ipusername'] === $ip ) {
                    $username_lock_arr = $row;
                }
            }
        }
        // 剩余时间
        $ip_lock_interval       = W_TIMESTAMP - $ip_lock_arr['logintime'];
        $username_lock_interval = W_TIMESTAMP - $username_lock_arr['logintime'];
        // 检查IP是否被锁定
        if( ($ip_lock_interval > $wconfig['login_lock_second']) && ($ip_lock_arr['count'] >= $wconfig['login_lock_number']) ) {
            $data = array(
                'status'      => -15,
                'timeleft'    => $wconfig['login_lock_second'] - $ip_lock_interval,
                'remaincount' => 0,
                'accesstoken' => ''
            );
            return $data;
        }
        // 检查用户名是否被锁定
        if( ($username_lock_interval > $wconfig['login_lock_second']) && ($username_lock_arr['count'] >= $wconfig['login_lock_number']) ) {
            $data = array(
                'status'      => -16,
                'timeleft'    => $wconfig['login_lock_second'] - $username_lock_interval,
                'remaincount' => 0,
                'accesstoken' => ''
            );
            return $data;
        }
        // 根据用户名查出用户信息
        $sql = "SELECT * FROM `{$wconfig['db']['tablepre']}user` WHERE `{$username_type}`='{$username}'";
        $user_row = $wdb->get_row($sql);
        // 如果登录账号不对，记录登陆错误IP次数
        if( empty($user_row) ) {
            $remaincount = 0;
            if( $ip_lock_arr['ipusername'] === '' ) {
                $remaincount = $wconfig['login_lock_number'] - 1;
                $sql = "REPLACE INTO `{$wconfig['db']['tablepre']}loginfailed` (`ipusername`, `logintime`, `count`) VALUES ('{$ip}', '". W_TIMESTAMP ."', '1')";
                $wdb->query($sql);
            } else {
                $remaincount = $wconfig['login_lock_number'] - ($ip_lock_arr['count'] + 1);
                $sql = "UPDATE `{$wconfig['db']['tablepre']}loginfailed` SET `logintime`='". W_TIMESTAMP ."', `count`=`count`+1 WHERE `ipusername`='{$ip}'";
                $wdb->query($sql);
            }
            if( $remaincount == 0 ) {
                $data = array(
                    'status'      => -15,
                    'timeleft'    => $wconfig['login_lock_second'],
                    'remaincount' => 0,
                    'accesstoken' => ''
                );
                return $data;
            } else {
                $data = array(
                    'status'      => -17,
                    'timeleft'    => 0,
                    'remaincount' => $remaincount,
                    'accesstoken' => ''
                );
                return $data;
            }
        }
        // 如果密码不对，记录登录错误IP和账号次数
        if( $user_row['password'] != md5($password.$user_row['salt']) ) {
            $remaincount = 0;
            if( $username_lock_arr['ipusername'] === '' ) {
                $remaincount = $wconfig['login_lock_number'] - 1;
                $sql = "REPLACE INTO `{$wconfig['db']['tablepre']}loginfailed` (`ipusername`, `logintime`, `count`) VALUES ('{$username}', '". W_TIMESTAMP ."', '1'), ('{$ip}', '". W_TIMESTAMP ."', '1')";
                $wdb->query($sql);
            } else {
                $remaincount = $wconfig['login_lock_number'] - ($username_lock_arr['count'] + 1);
                $sql = "UPDATE `{$wconfig['db']['tablepre']}loginfailed` SET `logintime`='". W_TIMESTAMP ."', `count`=`count`+1 WHERE `ipusername`='{$username}' OR `ipusername`='{$ip}'";
                $wdb->query($sql);
            }
            if( $remaincount == 0 ) {
                $data = array(
                    'status'      => -16,
                    'timeleft'    => $wconfig['login_lock_second'],
                    'remaincount' => 0,
                    'accesstoken' => ''
                );
                return $data;
            } else {
                $data = array(
                    'status'      => -18,
                    'timeleft'    => 0,
                    'remaincount' => $remaincount,
                    'accesstoken' => ''
                );
                return $data;
            }
        }
        // 登陆成功，清除过期的“IP锁”和“账户锁”
        $expire_time = W_TIMESTAMP - ($wconfig['login_lock_second'] + 1);
        $wdb->query("DELETE FROM `{$wconfig['db']['tablepre']}loginfailed` WHERE `logintime`<'{$expire_time}'");
        // 更新登录时间、更新登录IP、更新登录次数
        $sql = "UPDATE `{$wconfig['db']['tablepre']}user` SET `lastlogintime`='". W_TIMESTAMP ."', `lastloginip`='{$ip}', `logincount`=`logincount`+1 WHERE `uid`='{$user_row['uid']}'";
        $wdb->query($sql);
        // 赋值
        $this->uid      = $user_row['uid'];
        $this->username = $user_row['username'];
        $this->roleid   = $user_row['roleid'];
        $this->point    = $user_row['point'];
        $this->balance  = $user_row['balance'];
        $this->realname = $user_row['realname'];
        $this->nickname = $user_row['nickname'];
        // 删除用完了的变量
        unset($username_type, $ip_lock_arr, $username_lock_arr, $ip_lock_interval, $username_lock_interval, $user_row);
        // 返回成功结果
        if( $mode == "accesstoken" ) {
            $data = array(
                'status'      => 1,
                'timeleft'    => 0,
                'remaincount' => $wconfig['login_lock_number'],
                'accesstoken' => $this->create_accesstoken()
            );
        } else {
            $sql = "UPDATE `{$wconfig['db']['tablepre']}session` SET `uid`='{$this->uid}' WHERE `sid`='{$this->sid}'";
            $wdb->query($sql);
            $data = array(
                'status'      => 1,
                'timeleft'    => 0,
                'remaincount' => $wconfig['login_lock_number'],
                'accesstoken' => ''
            );
        }
        return $data;
    }
    
    /**
     * 登出
     */
    public function logout() {
        global $wconfig, $wdb;
        if( ($this->uid > 0) && ($this->accesstoken != '') ) {
            $wdb->query("DELETE FROM `{$wconfig['db']['tablepre']}accesstoken` WHERE `accesstoken`='{$this->accesstoken}'");
        }
        if( ($this->uid > 0) && ($this->sid != '') ) {
            $wdb->query("DELETE FROM `{$wconfig['db']['tablepre']}session` WHERE `sid`='{$this->sid}'");
        }
    }
    
    /* ------------------------------------------------- */
    
    /**
     * 检查访问权限
     * @return boolean
     */
    public function check_access_permission() {
        global $wconfig, $wdb, $m, $a;
        // 如果权限关闭，直接返回TRUE
        if( W_PERMISSION == 0 ) {
            return TRUE;
        }
        // 定义权限标记
        $flag = W_APPNAME . '/' . $m . '/' . $a;
        // 游客权限判断
        if( in_array($flag, $wconfig['permission_guest']) ) {
            return TRUE;
        }
        // 如果是超级管理员，啥都能做
        if( $this->roleid == 1 ) {
            return TRUE;
        }
        // 获取角色权限
        $sql = "SELECT `permission` FROM `{$wconfig['db']['tablepre']}role` WHERE `roleid`='{$this->roleid}'";
        $row = $wdb->get_row($sql);
        if( empty($row) ) {
            return FALSE;
        }
        // 分割角色权限
        $permission = explode(',', $row['permission']);
        // 检查是否拥有权限
        if( in_array($flag, $permission) ) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    /**
     * 获取“当前应用”的“用户菜单”
     * @return array
     */
    public function get_curapp_user_menu() {
        global $wconfig, $wdb, $wmenu;
        // 如果没有当前应用的菜单，直接返回空数组
        if( !isset($wmenu[W_APPNAME]) ) {
            return array();
        }
        // 获得当前app的module和action菜单
        $curapp_menu = array();
        foreach($wmenu as $key => $val) {
            if( (strpos($key, W_APPNAME) === 0) && (substr_count($key, '/') > 0) ) {
                $curapp_menu[$key] = $val;
            }
        }
        // 定义权限变量
        $permission = array();
        // 如果是超级管理员，获取当前应用的所有权限，否则从数据库查询对应的角色权限
        if( $this->roleid == 1 ) {
            foreach($curapp_menu as $key => $val) {
                $permission[] = $key;
            }
        } else {
            // 获取用户的角色权限
            $sql = "SELECT * FROM `{$wconfig['db']['tablepre']}role` WHERE `roleid`='{$this->roleid}'";
            $row = $wdb->get_row($sql);
            // 如果没有该角色，返回空数组
            if( empty($row) ) {
                return array();
            }
            // 分割“权限字符串”为数组
            $permission = explode(',', $row['permission']);
        }
        // 定义当前app菜单module层变量
        $mod_menu = array();
        // 定义当前app菜单module层下的action层变量
        $act_menu = array();
        // 遍历当前app菜单，获取对应的权限菜单
        foreach($curapp_menu as $key => $val) {
            $keyarr = explode('/', $key);
            $count  = substr_count($key, '/');
            if( ($count == 1) && in_array($key, $permission) ) {
                $val['url']  = '?m=' . $keyarr[1];
                $val['flag'] = $key;
                $mod_menu[] = $val;
            }
            if( ($count == 2) && in_array($key, $permission) ) {
                $val['url']  = '?m=' . $keyarr[1] . '&a=' . $keyarr[2];
                $val['flag'] = $key;
                $act_menu[] = $val;
            }
        }
        unset($curapp_menu);
        // 把action层压入module层
        foreach($mod_menu as $mkey => $mval) {
            foreach($act_menu as $akey => $aval) {
                if( strpos($aval['url'], $mval['url']) === 0 ) {
                    $mod_menu[$mkey]['children'][] = $aval;
                }
            }
        }
        unset($act_menu);
        // 返回菜单
        return $mod_menu;
    }
    
    /**
     * 记录用户操作日志
     * @param string $loginfo 日志信息（限255个字）
     */
    public function actionlog($loginfo) {
        if( $this->uid > 0 ) {
            $ip  = w_get_client_ip();
            if( W_APPNAME == "admin" ) {
                $log_table = $GLOBALS['wconfig']['db']['tablepre'] . "adminlog";
            } else {
                $log_table = $GLOBALS['wconfig']['db']['tablepre'] . "actionlog";
            }
            $sql = "INSERT INTO `{$log_table}` (`username`, `logtime`, `ip`, `loginfo`) VALUES ('{$this->username}', '".W_TIMESTAMP."', '{$ip}', '{$loginfo}')";
            $GLOBALS['wdb']->query($sql);
        }
    }
    
}
