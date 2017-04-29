<?php
/**
 * 接收事件消息
 */

if(!defined('IN_WZ_WECHAT')) {
	exit('Access Denied');
}

if($postObj->Event == 'subscribe') {
	
	// --- 用户关注 ---
	
	// 这里是个例子。我用回复图文来做例子。。。
	$textTpl = "<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[%s]]></MsgType>
		<ArticleCount>1</ArticleCount>
		<Articles>
		<item>
		<Title><![CDATA[%s]]></Title> 
		<Description><![CDATA[%s]]></Description>
		<PicUrl><![CDATA[%s]]></PicUrl>
		<Url><![CDATA[%s]]></Url>
		</item>
		</Articles>
		</xml>";
	
	$responseMsgType = 'news';
	$title1          = '嗨，这是我写的一个微信小框架，很高兴你能关注我，这是一段小程序自动回复的！';
	$description1    = '点击图片进入图文详细';
	$picurl1         = 'http://weizephp.75hh.com/wx/image/wx000.jpg';
	$url1            = 'http://weizephp.75hh.com/';
	
	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $responseMsgType, $title1, $description1, $picurl1, $url1);
	echo $resultStr;
	
} else if($postObj->Event == 'unsubscribe') {
	
	// --- 用户取消关注 ---
	
	echo ""; //写你自己的程序在这里...
	
} else if($postObj->Event == 'LOCATION') {
	
	// --- 用户上报地理位置事件 ---
	
	echo ""; //写你自己的程序在这里...}
	
} else if($postObj->Event == 'CLICK') {
	
	// --- 自定义菜单事件 - 点击菜单拉取消息时的事件推送 ---
	
	echo ""; //写你自己的程序在这里...
	
} else if($postObj->Event == 'VIEW') {
	
	// --- 自定义菜单事件 - 点击菜单跳转链接时的事件推送 ---
	
	echo ""; //写你自己的程序在这里...
	
} else {
	
	// --- 啥也不做 ---
	echo "";
	
}
