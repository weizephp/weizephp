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

// 载入全局文件
include 'global.inc.php';

if(!isset($_POST['dbhost'])) {
    
    /**
     * 设置 - 页面
     */
    
    // 文件、文件夹读写权限检查
    $result = array();
    foreach($checking_dirs as $v) {
        $path = '../'.$v;
        if(!file_exists($path) || !is_writable($path)) {
            $result[] = false;
        }
    }
    if(!empty($result)) {
        w_install_error('目录、文件读写权限检测不通过，请设置好之后再安装！');
    }
    
    // 载入模板
    include 'template/setting.html.php';
    
} else {
    
    /**
     * 设置 - 导入SQL/保存配置
     */
    
    // 设置脚本最大执行时间。这里设置为0（零），表示没有时间方面的限制。
    set_time_limit(0);
    
    /* ---------------------------------------------------------------- */
    
    // 接收 POST 数据
    $dbhost         = isset($_POST['dbhost']) ? trim($_POST['dbhost']) : '';
    $dbport         = isset($_POST['dbport']) ? trim($_POST['dbport']) : '';
    $dbuser         = isset($_POST['dbuser']) ? trim($_POST['dbuser']) : '';
    $dbpw           = isset($_POST['dbpw']) ? trim($_POST['dbpw']) : '';
    $dbname         = isset($_POST['dbname']) ? trim($_POST['dbname']) : '';
    $tablepre       = isset($_POST['tablepre']) ? trim($_POST['tablepre']) : '';
    
    $username       = isset($_POST['username']) ? trim($_POST['username']) : '';
    $username_again = isset($_POST['username_again']) ? trim($_POST['username_again']) : '';
    $password       = isset($_POST['password']) ? trim($_POST['password']) : '';
    $password_again = isset($_POST['password_again']) ? trim($_POST['password_again']) : '';
    $email          = isset($_POST['email']) ? trim($_POST['email']) : '';
    $email_again    = isset($_POST['email_again']) ? trim($_POST['email_again']) : '';
    
    // 检查用户输入的数据库信息
    if(empty($dbhost)) {
        w_install_error('数据库服务器地址不能为空！');
    }
    if(empty($dbport)) {
        w_install_error('数据库端口不能为空！');
    }
    if(empty($dbuser)) {
        w_install_error('数据库账户不能为空！');
    }
    if(empty($dbname)) {
        w_install_error('数据库名不能为空！');
    }
    if(empty($tablepre)) {
        w_install_error('数据表前缀不能为空！');
    }
    
    // 检查用户输入信息是否正确
    if(empty($username)) {
        w_install_error('管理员账号不能为空！');
    }
    if(preg_match('/^[a-zA-Z]+[a-zA-Z0-9_]+$/', $username) == 0) {
        w_install_error('用户名只允许使用字母、数字、下划线，且必须需以字母开头！');
    }
    if($username != $username_again) {
        w_install_error('两次输入的管理员账号不一致！');
    }
    if(empty($password)) {
        w_install_error('管理员密码不能为空！');
    }
    if($password != $password_again) {
        w_install_error('两次输入的管理员密码不一致！');
    }
    if(!preg_match('/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/', $email)) {
        w_install_error('邮箱格式有误！');
    }
    if($email != $email_again) {
        w_install_error('两次输入的管理员邮箱不一致！');
    }
    
    /* ---------------------------------------------------------------- */
    
    // 载入 db_install_mysqli 类库
    include 'db_install_mysqli.class.php';
    
    // 连接MySQL
    $DB = new db_install_mysqli($dbhost, $dbuser, $dbpw);
    
    if( $DB->connect_error ) {
        //die( 'Connect Error (' . $DB->connect_errno . ') ' . $DB->connect_error );
        w_install_error('无法连接数据库服务器，请检查数据库信息是否正确！');
    }
    if( !$DB->set_charset(DB_CHARSET) ) {
        //die( "Error loading character set {$dbcharset}: {$DB->error}" );
        w_install_error('数据库字符集设置错误！');
    }
    
    // 选择用于数据库查询的默认数据库
    $result = $DB->select_db($dbname);
    if( !$result ) {
        if( $DB->db_create_db($dbname) ) {
            // 重新选择数据库
            $DB->select_db($dbname);
        } else {
            // 不能创建数据库，请手动创建
            //exit('Can\'t create '. $dbname .'!');
            w_install_error('权限不够，请手动创建数据库！');
        }
    }
    
    /* ---------------------------------------------------------------- */
    
    // 安装SQL数据思路：
    // 将文件作为一个数组返回。数组中的每个单元都是文件中相应的一行，包括换行符在内。如果失败 file() 返回 FALSE。
    
    // 读取SQL文件数据
    $sql_file = file(SQL_FILE);
    // 组合文件内容
    $sql = implode('', $sql_file);
    // 删除SQL行注释，行注释不匹配换行符
    $sql = preg_replace('/^\s*(?:--|#).*/m', '', $sql);
    // 删除SQL块注释，匹配换行符，且为非贪婪匹配
    $sql = preg_replace('/^\s*\/\*.*?\*\/\;/ms', '', $sql);
    // 删除SQL串首尾的空白符
    $sql = trim($sql);
    
    // 替换表前缀
    if(SOURCE_PREFIX != $tablepre) {
        $keywords = 'CREATE\s+TABLE(?:\s+IF\s+NOT\s+EXISTS)?|' .
            'DROP\s+TABLE(?:\s+IF\s+EXISTS)?|' .
            'ALTER\s+TABLE|' .
            'UPDATE|' .
            'REPLACE\s+INTO|' .
            'DELETE\s+FROM|' .
            'INSERT\s+INTO';
        
        $pattern     = '/(' . $keywords . ')(\s*)`?' . SOURCE_PREFIX . '(\w+)`?(\s*)/i';
        $replacement = '\1\2`' . $tablepre . '\3`\4';
        $sql         = preg_replace($pattern, $replacement, $sql);
        
        $pattern     = '/(UPDATE.*?WHERE)(\s*)`?' . SOURCE_PREFIX . '(\w+)`?(\s*\.)/i';
        $replacement = '\1\2`' . $tablepre . '\3`\4';
        $sql         = preg_replace($pattern, $replacement, $sql);
    }
    
    // 解析查询项
    $sql         = str_replace("\r", '', $sql);
    $query_items = explode(";\n", $sql);
    unset($sql_file, $sql);
    
    // 写入数据库
    $error_arr = array();
    foreach($query_items as $v) {
        $v = trim($v);
        if(!empty($v)) {
            $DB->query($v);
        }
    }
    
    // 创建管理员
    $salt     = substr(uniqid(rand()), -6);
    $password = md5(md5($password).$salt);
    $sql = "INSERT INTO `{$tablepre}users`(`email`, `username`, `mobile`, `password`, `status`, `roleid`, `regtime`, `regip`, `lastlogintime`, `lastloginip`, `logincount`, `points`, `balances`, `salt`) VALUES ('{$email}', '{$username}', '0', '{$password}', '1', '1', '".INSTALL_TIMESTAMP."', '{$_SERVER['REMOTE_ADDR']}', '0', '', '0', '0', '0.00', '{$salt}')";
    $DB->query($sql);
    
    // 关闭数据库
    $DB->close();
    
    /* ---------------------------------------------------------------- */
    
    // 配置文件变量
    $cfg_filename = '../config.inc.php';
    
    // 获取配置文件
    $cfg_content = file_get_contents($cfg_filename);
    
    // 站点安全密钥生成
    $random_str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $random_str = substr(str_shuffle($random_str), 0, 16);
    
    // 替换站点安全密钥
    $cfg_content = str_replace("\$_W['authkey']                = 'cblL5knV9ZSET8g1';", "\$_W['authkey']                = '{$random_str}';", $cfg_content);
    // 数据库主机
    $cfg_content = str_replace("\$_W['db']['host']             = 'localhost';",        "\$_W['db']['host']             = '{$dbhost}';", $cfg_content);
    // 数据库用户名
    $cfg_content = str_replace("\$_W['db']['username']         = 'root';",             "\$_W['db']['username']         = '{$dbuser}';", $cfg_content);
    // 数据库密码
    $cfg_content = str_replace("\$_W['db']['password']         = '';",                 "\$_W['db']['password']         = '{$dbpw}';", $cfg_content);
    // 数据库名
    $cfg_content = str_replace("\$_W['db']['name']             = 'weizephp';",         "\$_W['db']['name']             = '{$dbname}';", $cfg_content);
    // 数据库端口（默认是3306）
    $cfg_content = str_replace("\$_W['db']['port']             = '3306';",             "\$_W['db']['port']             = '{$dbport}';", $cfg_content);
    // 数据库表前缀
    $cfg_content = str_replace("\$_W['db']['tablepre']         = 'w_';",               "\$_W['db']['tablepre']         = '{$tablepre}';", $cfg_content);
	
	// COOKIE随机前缀
	$random_cookie_pre = substr(str_shuffle($random_str), 0, 4);
    $cfg_content = str_replace("\$_W['cookie']['prefix']       = 'W_';",               "\$_W['cookie']['prefix']       = '{$random_cookie_pre}_';", $cfg_content);
    
    // 更改配置文件
    file_put_contents($cfg_filename, $cfg_content, LOCK_EX);
    unset($cfg_filename, $cfg_content);
    
    /* ---------------------------------------------------------------- */
    
    // 创建安装锁文件
    file_put_contents('install.lock', 'Weize PHP INSTALLED');
    
    // 安装成功提示
    w_install_success('安装完成！', '../index.php');
    
}
?>
