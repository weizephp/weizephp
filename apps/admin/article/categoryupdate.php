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

if(!isset($_POST['categoryid'])) {
    
    /**
     * 文章分类更新页面
     */
    
    include 'includes/w_extend.func.php';
    
    // 接收分类ID，并取得所有分类
    $categoryid = isset($_GET['categoryid']) ? intval($_GET['categoryid']) : 0;
    $sql        = "SELECT * FROM `{$_W['db']['tablepre']}articlescategories` WHERE 1";
    $categories = $WDB->get_all($sql);
    
    // 获取当前分类
    $row = array();
    foreach($categories as $v) {
        if($v['categoryid'] == $categoryid) {
            $row = $v;
            break;
        }
    }
    
    // 检查数据
    if(empty($row)) {
        w_errormessage("没有该分类，返回上一页");
    }
    
    // $row数据html安全处理，防止xss攻击
    foreach($row as $k => $v) {
        $row[$k] = htmlspecialchars($v);
    }
    
    // formtoken设置、创建分类树
    $formtoken  = w_security::set_formtoken();
    $categories = w_category_tree_html($categories);
    
    // 删除当前分类树
    foreach($categories as $k => $v) {
        if($v['categoryid'] == $categoryid) {
            unset($categories[$k]);
            break;
        }
    }
    
    // 载入模板
    include $_W['template_path'].'/article/categoryupdate.html.php';
    
} else {
    
    /**
     * 文章分类更新进数据库
     */
    
    // formtoken验证
    $result = w_security::check_formtoken($_POST['formtoken']);
    if($result == false) {
        exit('{"status":0, "msg":"非法操作"}');
    }
    
    // 接收数据
    $categoryid     = isset($_POST['categoryid'])     ? intval($_POST['categoryid']) : 0;
    $pid            = isset($_POST['pid'])            ? intval($_POST['pid']) : 0;
    $categoryname   = isset($_POST['categoryname'])   ? addslashes(trim($_POST['categoryname'])) : '';
    $status         = isset($_POST['status'])         ? intval($_POST['status']) : 0;
    $displayorder   = isset($_POST['displayorder'])   ? intval($_POST['displayorder']) : 1;
    $seotitle       = isset($_POST['seotitle'])       ? addslashes(trim($_POST['seotitle'])) : '';
    $seokeywords    = isset($_POST['seokeywords'])    ? addslashes(trim($_POST['seokeywords'])) : '';
    $seodescription = isset($_POST['seodescription']) ? addslashes(trim($_POST['seodescription'])) : '';
    
    // 输入检查
    if(empty($categoryname)) {
        exit('{"status":0, "msg":"分类名称不能为空"}');
    }
    if($pid == $categoryid) {
        exit('{"status":0, "msg":"上级分类不能选择自身"}');
    }
    
    // 获取旧的分类信息
    $sql = "SELECT `categoryid`, `pid`, `categoryname` FROM `{$_W['db']['tablepre']}articlescategories` WHERE `categoryid`='{$categoryid}'";
    $old = $WDB->get_row($sql);
    
    // 检查是否重名
    $sql = "SELECT `categoryid`, `pid` FROM `{$_W['db']['tablepre']}articlescategories` WHERE `categoryid`<>'{$categoryid}' AND `categoryname`='{$categoryname}'";
    $row = $WDB->get_row($sql);
    if(!empty($row)) {
        exit('{"status":0, "msg":"存在同名"}');
    }
    
    // 更新进数据库
    $sql = "UPDATE `{$_W['db']['tablepre']}articlescategories` SET `pid`='{$pid}',`categoryname`='{$categoryname}',`status`='{$status}',`displayorder`='{$displayorder}',`seotitle`='{$seotitle}',`seokeywords`='{$seokeywords}',`seodescription`='{$seodescription}' WHERE `categoryid`='{$categoryid}'";
    if($WDB->query($sql)) {
        $formtoken  = w_security::set_formtoken();//[*]为了安全，需要重置formtoken
        if($old['categoryname'] != $categoryname) {
            w_adminlog('把文章分类“'. $old['categoryname'] .'”更新为：'. $categoryname);
        } else {
            w_adminlog('更新了文章分类：'. $categoryname);
        }
        exit('{"status":1, "msg":"更新成功"}');
    } else {
        exit('{"status":0, "msg":"更新失败"}');
    }
    
}
