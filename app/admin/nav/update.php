<?php
/**
 * 导航菜单更新
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

if( !isset($_POST['target']) ) {
    
    /**
     * 导航菜单更新-页面
     */
    
    // 接收参数
    $nid = isset($_GET['nid']) ? intval($_GET['nid']) : 0;

    // 获取当前导航菜单
    $sql = "SELECT * FROM `{$wconfig['db']['tablepre']}nav` WHERE `nid`='{$nid}'";
    $nav = $wdb->get_row($sql);
    if( empty($nav) ) {
        w_error('获取的导航菜单为空');
    }

    // 包含图像处理类
    include W_ROOT_PATH . '/lib/w_image.class.php';

    // 封面图地址
    $pic = '';
    if( !empty($nav['pic']) ) {
        $pic = w_image::thumbcache('./' . $nav['pic'], 100, 100);
    }

    // 获取所有导航菜单
    $sql = "SELECT `nid`, `pid`, `level`, `name`, `status` FROM `{$wconfig['db']['tablepre']}nav` WHERE `nid`<>'{$nid}' AND `status`='1' ORDER BY `listorder` ASC, `nid` ASC";
    $nav_arr = $wdb->get_all($sql);

    include W_ROOT_PATH . '/app/admin/nav/nav.inc.php';
    $nav_list = set_nav_level($nav_arr);
    unset($nav_arr);

    // 包含模板
    include $wconfig['theme_path'] . '/admin/nav/update.html.php';
    
} else {
    
    /**
     * 导航菜单更新-保存数据
     */
    
    $formtoken = isset($_POST['formtoken']) ? trim($_POST['formtoken']) : '';
    if( $formtoken != $wuser->formtoken ) {
        exit('{"status":0, "msg":"非法操作"}');
    }
    
    $nid         = isset($_POST['nid']) ? intval($_POST['nid']) : 0;
    
    $pid         = isset($_POST['pid']) ? intval($_POST['pid']) : 0;
    $name        = isset($_POST['name']) ? addslashes(trim($_POST['name'])) : '';
    $status      = isset($_POST['status']) ? intval($_POST['status']) : 0;
    $listorder   = isset($_POST['listorder']) ? intval($_POST['listorder']) : 0;
    $internal    = isset($_POST['internal']) ? intval($_POST['internal']) : 0;
    $url         = isset($_POST['url']) ? addslashes(trim($_POST['url'])) : '';
    $target      = isset($_POST['target']) ? intval($_POST['target']) : 0;
    $pic_id      = isset($_POST['pic_id']) ? intval($_POST['pic_id']) : 0;
    $description = isset($_POST['description']) ? addslashes(trim($_POST['description'])) : '';
    
    $pic_delete  = isset($_POST['pic_delete']) && ($_POST['pic_delete'] == 'yes') ? 'yes' : 'no';
    
    if( $nid < 1 ) {
        exit('{"status":0, "msg":"错误，非法索引ID"}');
    }
    if( empty($name) ) {
        exit('{"status":0, "msg":"名称不能为空"}');
    }
    if( empty($url) ) {
        exit('{"status":0, "msg":"导航链接不能为空"}');
    }
    
    $sql = "SELECT * FROM `{$wconfig['db']['tablepre']}nav` WHERE `nid`='{$nid}'";
    $old = $wdb->get_row($sql);
    if( empty($old) ) {
        exit('{"status":0, "msg":"抱歉，内容找不到或者已经删除，无需操作"}');
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
    
    $pic_sql = "";
    if( !empty($pic) && is_file($pic) ) {
        $pic_sql = "`pic`='{$pic}',";
        // [!]
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
    
    $sql = "UPDATE `{$wconfig['db']['tablepre']}nav` SET `pid`='{$pid}',`level`='{$level}',`name`='{$name}',`status`='{$status}',`listorder`='{$listorder}',`internal`='{$internal}',`url`='{$url}',`target`='{$target}',{$pic_sql}`description`='{$description}' WHERE `nid`='{$nid}'";
    if( $wdb->query($sql) ) {
        if( $old['name'] != $name ) {
            $wuser->actionlog('把导航“'. $old['name'] .'”更新为：'. $name);
        } else {
            $wuser->actionlog("更新了导航：". $name);
        }
        exit('{"status":1, "msg":"操作成功"}');
    } else {
        exit('{"status":0, "msg":"操作失败"}');
    }
    
}
