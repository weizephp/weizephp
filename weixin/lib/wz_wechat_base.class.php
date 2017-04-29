<?php
/**
 * +--------------------------------------
 * | WeiZe wechat base class
 * +--------------------------------------
 */

class wz_wechat_base {
	
	/**
	 * 1、检查签名。用来验证请求是不是来自微信服务器
	 * @return boolen 
	 */
	public function check_signature() {
		$signature = isset($_GET["signature"]) ? $_GET["signature"] : '';
		$timestamp = isset($_GET["timestamp"]) ? $_GET["timestamp"] : '';
		$nonce     = isset($_GET["nonce"])     ? $_GET["nonce"] : '';
		
		$tmpArr = array(WECHAT_TOKEN, $timestamp, $nonce);
		// use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 2、接收消息，并解析（要用 === 运算符来测试返回值）
	 * @return object or false
	 */
	public function receive_msg() {
		$postdata = file_get_contents("php://input");
		if(!empty($postdata)) {
			libxml_disable_entity_loader(true);
			$postObj = simplexml_load_string($postdata, 'SimpleXMLElement', LIBXML_NOCDATA);
			return $postObj;
		} else {
			return false;
		}
	}
	
	/**
	 * 3、发送消息 - 被动回复用户消息
	 * @return string xml
	 */
	public function response_msg() {
		
	}
	
	
	/** --------------------------------------------------------- */
	
	
	/**
	 * 获取access_token
	 * @return string
	 */
	public function get_access_token() {
		$access_token_arr = array();
		
		$cache_file = './data/cache/wechat_access_token.cache.php';
		
		// 1.如果存在缓存文件，就载入
		if(is_file($cache_file)) {
			include($cache_file);
		}
		
		// 如果缓存的 access_token 没有过期，就直接返回 access_token
		if(!empty($access_token_arr) && isset($access_token_arr['expire_time']) && ($access_token_arr['expire_time'] > WZ_TIMESTAMP)) {
			return $access_token_arr['access_token'];
		}
		
		// 否则，从微信服务器上获取 access_token
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='. WECHAT_APPID .'&secret='. WECHAT_APPSECRET;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		$jsoninfo = json_decode($output, true);
		
		// 如果获取成功，就把 access_token,expires_in,expire_time 保存进缓存，然后返回 access_token，否则返回空字符串
		if(isset($jsoninfo['access_token'])) {
			$access_token = $jsoninfo['access_token'];
			$expires_in   = $jsoninfo['expires_in'];
			$expire_time  = WZ_TIMESTAMP + ($jsoninfo['expires_in'] - 200);
            $content = <<<EOT
<?php
\$access_token_arr                 = array();
\$access_token_arr['access_token'] = '{$access_token}';
\$access_token_arr['expires_in']   = '{$expires_in}';
\$access_token_arr['expire_time']  = '{$expire_time}';

EOT;
            file_put_contents($cache_file, $content);
			return $jsoninfo['access_token'];
		} else {
			return "";
		}
	}
	
}
