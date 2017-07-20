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

if( !defined('IN_WEIZEPHP') ) {
    exit('Access Denied');
}

/** ------------------------------------------------------------------- */

/**
 * 递归删除“指定目录”下的“所有文件及目录”
 * 注意：很危险的函数！！！在使用该函数时，一定要先备份，再测试。否则可能会误删，我都是血和泪的教训呀！
 * 用法：w_rmdir('./data/cache');
 * @param string $dirname
 */
function w_rmdir($dirname) {
    if(substr($dirname, -1) != '/') {
        $dirname = $dirname . '/';
    }
    $d = dir($dirname);
    while(false !== ($entry = $d->read())) {
        if($entry != '.' && $entry != '..' && $entry != 'index.htm') {
            if(is_dir($dirname.$entry)) {
                w_rmdir($dirname.$entry);
                rmdir($dirname.$entry);
            } else {
                unlink($dirname.$entry);
            }
        }
    }
    $d->close();
}

/** ------------------------------------------------------------------- */

/**
 * 列出指定路径中的文件和目录。用来替代不支持 scandir 函数的低版本PHP
 * @param string $directory
 * @param string $sorting_order
 * @return array
 */
function w_scandir($directory, $sorting_order = 0) {
    if(!is_dir($directory)) {
        return array();
    }
    $files = array();
    $dh    = opendir($directory);
    while(false !== ($filename = readdir($dh))) {
        $files[] = $filename;
    }
    if($sorting_order == 0) {
        sort($files); // 升序
    } else {
        rsort($files); // 降序
    }
    return $files;
}

/** ------------------------------------------------------------------- */

/*
// 无限分类数据结构
$categories = array(
    array('cid' => 1,  'pid' => 0,  'displayorder'=>'9', 'name' => '广西壮族自治区'),
    
    array('cid' => 2,  'pid' => 1,  'displayorder'=>'2', 'name' => '柳州市'),
    array('cid' => 3,  'pid' => 2,  'displayorder'=>'9', 'name' => '融水苗族自治县'),
    array('cid' => 4,  'pid' => 3,  'displayorder'=>'9', 'name' => '杆洞乡'),
    array('cid' => 5,  'pid' => 4,  'displayorder'=>'9', 'name' => '高强村'),
    array('cid' => 6,  'pid' => 5,  'displayorder'=>'3', 'name' => '上坪屯'),
    array('cid' => 7,  'pid' => 5,  'displayorder'=>'2', 'name' => '中坪屯'),
    array('cid' => 8,  'pid' => 5,  'displayorder'=>'1', 'name' => '下坪屯'),
    
    array('cid' => 9,  'pid' => 1,  'displayorder'=>'1', 'name' => '桂林市'),
    array('cid' => 10, 'pid' => 9,  'displayorder'=>'9', 'name' => '阳朔县'),
    array('cid' => 11, 'pid' => 10, 'displayorder'=>'9', 'name' => '白沙镇'),
    array('cid' => 12, 'pid' => 11, 'displayorder'=>'2', 'name' => '古板村'),
    array('cid' => 13, 'pid' => 11, 'displayorder'=>'1', 'name' => '蔡村'),
);
*/

/**
 * [!]无限分类树
 * @param array $categories
 * @param int $pid
 * @param string $hyphen
 * @param string $nbsp
 * @return array
 */
function w_category_tree($categories, $pid = 0, $hyphen = '', $nbsp = '') {
    $trees = array();
    foreach($categories as $v) {
        if($v['pid'] == $pid) {
            $v['name_fmt'] = $nbsp . $hyphen . htmlspecialchars($v['name']);
            $v['children'] = w_category_tree($categories, $v['cid'], '└─ ', $nbsp . '&nbsp;&nbsp;&nbsp;&nbsp;');
            $trees[] = $v;
        }
    }
    return $trees;
}

/**
 * [!]无限分类树“列表”
 * @param array $categories
 * @return array
 */
function w_category_tree_list($categories) {
    static $list = array();
    foreach($categories as $v) {
        $tmp = $v;
        if(isset($tmp['children'])) {
            unset($tmp['children']);
        }
        $list[] = $tmp;
        if(!empty($v['children'])) {
            w_category_tree_list($v['children']);
        }
    }
    return $list;
}

/**
 * [!]无限分类树“HTML”（依赖w_category_tree,w_category_tree_list两个函数）
 * @param array $categories
 * @return array
 */
function w_category_tree_html($categories) {
    // 1、排序
    $displayorder = array();
    foreach($categories as $k => $v) {
        $displayorder[$k] = $v['displayorder'];
    }
    array_multisort($displayorder, SORT_ASC, $categories);
    // 2、生成分类树
    $categories = w_category_tree($categories);
    // 3、生成分类树列表
    $categories = w_category_tree_list($categories);
    // 4、返回最后结果
    return $categories;
}

/** ------------------------------------------------------------------- */
