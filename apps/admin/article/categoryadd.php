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

if(!isset($_POST['categoryname'])) {
    
    /**
     * 新闻分类添加页面
     */
    include 'includes/w_extend.func.php';
    $formtoken  = w_security::set_formtoken();
    $sql        = "SELECT `categoryid`, `pid`, `categoryname`, `status`, `displayorder` FROM `{$_W['db']['tablepre']}articlescategories`";
    $categories = $WDB->get_all($sql);
    $categories = w_category_tree_html($categories);
    include $_W['template_path'].'/article/categoryadd.html.php';
    
} else {
    
    /**
     * 新闻分类写入数据库
     */
    
    $result = w_security::check_formtoken($_POST['formtoken']);
    if($result == false) {
        exit('{"status":0, "msg":"非法操作"}');
    }
    
    $pid            = isset($_POST['pid'])            ? intval($_POST['pid']) : 0;
    $categoryname   = isset($_POST['categoryname'])   ? addslashes(trim($_POST['categoryname'])) : '';
    $status         = isset($_POST['status'])         ? intval($_POST['status']) : 0;
    $displayorder   = isset($_POST['displayorder'])   ? intval($_POST['displayorder']) : 1;
    $seotitle       = isset($_POST['seotitle'])       ? addslashes(trim($_POST['seotitle'])) : '';
    $seokeywords    = isset($_POST['seokeywords'])    ? addslashes(trim($_POST['seokeywords'])) : '';
    $seodescription = isset($_POST['seodescription']) ? addslashes(trim($_POST['seodescription'])) : '';
    
    if(empty($categoryname)) {
        exit('{"status":0, "msg":"分类名称不能为空"}');
    }
    
    $sql = "SELECT `categoryid` FROM `{$_W['db']['tablepre']}articlescategories` WHERE `categoryname`='{$categoryname}'";
    $row = $WDB->get_row($sql);
    if(!empty($row)) {
        exit('{"status":0, "msg":"同名分类已经存在"}');
    }
    
    $sql = "INSERT INTO `{$_W['db']['tablepre']}articlescategories`(`pid`, `categoryname`, `status`, `displayorder`, `seotitle`, `seokeywords`, `seodescription`) VALUES ('{$pid}', '{$categoryname}', '{$status}', '{$displayorder}', '{$seotitle}', '{$seokeywords}', '{$seodescription}')";
    if($WDB->query($sql)) {
        $formtoken = w_security::set_formtoken(); // 重置formtoken
        w_adminlog("添加文章分类：". $categoryname);
        exit('{"status":1, "msg":"操作成功"}');
    } else {
        exit('{"status":0, "msg":"操作失败"}');
    }
    
}
