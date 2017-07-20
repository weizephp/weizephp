<?php
/**
 * 单页查看
 */

if( !defined('IN_WEIZEPHP') ) {
    exit('Access Denied');
}

$aid = isset($_GET['aid']) ? intval($_GET['aid']) : 0;
if($aid < 1) {
    w_error('非法索引ID', '?');
}

$sql = "SELECT * FROM `{$wconfig['db']['tablepre']}article` WHERE `aid`='{$aid}'";
$row = $wdb->get_row($sql);
if(empty($row)) {
    w_error('没有找到内容', '?');
}

$sql = "SELECT * FROM `{$wconfig['db']['tablepre']}article_content` WHERE `aid`='{$aid}'";
$content = $wdb->get_row($sql);
$row['content'] = isset($content['content']) ? $content['content'] : '';
unset($content);

foreach( $row as $key => $val ) {
    if( $key != 'content' ) {
        $row[$key] = htmlspecialchars($val);
    }
}

include $wconfig['theme_path'] . '/content/article/view.html.php';
