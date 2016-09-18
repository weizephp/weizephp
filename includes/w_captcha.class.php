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

class w_captcha {
	
    /**
     * 创建验证码键
     */
	public static function create_ckey() {
		global $_W, $WDB;
		
		if(isset($_COOKIE[ $_W['cookie']['prefix'].'ckey' ])) {
			$captcha = self::check_ckey($_COOKIE[ $_W['cookie']['prefix'].'ckey' ]);
			if($captcha !== false) {
				return $_COOKIE[ $_W['cookie']['prefix'].'ckey' ];
			}
		}
		
		$sql = "INSERT INTO `{$_W['db']['tablepre']}captchas`(`code`, `createtime`, `verified`, `count`) VALUES ('', '".W_TIMESTAMP."', '0', '0')";
		if(!$WDB->query($sql)) {
			return '';
		}
		
		$cid  = $WDB->insert_id;
		$sign = w_create_sign(array($_W['authkey'], $cid, 'captcha'));
		$ckey = $cid.','.$sign;
		
		w_setcookie( $_W['cookie']['prefix'].'ckey', $ckey );
		
		return $ckey;
	}
	
	/* ---------------------------------------- */
	
	/**
	 * 检查验证码键
	 */
	public static function check_ckey($ckey) {
		global $_W, $WDB;
		
		if(preg_match('/^[0-9]{0,10}\,[a-zA-Z0-9]{32}$/', $ckey) === 0) {
			return false;
		}
		
		$ckey_arr = explode(',', $ckey);
		$ckey_arr[0] = (int)$ckey_arr[0];
		
		$sign = w_create_sign(array($_W['authkey'], $ckey_arr[0], 'captcha'));
		if($sign !== $ckey_arr[1]) {
			return false;
		}
		
		$sql = "SELECT `cid`, `code`, `createtime`, `verified`, `count` FROM `{$_W['db']['tablepre']}captchas` WHERE `cid`='{$ckey_arr[0]}'";
		$captcha = $WDB->get_row($sql);
		
		if(empty($captcha)) {
			return false;
		}
		
		if($captcha['verified'] == 1) {
			return false;
		}
		
		if($captcha['count'] > 6) {
			return false;
		}
		
		if((W_TIMESTAMP - $captcha['createtime']) > $_W['captchaexpire']) {
			$expired = W_TIMESTAMP - $_W['captchaexpire'];
			$sql = "DELETE FROM `{$_W['db']['tablepre']}captchas` WHERE `createtime`<'{$expired}'";
			$WDB->query($sql);
			return false;
		}
		
		return $captcha;
	}
	
	/**
	 * 检查验证码
	 */
	public static function check_code($ckey, $code) {
		global $_W, $WDB;
		
		$captcha = self::check_ckey($ckey);
		if($captcha === false) {
			return false;
		}
		
		if($captcha['code'] !== strtoupper($code)) {
			$count = $captcha['count'] + 1;
			$sql = "UPDATE `{$_W['db']['tablepre']}captchas` SET `count`='{$count}' WHERE `cid`='{$captcha['cid']}'";
			$WDB->query($sql);
			return false;
		}
		
		return $captcha['cid'];
	}
	
	/* ---------------------------------------- */
	
	/**
	 * 验证码过
	 */
	public static function verified($cid) {
		global $_W, $WDB;
		$sql = "UPDATE `{$_W['db']['tablepre']}captchas` SET `verified`='1' WHERE `cid`='{$cid}'";
		$WDB->query($sql);
	}
	
	/* ---------------------------------------- */
	
	/**
	 * 默认验证码图片
	 * @param int $cid
	 * @param number $width
	 * @param number $height
	 * @param number $length
	 */
	public static function default_img($cid, $width = 80, $height = 30, $length = 4) {
        global $_W, $WDB;
        
        $x = $width;
        $y = $height;
        
        if($length > 6) {
            $length = 6;
        }
        
        $str = 'ABCDEFGHIJKLMNPQRTUVWXYZ123456789';
        $shuffled = substr(str_shuffle($str), -$length);
        
		if($cid > 0) {
			$sql = "UPDATE `{$_W['db']['tablepre']}captchas` SET `code`='{$shuffled}',`createtime`='".W_TIMESTAMP."' WHERE `cid`='{$cid}'";
			$WDB->query($sql);
		}
        
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
}