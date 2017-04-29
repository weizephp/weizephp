<?php
/**
 * +----------------------------------------------------------------------
 * | WeizePHP framework
 * +----------------------------------------------------------------------
 * | Copyright (c) 2013-2113 http://weizephp.75hh.com/ All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: Ze Wei <E-mail:weizesw@gmail.com> <QQ:310472156>
 * +----------------------------------------------------------------------
 * | Description: 图片验证码管理类库
 * +----------------------------------------------------------------------
 */

/*
验证码数据库结构:
---------------------------------
CREATE TABLE IF NOT EXISTS `w_captcha` (
  `cid` char(8) NOT NULL,
  `lastvisit` int(10) unsigned NOT NULL DEFAULT '0',
  `code` char(6) NOT NULL DEFAULT '',
  `verified` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `count` tinyint(1) unsigned NOT NULL DEFAULT '0',
  UNIQUE KEY `cid` (`cid`) USING HASH
) ENGINE=MEMORY DEFAULT CHARSET=utf8 ;
*/

/*
验证码使用方式
---------------------------------
说明：请参照后台登陆的使用方式
*/


class w_captcha {
	
    /**
     * 创建 captcha key
     * @return string
     */
    public function createkey() {
        global $wconfig;
        $cid        = w_random(8);
        $sign       = w_sign( array($cid, W_TIMESTAMP, 'captcha', $wconfig['authkey']) );
        $captchakey = $cid . W_TIMESTAMP . $sign;
        return $captchakey;
    }
    
    /**
     * 分割 captcha key
     * @return array
     */
    public function splitkey($captchakey) {
        global $wconfig;
        if( strlen($captchakey) == 50 ) {
            $cid  = substr($captchakey, 0, 8);
            $time = intval(substr($captchakey, 8, 10));
            $sign = substr($captchakey, 18);
        } else {
            $cid  = w_random(8);
            $time = time();
            $sign = '';
        }
        $keys = array(
            'cid'  => $cid,
            'time' => $time,
            'sign' => $sign
        );
        return $keys;
    }
    
    /**
     * 显示验证码
     */
    public function display($width = 80, $height = 30, $length = 4) {
        global $wconfig, $wdb;
        
        $x = $width;
        $y = $height;
        
        if($length > 6) {
            $length = 6;
        }
        
        //------
        $captchakey = isset($_GET['captchakey']) ? trim($_GET['captchakey']) : "";
        
        $keys = $this->splitkey($captchakey);
        
        $last_expire = $keys['time'] + $wconfig['captcha']['expire'];
        
        $verify_sign = w_sign( array($keys['cid'], $keys['time'], 'captcha', $wconfig['authkey']) );
        
        if( ($last_expire > W_TIMESTAMP) && ($verify_sign === $keys['sign']) ) {
            $str = 'ABCDEFGHIJKLMNPQRSTUVWXYZABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
            $shuffled = substr(str_shuffle($str), -$length);
            
            $captcha_expire = W_TIMESTAMP - $wconfig['captcha']['expire'];
		    $sql = "DELETE FROM `{$wconfig['db']['tablepre']}captcha` WHERE `lastvisit`<'{$captcha_expire}'";
		    $wdb->query($sql);
            
            $sql = "REPLACE INTO `{$wconfig['db']['tablepre']}captcha` (`cid`, `lastvisit`, `code`, `verified`, `count`) VALUES ('{$keys['cid']}', '". W_TIMESTAMP ."', '{$shuffled}',  '0', '0')";
            $wdb->query($sql);
        } else {
            $shuffled = 'Error';
        }
        
        //------
        
        $im = imagecreate($x, $y);
        $bg_color = imagecolorallocate( $im, rand(50,200), rand(0,155), rand(0,155) );
        $text_color = imagecolorallocate( $im, 255, 255, 255 );
        
        imagestring($im, 5, floor(($x-(9*$length))/2), floor(($y-15)/2), $shuffled, $text_color);
        
        $line_color = imagecolorallocate($im, rand(0,255), rand(0,255), rand(0,255));
        for($i=0; $i<6; $i++) {
            imageline($im, rand(0,$x), 0, rand(0,$x), $y, $line_color);
        }
        
        for($i=0; $i<12; $i++) {
            imagesetpixel($im, rand(0,$x), rand(0,$y), $text_color);
        }
        
        header('Content-type: image/png');
        imagepng($im);
        imagedestroy($im);
    }
    
    /**
     * 检查验证码
     * @return int
     *   -1 验证码过期或无效
     *   0  验证码输入错误
     *   1  验证码输入正确
     */
    public function check($captchakey, $captchaval) {
        global $wconfig, $wdb;
		
		// 接收验证码值健(key)，并分割出cid,time,sign数据
		$keys = $this->splitkey($captchakey);
		
		// 检查验证码签名是否有效
		$verify_sign = w_sign( array($keys['cid'], $keys['time'], 'captcha', $wconfig['authkey']) );
		if( $verify_sign !== $keys['sign'] ) {
			return -1;
		}
		
		// 获取验证码信息
		$sql = "SELECT * FROM `{$wconfig['db']['tablepre']}captcha` WHERE `cid`='{$keys['cid']}'";
		$row = $wdb->get_row($sql);
		
		// 检查验证码是否已过期
		if( empty($row) ) {
			return -1;
		}
        if( $row['verified'] > 0 ) {
            return -1;
        }
		if( $row['count'] > 6 ) {
			return -1;
		}
        $last_expire = intval($row['lastvisit']) + $wconfig['captcha']['expire'];
        if( $last_expire < W_TIMESTAMP ) {
            return -1;
        }
		
		// 检查验证码输入是否错误
		if( strtoupper($captchaval) == $row['code'] ) {
            return 1;
		} else {
            return 0;
        }
    }
    
    /**
     * 更新检查计数，如果验证码输入错误超过6次，验证码则失效
     */
    public function check_error_count($captchakey) {
        global $wconfig, $wdb;
        $keys = $this->splitkey($captchakey);
        $sql = "UPDATE `{$wconfig['db']['tablepre']}captcha` SET `lastvisit`='". W_TIMESTAMP ."', `count`=`count`+1 WHERE `cid`='{$keys['cid']}'";
        $wdb->query($sql);
    }
    
    /**
     * 验证完成，锁定验证码，让验证码失效
     */
    public function succeed($captchakey) {
        global $wconfig, $wdb;
        $keys = $this->splitkey($captchakey);
        $sql = "UPDATE `{$wconfig['db']['tablepre']}captcha` SET `lastvisit`='". W_TIMESTAMP ."', `verified`='1' WHERE `cid`='{$keys['cid']}'";
        $wdb->query($sql);
    }
    
}
