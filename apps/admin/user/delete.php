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

// [*]^_^不允许删除的啦
w_errormessage("^_^系统不允许删除，如果不想使用了，可以修改为不启用");

// formtoken验证
$result = w_security::check_formtoken($_GET['formtoken']);
if($result == false) {
    w_errormessage('非法操作');
}
