<?php
/**
 * 接收文本消息，然后回复
 */

if(!defined('IN_WZ_WECHAT')) {
	exit('Access Denied');
}

// 1.获取文本关键字内容
$keyword = trim($postObj->Content);
if(empty($keyword)) {
	echo 'Input something...';
	exit();
}

// 2.回复。（你可以根据“关键字”，回复自己想要的结果）
if($keyword == '回复文字') {
	
	// --- 回复文字 ---
	$textTpl = "<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[%s]]></MsgType>
				<Content><![CDATA[%s]]></Content>
				<FuncFlag>0</FuncFlag>
				</xml>";

	$msgType    = "text";
	$contentStr = "回复文字：欢迎关注“韦泽好玩多”!";
	$resultStr  = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
	echo $resultStr;
	
} else if($keyword == '回复图文') {
	
	// --- 回复图文 ---
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
	$title1          = 'WeizePHP 4.0 会在2017年春节前发布';
	$description1    = '点击图片进入详细介绍';
	$picurl1         = 'http://weizephp.75hh.com/wx/image/wx000.jpg';
	$url1            = 'http://weizephp.75hh.com/';

	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $responseMsgType, $title1, $description1, $picurl1, $url1);
	echo $resultStr;
	
} else {
	
	// --- 如果是用户乱写的话。这里随机回复一段名言。 ---
	$random_say = array(
		'但愿人长久，千里共婵娟。',
		'长风破浪会有时，直挂云帆济沧海。',
		'命里有时终须有，命里无来莫强求。',
		'如果爱，请真爱！',
		'心存高远，脚踏实地。',
		'少壮不努力，老大徒伤悲。',
		'只要功夫深，铁杵磨成针。',
		'世上无难事，只要肯登攀。',
		'吃得苦中苦，方为人上人。',
		'一寸光阴一寸金，寸金难买寸光阴。',
		'有志不在年高，无志空活百岁。',
		'你热爱生命吗？那么别浪费时间，因为时间是构成生命的材料。',
		'不经历风雨，怎能见彩虹？',
		'永远要记得，成功的决心远胜于任何东西。'
	);
	shuffle($random_say);
	
	$textTpl = "<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[%s]]></MsgType>
				<Content><![CDATA[%s]]></Content>
				<FuncFlag>0</FuncFlag>
				</xml>";

	$msgType    = "text";
	$contentStr = $random_say[0];
	$resultStr  = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
	echo $resultStr;
	
}
