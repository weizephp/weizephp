<?php
/**
 * 导航菜单生成
 * 说明：主要生成 ./config/config_nav.inc.php 文件，方便调用
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

$op = isset($_GET['op']) ? $_GET['op'] : 'view';

if( $op == 'do' ) {
    
    /**
     *  导航菜单生成-生成 config_nav.inc.php 配置文件
     */
    
    $formtoken = isset($_GET['formtoken']) ? trim($_GET['formtoken']) : '';
    if( $formtoken != $wuser->formtoken ) {
        exit('{"status":0, "msg":"非法操作"}');
    }
    
    //-----------------
    
    $sql = "SELECT * FROM `{$wconfig['db']['tablepre']}nav` WHERE `status`='1' ORDER BY `listorder` ASC, `nid` ASC";
    $nav_arr = $wdb->get_all($sql);
    
    //-----------------
    
    $nav_list = array();
    $level_1  = array();
    $level_2  = array();
    $level_3  = array();

    foreach($nav_arr as $val) {
        if( $val['level'] == 1 ) {
            $level_1[] = $val;
        }
        if( $val['level'] == 2 ) {
            $level_2[] = $val;
        }
        if( $val['level'] == 3 ) {
            $level_3[] = $val;
        }
    }
    
    foreach($level_2 as $key_2 => $val_2) {
        $level_2[$key_2]['children'] = array();
        foreach($level_3 as $val_3) {
            if($val_2['nid'] == $val_3['pid']) {
                $level_2[$key_2]['children'][] = $val_3;
            }
        }
    }
    
    foreach($level_1 as $key_1 => $val_1) {
        $level_1[$key_1]['children'] = array();
        foreach($level_2 as $val_2) {
            if($val_1['nid'] == $val_2['pid']) {
                $level_1[$key_1]['children'][] = $val_2;
            }
        }
    }
    
    $nav_list = $level_1;
    
    unset($nav_arr, $level_1, $level_2, $level_3);
    
    //-----------------
    
    $result = file_put_contents(W_ROOT_PATH . "/config/config_nav.inc.php", "<?php\r\n\$_nav = " . var_export($nav_list, TRUE) . ";", LOCK_EX);
    
    //-----------------
    
    if( $result !== FALSE ) {
        $wuser->actionlog("添加了导航菜单 ./config/config_nav.inc.php 文件");
        exit('{"status":1, "msg":"操作成功"}');
    } else {
        exit('{"status":0, "msg":"导航菜单 ./config/config_nav.inc.php 文件写入失败"}');
    }
    
    
} else {
    
    /**
     *  导航菜单生成-页面
     */
    
    include $wconfig['theme_path'] . '/admin/nav/generate.html.php';
    
}
