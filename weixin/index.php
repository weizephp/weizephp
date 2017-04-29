<?php
/**
 * +--------------------------------------
 * | WeChat index page
 * +--------------------------------------
 * | Author: Ze Wei <E-mail:weizesw@gmail.com> <QQ:310472156>
 * +--------------------------------------
 */

include 'config.inc.php';
include 'lib/wz_wechat_base.class.php';

$wz_wechat_base = new wz_wechat_base();

// ---------------------------------------

// 1.如果不是微信服务器发过来的，直接停掉
$result = $wz_wechat_base->check_signature();
if($result === false) {
	echo "";
	exit;
}
unset($result);

// ---------------------------------------

// 2.如果是“微信接入”，直接原样输出
if(isset($_GET['echostr'])) {
	echo $_GET['echostr'];
	exit;
}

// ---------------------------------------

// 3.接收消息，并解析，如果出错，直接停掉
$postObj = $wz_wechat_base->receive_msg();
if($postObj === false) {
	echo "";
	exit;
}

// ---------------------------------------

/**
 * 4.回复消息（响应消息）
 */

$fromUsername = $postObj->FromUserName;
$toUsername   = $postObj->ToUserName;
$time         = time();

if($postObj->MsgType == 'event') {
	
	// --- 接收事件消息（比如：关注、取消关注...），然后回复或者做别的处理 ---
	include 'response/event.inc.php';
	
} else if($postObj->MsgType == 'text') {
	
	// --- 接收文本消息，然后回复或者做别的处理 ---
	include 'response/text.inc.php';
	
} else if($postObj->MsgType == 'image') {
	
	// --- 接收图片消息，然后回复或者做别的处理 ---
	include 'response/image.inc.php';
	
} else if($postObj->MsgType == 'voice') {
	
	// --- 接收语音消息，然后回复或者做别的处理 ---
	include 'response/voice.inc.php';
	
} else if($postObj->MsgType == 'video') {
	
	// --- 接收视频消息，然后回复或者做别的处理 ---
	include 'response/video.inc.php';
	
} else if($postObj->MsgType == 'shortvideo') {
	
	// --- 接收小视频消息，然后回复或者做别的处理 ---
	include 'response/shortvideo.inc.php';
	
} else if($postObj->MsgType == 'location') {
	
	// --- 接收地理位置消息，然后回复或者做别的处理 ---
	include 'response/location.inc.php';
	
} else if($postObj->MsgType == 'link') {
	
	// --- 接收链接消息，然后回复或者做别的处理 ---
	include 'response/link.inc.php';
	
} else {
	echo "";
	exit;
}

?>
