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

/*
+--------------------------------------
| 使用方法
+--------------------------------------

// 载入 db_install_mysqli 类库
include 'db_install_mysqli.class.php';

// 定义MySQL连接信息
$host      = 'localhost';
$username  = 'root';
$passwd    = '';
$dbname    = 'weizephp';
$port      = 3306;
$socket    = NULL;
$dbcharset = 'utf8';

// 连接MySQL
$DB = new db_install_mysqli($host, $username, $passwd);
if( $DB->connect_error ) {
    die( 'Connect Error (' . $DB->connect_errno . ') ' . $DB->connect_error );
}
if( !$DB->set_charset($dbcharset) ) {
    die( "Error loading character set {$dbcharset}: {$DB->error}" );
}

// 选择用于数据库查询的默认数据库
$result = $DB->select_db($dbname);
if( !$result ) {
	if( $DB->db_create_db($dbname) ) {
	    // 重新选择数据库
		$DB->select_db($dbname);
	} else {
	    // 不能创建数据库，请手动创建
		exit('Can\'t create '. $dbname .'!');
	}
}

+--------------------------------------
*/

class db_install_mysqli extends mysqli {
    
    public $dbcharset = 'utf8';
    
    /**
     * 数据库执行语句，可执行查询添加修改删除等任何sql语句
     */
    public function query($sql, $resultmode = MYSQLI_STORE_RESULT) {
        $result = parent::query($sql, $resultmode);
        if($result === FALSE) {
            die('Invalid query: ' . $this->errno . ' '. $this->error. '. SQL: ' . $sql);
        }
        return $result;
    }
    
    /**
     * 创建数据库
     */
    public function db_create_db($dbname) {
        if($this->get_server_info() > '4.1') {
            $sql = "CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET " . $this->dbcharset;
        } else {
            $sql = "CREATE DATABASE IF NOT EXISTS `{$dbname}`";
        }
        if($this->query($sql)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
}
