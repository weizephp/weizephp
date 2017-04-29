<?php
/**
 * 导航菜单添加
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

if( !isset($_POST['target']) ) {
    
    /**
     * 导航菜单添加-页面
     */
        
    $sql = "SELECT `nid`, `pid`, `level`, `name`, `status` FROM `{$wconfig['db']['tablepre']}nav` WHERE `status`='1' ORDER BY `listorder` ASC, `nid` ASC";
    $nav_arr = $wdb->get_all($sql);
    
    include W_ROOT_PATH . '/app/admin/nav/nav.inc.php';
    $nav_list = set_nav_level($nav_arr);
    unset($nav_arr);
    
    include $wconfig['theme_path'] . '/admin/nav/add.html.php';
    
} else {
    
    /**
     * 导航菜单添加-保存数据
     */
    $formtoken = isset($_POST['formtoken']) ? trim($_POST['formtoken']) : '';
    if( $formtoken != $wuser->formtoken ) {
        exit('{"status":0, "msg":"非法操作"}');
    }
    
    $pid         = isset($_POST['pid']) ? intval($_POST['pid']) : 0;
    $name        = isset($_POST['name']) ? addslashes(trim($_POST['name'])) : '';
    $status      = isset($_POST['status']) ? intval($_POST['status']) : 0;
    $listorder   = isset($_POST['listorder']) ? intval($_POST['listorder']) : 0;
    $internal    = isset($_POST['internal']) ? intval($_POST['internal']) : 0;
    $url         = isset($_POST['url']) ? addslashes(trim($_POST['url'])) : '';
    $target      = isset($_POST['target']) ? intval($_POST['target']) : 0;
    $pic_id      = isset($_POST['pic_id']) ? intval($_POST['pic_id']) : 0;
    $description = isset($_POST['description']) ? addslashes(trim($_POST['description'])) : '';
    
    if( empty($name) ) {
        exit('{"status":0, "msg":"名称不能为空"}');
    }
    if( empty($url) ) {
        exit('{"status":0, "msg":"导航链接不能为空"}');
    }
    
    // 同名检查...
    
    $pic = '';
    if( $pic_id > 0 ) {
        $sql     = "SELECT `id`, `uid`, `filepath` FROM `{$wconfig['db']['tablepre']}upload` WHERE `id`='{$pic_id}'";
        $pic_row = $wdb->get_row($sql);
        $pic     = isset($pic_row['filepath']) ? $pic_row['filepath'] : '';
    }
    
    $level = 1;
    
    if( $pid > 0 ) {
        $sql = $sql = "SELECT `nid`, `pid`, `level`, `name` FROM `{$wconfig['db']['tablepre']}nav` WHERE `nid`='{$pid}'";
        $parent = $wdb->get_row($sql);
        // 目前只支持3级
        if( !empty($parent) ) {
            if( $parent['level'] < 3 ) {
                $level = $parent['level'] + 1;
            } else {
                $pid   = $parent['pid'];
                $level = $parent['level'];
            }
        }
    }
    if($level > 3) {
        exit('{"status":0, "msg":"目前只支持3级"}');
    }
    
    $sql = "INSERT INTO `{$wconfig['db']['tablepre']}nav` (`pid`, `level`, `name`, `status`, `listorder`, `internal`, `url`, `target`, `pic`, `description`) VALUES ('{$pid}', '{$level}', '{$name}', '{$status}', '{$listorder}', '{$internal}', '{$url}', '{$target}', '{$pic}', '{$description}')";
    if( $wdb->query($sql) ) {
        // [!]
        if( !empty($pic) && is_file($pic) ) {
            $sql = "DELETE FROM `{$wconfig['db']['tablepre']}upload` WHERE `id`='{$pic_id}'";
            $wdb->query($sql);
        }
        $wuser->actionlog("添加了导航：". $name);
        exit('{"status":1, "msg":"操作成功"}');
    } else {
        exit('{"status":0, "msg":"操作失败"}');
    }
    
}
