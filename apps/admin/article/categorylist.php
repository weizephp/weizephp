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

include 'includes/w_extend.func.php';

$formtoken  = w_security::set_formtoken();

$sql        = "SELECT `categoryid`, `pid`, `categoryname`, `status`, `displayorder` FROM `{$_W['db']['tablepre']}articlescategories`";
$categories = $WDB->get_all($sql);

$categories = w_category_tree_html($categories);

include $_W['template_path'].'/article/categorylist.html.php';
