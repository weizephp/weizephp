<?php
/**
 * 文章更新
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

if( !isset($_POST['title']) ) {
    
    /**
     * 文章更新-页面
     */
    
    // 接收参数
    $aid = isset($_GET['aid']) ? intval($_GET['aid']) : 0;
    if( $aid < 1 ) {
        w_error('错误，非法索引ID');
    }
    
    // 载入扩展函数
    include W_ROOT_PATH . '/lib/w_extend.func.php';
    
    // 载入图像处理类
    include W_ROOT_PATH . '/lib/w_image.class.php';
    
    // 获取分类树
    $sql        = "SELECT `cid`, `pid`, `name`, `status`, `displayorder` FROM `{$wconfig['db']['tablepre']}article_category` WHERE `status`='1'";
    $categories = $wdb->get_all($sql);
    $categories = w_category_tree_html($categories);
    
    // 获取文章
    $sql = "SELECT * FROM `{$wconfig['db']['tablepre']}article` WHERE `aid`='{$aid}'";
    $row = $wdb->get_row($sql);
    if( empty($row) ) {
        w_error('抱歉，找不到文章，或者文章已经被删除');
    }
    
    // 获取内容
    $sql     = "SELECT * FROM `{$wconfig['db']['tablepre']}article_content` WHERE `aid`='{$aid}'";
    $content = $wdb->get_row($sql);
    $row['content'] = $content['content'];
    unset($content);
    
    // htmlspecialchars安全处理
    foreach( $row as $k => $v ) {
        if( $k != 'content' ) {
            $row[$k] = htmlspecialchars($v);
        }
    }
    
    // 封面图地址处理
    $pic = '';
    if( !empty($row['pic']) ) {
        $pic = w_image::thumbcache('./' . $row['pic'], 100, 100);
    }
    
    // 返回上一页URL定义
    $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '?m=article&a=list';
    
    // 包含模板
    include $wconfig['theme_path'] . '/admin/article/update.html.php';
    
} else {
    
    /**
     * 文章更新-数据入库
     */
    
    // 安全验证
    $formtoken = isset($_POST['formtoken']) ? trim($_POST['formtoken']) : '';
    if( $formtoken != $wuser->formtoken ) {
        exit('{"status":0, "msg":"非法操作"}');
    }
    
    // 接收数据
    $aid          = isset($_POST['aid']) ? addslashes(trim($_POST['aid'])) : '';
    
    $title        = isset($_POST['title']) ? addslashes(trim($_POST['title'])) : '';
    $subtitle     = isset($_POST['subtitle']) ? addslashes(trim($_POST['subtitle'])) : '';
    
    $cid          = isset($_POST['cid']) ? intval($_POST['cid']) : 0;
    $status       = isset($_POST['status']) ? intval($_POST['status']) : 1;
    $displayorder = isset($_POST['displayorder']) ? intval($_POST['displayorder']) : 10;
    
    $pic_id       = isset($_POST['pic_id']) ? intval($_POST['pic_id']) : 0;
    
    $jumpurl      = isset($_POST['jumpurl']) ? addslashes(trim($_POST['jumpurl'])) : '';
    $source       = isset($_POST['source']) ? addslashes(trim($_POST['source'])) : '';
    $sourceurl    = isset($_POST['sourceurl']) ? addslashes(trim($_POST['sourceurl'])) : '';
    $keywords     = isset($_POST['keywords']) ? addslashes(trim($_POST['keywords'])) : '';
    $description  = isset($_POST['description']) ? addslashes(trim($_POST['description'])) : '';
    $content      = isset($_POST['content']) ? addslashes(trim($_POST['content'])) : '';
    
    $pic_delete   = isset($_POST['pic_delete']) && ($_POST['pic_delete'] == 'yes') ? 'yes' : 'no';
    
    // 输入检查
    if( $aid < 1 ) {
        exit('{"status":0, "msg":"错误，非法索引ID"}');
    }
    
    if( empty($title) ) {
        exit('{"status":0, "msg":"标题不能为空"}');
    }
    if( $cid < 1 ) {
        exit('{"status":0, "msg":"分类必须"}');
    }
    if( empty($description) ) {
        exit('{"status":0, "msg":"简介不能为空"}');
    }
    if( empty($content) ) {
        exit('{"status":0, "msg":"内容不能为空"}');
    }
    
    // 读取旧数据
    $sql = "SELECT `aid`, `cid`, `status`, `title`, `pic` FROM `{$wconfig['db']['tablepre']}article` WHERE `aid`='{$aid}'";
    $old = $wdb->get_row($sql);
    if( empty($old) ) {
        exit('{"status":0, "msg":"抱歉，找不到文章，或者文章已经被删除，无需操作"}');
    }
    
    $sql = "SELECT * FROM `{$wconfig['db']['tablepre']}article_content` WHERE `aid`='{$aid}'";
    $old_db_content = $wdb->get_row($sql);
    
    $old['content'] = $old_db_content['content'];
    unset($old_db_content);
    
    $sql = "SELECT `id`, `aid`, `uid`, `attachment` FROM `{$wconfig['db']['tablepre']}article_attachment` WHERE `aid`='{$aid}'";
    $old['attachments'] = $wdb->get_all($sql);
    
    // 文章同名检查...
    
    // 如果存在图片封面上传，则处理图片
    $pic = '';
    if( $pic_id > 0 ) {
        $sql     = "SELECT `id`, `uid`, `filepath` FROM `{$wconfig['db']['tablepre']}upload` WHERE `id`='{$pic_id}'";
        $pic_row = $wdb->get_row($sql);
        $pic     = isset($pic_row['filepath']) ? $pic_row['filepath'] : '';
    }
    
    // 定义网站路径
    $sitepath = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'));
    
    // 定义网站路径正则模式
    $sitepath_pattern = str_ireplace('/', '\/', $sitepath);
    
    // 用正则获取编辑器上传的附件
    $attachments  = array();
    $src_matches  = array();
    $href_matches = array();
    preg_match_all('/src=\\\"'. $sitepath_pattern .'\/data\/tmp\/ueditor\/([0-9a-zA-Z.\/]+)\\\"/', $content, $src_matches);
    if( !empty($src_matches[1]) ) {
        foreach($src_matches[1] as $key => $val) {
            $attachments[] = $val;
        }
    }
    preg_match_all('/href=\\\"'. $sitepath_pattern .'\/data\/tmp\/ueditor\/([0-9a-zA-Z.\/]+)\\\"/', $content, $src_matches);
    if( !empty($href_matches[1]) ) {
        foreach($href_matches[1] as $key => $val) {
            $attachments[] = $val;
        }
    }
    
    // 用正则获取编辑器提交过来的“旧附件”
    $old_attachments  = array();
    $old_src_matches  = array();
    $old_href_matches = array();
    preg_match_all('/src=\\\"'. $sitepath_pattern .'\/upload\/'. $m .'\/ueditor\/([0-9a-zA-Z.\/]+)\\\"/', $content, $old_src_matches);
    if( !empty($old_src_matches[1]) ) {
        foreach($old_src_matches[1] as $key => $val) {
            $old_attachments[] = $val;
        }
    }
    preg_match_all('/href=\\\"'. $sitepath_pattern .'\/upload\/'. $m .'\/ueditor\/([0-9a-zA-Z.\/]+)\\\"/', $content, $old_href_matches);
    if( !empty($old_href_matches[1]) ) {
        foreach($old_href_matches[1] as $key => $val) {
            $old_attachments[] = $val;
        }
    }
    
    // 附件路径替换
    $content = str_replace('src=\"'. $sitepath .'/data/tmp/ueditor', 'src=\"'. $sitepath .'/upload/'. $m .'/ueditor', $content);
    $content = str_replace('href=\"'. $sitepath .'/data/tmp/ueditor', 'href=\"'. $sitepath .'/upload/'. $m .'/ueditor', $content);
    
    // [!]封面图处理。如果上传了封面图，就删除 w_upload 表对应的记录(这个功能可有可无)，这里是更新，需要同时删除旧图片
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
    
    // 保存数据
    $pic_sql = !empty($pic) ? "`pic`='{$pic}'," : "";
    $sql = "UPDATE `{$wconfig['db']['tablepre']}article` SET `cid`='{$cid}',`status`='{$status}',`displayorder`='{$displayorder}',`title`='{$title}',`subtitle`='{$subtitle}',`keywords`='{$keywords}',`description`='{$description}',{$pic_sql}`updatetime`='". W_TIMESTAMP ."',`jumpurl`='{$jumpurl}',`source`='{$source}',`sourceurl`='{$sourceurl}',`editor`='{$wuser->username}' WHERE `aid`='{$aid}'";
    if( $wdb->query($sql) ) {
        // 更新内容
        $sql = "UPDATE `{$wconfig['db']['tablepre']}article_content` SET `content`='{$content}' WHERE `aid`='{$aid}'";
        $wdb->query($sql);
        // [!]移动“编辑器附件”到“正式目录”
        if( !empty($attachments) ) {
            foreach($attachments as $val) {
                $tmp_filename = './data/tmp/ueditor/'. $val;
                $new_filename = './upload/'. $m .'/ueditor/'. $val;
                $new_path     = dirname($new_filename);
                if( is_file($tmp_filename) ) {
                    if( !is_dir($new_path) ) {
                        mkdir($new_path, 0777, true);
                    }
                    rename($tmp_filename, $new_filename);
                    $sql = "INSERT INTO `{$wconfig['db']['tablepre']}article_attachment` (`aid`, `uid`, `attachment`) VALUES ('{$aid}', '{$wuser->uid}', '{$val}')";
                    $wdb->query($sql);
                }
            }
        }
        // 删除没有用了的“旧附件”
        foreach($old['attachments'] as $v) {
            if( !in_array($v['attachment'], $old_attachments) ) {
                $attachment_filename = './upload/'. $m .'/ueditor/'. $v['attachment'];
                if( is_file($attachment_filename) ) {
                    $sql = "DELETE FROM `{$wconfig['db']['tablepre']}article_attachment` WHERE `id`='{$v['id']}'";
                    $wdb->query($sql);
                    unlink($attachment_filename);
                }
            }
        }
        // 增加管理记录
        if( $old['title'] != $title ) {
            $wuser->actionlog('把文章“'. $old['title'] .'”更新为：'. $title);
        } else {
            $wuser->actionlog("更新了文章：". $title);
        }
        // 返回成功消息
        exit('{"status":1, "msg":"操作成功"}');
    } else {
        exit('{"status":0, "msg":"操作失败"}');
    }
    
}
