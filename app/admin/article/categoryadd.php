<?php
/**
 * 文章分类添加
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

if( !isset($_POST['name']) ) {
    
    /**
     * 文章分类添加-页面
     */
    include W_ROOT_PATH . '/lib/w_extend.func.php';
    $sql        = "SELECT `cid`, `pid`, `name`, `status`, `displayorder` FROM `{$wconfig['db']['tablepre']}article_category`";
    $categories = $wdb->get_all($sql);
    $categories = w_category_tree_html($categories);
    include $wconfig['theme_path'] . '/admin/article/categoryadd.html.php';
    
} else {
    
    /**
     * 文章分类添加-数据入库
     */
    $formtoken = isset($_POST['formtoken']) ? trim($_POST['formtoken']) : '';
    if( $formtoken != $wuser->formtoken ) {
        exit('{"status":0, "msg":"非法操作"}');
    }
    
    $pid            = isset($_POST['pid'])            ? intval($_POST['pid']) : 0;
    $name           = isset($_POST['name'])           ? addslashes(trim($_POST['name'])) : '';
    $status         = isset($_POST['status'])         ? intval($_POST['status']) : 0;
    $displayorder   = isset($_POST['displayorder'])   ? intval($_POST['displayorder']) : 1;
    $pic_id         = isset($_POST['pic_id'])         ? intval($_POST['pic_id']) : 0;
    $seotitle       = isset($_POST['seotitle'])       ? addslashes(trim($_POST['seotitle'])) : '';
    $seokeywords    = isset($_POST['seokeywords'])    ? addslashes(trim($_POST['seokeywords'])) : '';
    $seodescription = isset($_POST['seodescription']) ? addslashes(trim($_POST['seodescription'])) : '';
    
    if( empty($name) ) {
        exit('{"status":0, "msg":"分类名称不能为空"}');
    }
    
    $sql = "SELECT `cid` FROM `{$wconfig['db']['tablepre']}article_category` WHERE `name`='{$name}'";
    $row = $wdb->get_row($sql);
    if( !empty($row) ) {
        exit('{"status":0, "msg":"同名分类已经存在"}');
    }
    
    $pic = '';
    if( $pic_id > 0 ) {
        $sql     = "SELECT `id`, `uid`, `filepath` FROM `{$wconfig['db']['tablepre']}upload` WHERE `id`='{$pic_id}'";
        $pic_row = $wdb->get_row($sql);
        $pic     = isset($pic_row['filepath']) ? $pic_row['filepath'] : '';
    }
    
    $sql = "INSERT INTO `{$wconfig['db']['tablepre']}article_category` (`pid`, `name`, `status`, `pic`, `displayorder`, `seotitle`, `seokeywords`, `seodescription`) VALUES ('{$pid}', '{$name}', '{$status}', '{$pic}', '{$displayorder}', '{$seotitle}', '{$seokeywords}', '{$seodescription}')";
    if( $wdb->query($sql) ) {
        if( !empty($pic) && is_file($pic) ) {
            $sql = "DELETE FROM `{$wconfig['db']['tablepre']}upload` WHERE `id`='{$pic_id}'";
            $wdb->query($sql);
        }
        $wuser->actionlog("添加文章分类：". $name);
        exit('{"status":1, "msg":"操作成功"}');
    } else {
        exit('{"status":0, "msg":"操作失败"}');
    }
    
}
