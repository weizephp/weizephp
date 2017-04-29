<?php
/**
 * 文章分类更新
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

if( !isset($_POST['name']) ) {
    
    /**
     * 文章分类更新-页面
     */
    include W_ROOT_PATH . '/lib/w_extend.func.php';
    include W_ROOT_PATH . '/lib/w_image.class.php';
    
    $cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
    
    $sql        = "SELECT * FROM `{$wconfig['db']['tablepre']}article_category` WHERE 1";
    $categories = $wdb->get_all($sql);
    
    $row = array();
    foreach($categories as $v) {
        if($v['cid'] == $cid) {
            $row = $v;
            break;
        }
    }
    
    if( empty($row) ) {
        w_error("没有该分类，请返回上一页");
    }
    
    foreach($row as $k => $v) {
        $row[$k] = htmlspecialchars($v);
    }
    
    $categories = w_category_tree_html($categories);
    
    foreach($categories as $k => $v) {
        if($v['cid'] == $cid) {
            unset($categories[$k]);
            break;
        }
    }
    
    $pic = '';
    if( !empty($row['pic']) ) {
        $pic = w_image::thumbcache('./' . $row['pic'], 100, 100);
    }
    
    include $wconfig['theme_path'] . '/admin/article/categoryupdate.html.php';
    
} else {
    
    /**
     * 文章分类更新-数据入库
     */
    $formtoken = isset($_POST['formtoken']) ? trim($_POST['formtoken']) : '';
    if( $formtoken != $wuser->formtoken ) {
        exit('{"status":0, "msg":"非法操作"}');
    }
    
    $cid            = isset($_POST['cid'])            ? intval($_POST['cid']) : 0;
    $pid            = isset($_POST['pid'])            ? intval($_POST['pid']) : 0;
    $name           = isset($_POST['name'])           ? addslashes(trim($_POST['name'])) : '';
    $status         = isset($_POST['status'])         ? intval($_POST['status']) : 0;
    $pic_id         = isset($_POST['pic_id'])         ? intval($_POST['pic_id']) : 0;
    $displayorder   = isset($_POST['displayorder'])   ? intval($_POST['displayorder']) : 1;
    $seotitle       = isset($_POST['seotitle'])       ? addslashes(trim($_POST['seotitle'])) : '';
    $seokeywords    = isset($_POST['seokeywords'])    ? addslashes(trim($_POST['seokeywords'])) : '';
    $seodescription = isset($_POST['seodescription']) ? addslashes(trim($_POST['seodescription'])) : '';
    
    $pic_delete     = isset($_POST['pic_delete']) && ($_POST['pic_delete'] == 'yes') ? 'yes' : 'no';
    
    if( empty($name) ) {
        exit('{"status":0, "msg":"分类名称不能为空"}');
    }
    if( $pid == $cid ) {
        exit('{"status":0, "msg":"上级分类不能选择自身"}');
    }
    
    $sql = "SELECT `cid`, `pid`, `name`, `pic` FROM `{$wconfig['db']['tablepre']}article_category` WHERE `cid`='{$cid}'";
    $old = $wdb->get_row($sql);
    
    $sql = "SELECT `cid`, `pid` FROM `{$wconfig['db']['tablepre']}article_category` WHERE `cid`<>'{$cid}' AND `name`='{$name}'";
    $row = $wdb->get_row($sql);
    if( !empty($row) ) {
        exit('{"status":0, "msg":"存在同名"}');
    }
    
    $pic = '';
    if( $pic_id > 0 ) {
        $sql     = "SELECT `id`, `uid`, `filepath` FROM `{$wconfig['db']['tablepre']}upload` WHERE `id`='{$pic_id}'";
        $pic_row = $wdb->get_row($sql);
        $pic     = isset($pic_row['filepath']) ? $pic_row['filepath'] : '';
    }
    
    $pic_sql = "";
    if( !empty($pic) && is_file($pic) ) {
        $pic_sql = "`pic`='{$pic}',";
        $sql = "DELETE FROM `{$wconfig['db']['tablepre']}upload` WHERE `id`='{$pic_id}'";
        $wdb->query($sql);
        if( !empty($old['pic']) && is_file($old['pic']) ) {
            unlink($old['pic']);
        }
    } else {
        if( ($pic_delete == 'yes') && is_file($old['pic']) ) {
            $pic_sql = "`pic`='',";
            unlink($old['pic']);
        } else {
            $pic_sql = "";
        }
    }
    
    $sql = "UPDATE `{$wconfig['db']['tablepre']}article_category` SET `pid`='{$pid}',`name`='{$name}',`status`='{$status}',{$pic_sql}`displayorder`='{$displayorder}',`seotitle`='{$seotitle}',`seokeywords`='{$seokeywords}',`seodescription`='{$seodescription}' WHERE `cid`='{$cid}'";
    if( $wdb->query($sql) ) {
        if( $old['name'] != $name ) {
            $wuser->actionlog('把文章分类“'. $old['name'] .'”更新为：'. $name);
        } else {
            $wuser->actionlog('更新了文章分类：'. $name);
        }
        exit('{"status":1, "msg":"更新成功"}');
    } else {
        exit('{"status":0, "msg":"更新失败"}');
    }
    
}
