<?php
/**
 * +--------------------------------------
 * | WeiZe weChat config
 * +--------------------------------------
 */

// ---------------------------------------

date_default_timezone_set('Asia/Shanghai');

// ---------------------------------------

define('IN_WZ_WECHAT', true);
define('WZ_ROOT_PATH', dirname(__FILE__));
define('WZ_SCRIPT',    basename($_SERVER['SCRIPT_NAME']));
define('WZ_TIMESTAMP', time());

// ----------------------------------------

define('WECHAT_APPID',          'wx85cde4f75fbd83edesc2'); // AppID(应用ID)
define('WECHAT_APPSECRET',      '73e513c8f35908sd946df7ac1sdsdsdsdsc56ec6b10'); // AppSecret(应用密钥)
//define('WECHAT_URL',            'http://weizephp.75hh.com/wx/'); // URL(服务器地址)
define('WECHAT_TOKEN',          'QnixknB4Csdsdsds87TMh7rAd7z'); // Token(令牌)
define('WECHAT_ENCODINGAESKEY', 'FIwXVacPSuzZupnJXedJoxcoMswsstfGYNcWJdYyVsXV'); // EncodingAESKey(消息加解密密钥)

// ----------------------------------------

define('WECHAT_PAY_MCH_ID',     '1368233808'); // 商户号
define('WECHAT_PAY_MCH_NAME',   '苏州年年网络科技有限公司'); // 商户名
define('WECHAT_PAY_MCH_KEY',    'www.501133.com');

// ----------------------------------------

$_WZ = array();

// 系统配置
$_WZ['error_reporting'] = false;
$_WZ['version']         = 'WeiZe weChat 1.0 Release 20161122';
$_WZ['lang_pack']       = 'zh_cn';
$_WZ['template']        = 'default';
$_WZ['authkey']         = 'YGwaLB9YnUzc2nvm';
$_WZ['founder']         = array('1');

// 数据库配置
$_WZ['db']             = array();
$_WZ['db']['host']     = 'localhost';
$_WZ['db']['username'] = 'root';
$_WZ['db']['password'] = '';
$_WZ['db']['name']     = 'wechat';
$_WZ['db']['port']     = '3306';
$_WZ['db']['socket']   = null;
$_WZ['db']['charset']  = 'utf8';
$_WZ['db']['tablepre'] = 'cs_';

