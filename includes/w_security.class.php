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

if(!defined('IN_WEIZEPHP')){exit('Access Denied');}

class w_security {
    
    /**
     * 设置formtoken
     * @return boolean|string
     */
    public static function set_formtoken() {
        global $_W, $WDB;
        if(empty($_W['session']['sid'])) {
            return false;
        }
        $server_formtoken = w_random();
        $client_formtoken = md5($server_formtoken.$_W['authkey']);
        $sql = "UPDATE `{$_W['db']['tablepre']}sessions` SET `formtoken`='{$server_formtoken}' WHERE `sid`='{$_W['session']['sid']}'";
        if($WDB->query($sql)) {
            return $client_formtoken;
        } else {
            return false;
        }
    }
    
    /**
     * 检验formtoken
     * @param string $formtoken
     * @return boolean
     */
    public static function check_formtoken($formtoken) {
        global $_W, $WDB;
        if(empty($_W['session']['sid'])) {
            return false;
        }
        $formtoken = trim($formtoken);
        if(empty($formtoken)) {
            return false;
        }
        $server_formtoken_md5 = md5($_W['session']['formtoken'].$_W['authkey']);
        if($server_formtoken_md5 === $formtoken) {
            return true;
        } else {
            return false;
        }
    }
}
