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

$_W = array();

// 系统配置
$_W['error_reporting']        = false;
$_W['version']                = 'WeizePHP 3.0 Release 20160918';
$_W['timezone']               = 'Asia/Hong_Kong';
$_W['protocol']               = '';
$_W['language_pack']          = 'zh_cn';
$_W['template']               = 'default';
$_W['authkey']                = 'cblL5knV9ZSET8g1';
$_W['founder']                = array('1');

// 默认应用配置
$_W['app']                    = 'content';
$_W['module']                 = 'home';
$_W['action']                 = 'index';

// 数据库配置
$_W['db']                     = array();
$_W['db']['host']             = 'localhost';
$_W['db']['username']         = 'root';
$_W['db']['password']         = '';
$_W['db']['name']             = 'weizephp';
$_W['db']['port']             = '3306';
$_W['db']['socket']           = null;
$_W['db']['charset']          = 'utf8';
$_W['db']['tablepre']         = 'w_';

// COOKIE配置
$_W['cookie']                 = array();
$_W['cookie']['prefix']       = 'W_';
$_W['cookie']['domain']       = '';
$_W['cookie']['path']         = '/';

// 系统自己的一套SESSION配置
$_W['skey_expire']            = 7200; // Session key expire
$_W['guest_sid_expire']       = 60;   // Guest session id expire 游客sid过期时间(秒)
$_W['user_sid_expire']        = 1800; // User session id expire  用户sid过期时间(秒)

// Access token
$_W['accesstoken_key_expire'] = 2592000; // 单位秒（这里是30天后过期）

// 登陆锁定配置
$_W['lock_seconds']           = 900;
$_W['lock_count']             = 6;

// 验证码过期时间配置
$_W['captchaexpire']          = 300;

// 公共文件夹、附件的CDN目录配置
$_W['public_path']            = '';
$_W['attachment_path']        = '';
$_W['thumb_path']             = '';

// 游客访问权限配置
$_W['guest_permission']       = array(
    // Admin center
    'admin/signin/index',
    'admin/signin/signin',
    'admin/signin/signout',
    // Member center
    'member/signin/index',
    'member/signin/signin',
    'member/signin/signout',
    // Generals app
    'generals/captcha/getckey',
    'generals/captcha/create',
    'generals/image/thumb',
	// AppApi
    'appapi/signin/signin',
    'appapi/signin/signout',
    //...
);

// 允许的缩略图目录配置
$_W['thumb_allow_folder']     = array(
    'article',
    'singlepage',
    //...
);

// 伪静态配置
$_W['rewrite']                = array();
$_W['rewrite']['on']          = false;
$_W['rewrite']['content']     = array(); // $_W['rewrite'][应用名]
$_W['rewrite']['content'][]   = array('/^content.php\?m=news&a=list&id=(\d+)$/' => 'news-list-$1.html');
$_W['rewrite']['content'][]   = array('/^content.php\?m=news&a=view&id=(\d+)$/' => 'news-view-$1.html');

