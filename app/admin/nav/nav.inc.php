<?php
/**
 * 导航菜单-公用函数
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

/**
 * 设置导航层级
 * @param array
 * @return array
 */
function set_nav_level($nav_arr) {
    if( empty($nav_arr) ) {
        return array();
    }
    
    $nav_list = array();
    $level_1  = array();
    $level_2  = array();
    $level_3  = array();

    foreach($nav_arr as $val) {
        $val['name'] = htmlspecialchars($val['name']);
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

    foreach($level_1 as $key => $val_1) {
        $nav_list[] = $val_1;
        foreach($level_2 as $val_2) {
            if( $val_1['nid'] == $val_2['pid'] ) {
                $val_2['name'] = '&nbsp;&nbsp;&nbsp;&nbsp;└─ ' . $val_2['name'];
                $nav_list[] = $val_2;
                foreach($level_3 as $val_3) {
                    if( $val_2['nid'] == $val_3['pid'] ) {
                        $val_3['name'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└─ ' . $val_3['name'];
                        $nav_list[] = $val_3;
                    }
                }
            }
        }
    }
    
    unset($nav_arr, $level_1, $level_2, $level_3);
    
    return $nav_list;
}
