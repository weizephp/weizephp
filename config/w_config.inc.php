<?php
/**
 * 配置文件
 */

$wconfig = array();

// 系统配置
$wconfig['error_reporting'] = false;
$wconfig['version']         = 'WeizePHP 4.0 Release 20170501';
$wconfig['timezone']        = 'Asia/Hong_Kong';
$wconfig['protocol']        = 'http';
$wconfig['lang']            = 'zh_cn';
$wconfig['theme']           = 'default';
$wconfig['authkey']         = 'cblL5knE4k8ET8g1';
$wconfig['founder']         = array('1');

// 默认应用、默认模块、默认操作配置
$wconfig['app'] = 'content';
$wconfig['m']   = 'home';
$wconfig['a']   = 'index';

// 数据库配置
$wconfig['db']             = array();
$wconfig['db']['host']     = 'localhost';
$wconfig['db']['username'] = 'root';
$wconfig['db']['password'] = '';
$wconfig['db']['name']     = 'weizephp4';
$wconfig['db']['port']     = '3306';
$wconfig['db']['socket']   = null;
$wconfig['db']['charset']  = 'utf8';
$wconfig['db']['tablepre'] = 'w_';

// Cookie 配置
$wconfig['cookie']           = array();
$wconfig['cookie']['prefix'] = 'Wz_';
$wconfig['cookie']['domain'] = '';
$wconfig['cookie']['path']   = '/';

// Session 配置
$wconfig['session'] = array();
$wconfig['session']['cookie_lifetime'] = 0;    // 单位秒（0表示关闭浏览器后浏览器的sid就过期）
$wconfig['session']['expire']          = 3600; // 单位秒（这里是会员60分钟sid不活动后过期，游客的话60秒后过期）

// Access Token 配置
$wconfig['accesstoken'] = array();
$wconfig['accesstoken']['expire'] = 2592000; // 单位秒（这里是30天后过期）

// 验证码配置
$wconfig['captcha'] = array();
$wconfig['captcha']['expire'] = 60; // 单位秒（这里是1分钟cid不活动后过期）

// 登陆锁定配置
$wconfig['login_lock_second'] = 900; // 输入出错后，锁定15分钟
$wconfig['login_lock_number'] = 6;   // 最多允许输入错误6次

// 游客允许访问配置
$wconfig['permission_guest'] = array(
    // 前台内容
    // ...
    // 后台管理
    'admin/login/index',
    'admin/login/login',
    'admin/login/logout',
    // 会员中心
    'member/login/index',
    'member/login/login',
    'member/login/logout',
    // 通用功能
    'general/captcha/createkey',
    'general/captcha/display',
	// 应用API
    'appapi/login/login',
    'appapi/login/logout',
    //...
);

// 公共目录、上传目录、缩略图目录配置（方便CDN之用）
$wconfig['public_path'] = 'public';
$wconfig['upload_path'] = 'upload';
$wconfig['thumb_path']  = 'upload/thumb';

// 通用上传配置
$wconfig['general_upload_max_size'] = 2; // 2M

// 缩略图配置

// URL重写配置
$wconfig['rewrite']            = array();
$wconfig['rewrite']['on']      = false;
// 内容伪静态
$wconfig['rewrite']['content'] = array();
$wconfig['rewrite']['content'] = array(
    '/^index.php\?m=singlepage&a=view&spid=(\d+)$/' => 'singlepage-view-$1.html', // 单页伪静态示例
);
/*
// 会员页伪静态
$wconfig['rewrite']['member'] = array();
$wconfig['rewrite']['member'] = array(
    '/^member.php\?m=login$/' => 'member-login.html', // 会员登陆页伪静态示例
);
*/
