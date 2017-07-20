<?php
/**
 * 单页查看
 */

if( !defined('IN_WEIZEPHP') ) {
    exit('Access Denied');
}

$spid = isset($_GET['spid']) ? intval($_GET['spid']) : 0;
if($spid < 1) {
    w_error('非法索引ID', '?');
}

$sql = "SELECT * FROM `{$wconfig['db']['tablepre']}singlepage` WHERE `spid`='{$spid}'";
$row = $wdb->get_row($sql);
if(empty($row)) {
    w_error('没有找到内容', '?');
}

$sql = "SELECT * FROM `{$wconfig['db']['tablepre']}singlepage_content` WHERE `spid`='{$spid}'";
$content = $wdb->get_row($sql);
$row['content'] = isset($content['content']) ? $content['content'] : '';
unset($content);

foreach( $row as $key => $val ) {
    if( $key != 'content' ) {
        $row[$key] = htmlspecialchars($val);
    }
}

include $wconfig['theme_path'] . '/content/singlepage/view.html.php';
