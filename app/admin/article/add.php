<?php
/**
 * 文章添加
 */

if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}

if( !isset($_POST['title']) ) {
    
    /**
     * 文章添加-页面
     */
    include W_ROOT_PATH . '/lib/w_extend.func.php';
    $sql        = "SELECT `cid`, `pid`, `name`, `status`, `displayorder` FROM `{$wconfig['db']['tablepre']}article_category` WHERE `status`='1'";
    $categories = $wdb->get_all($sql);
    $categories = w_category_tree_html($categories);
    include $wconfig['theme_path'] . '/admin/article/add.html.php';
    
} else {
    
    /**
     * 文章添加-数据入库
     */
    
    // 安全验证
    $formtoken = isset($_POST['formtoken']) ? trim($_POST['formtoken']) : '';
    if( $formtoken != $wuser->formtoken ) {
        exit('{"status":0, "msg":"非法操作"}');
    }
    
    // 接收数据
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
    
    // 输入检查
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
    
    // 文章同名检查...
    
    // 如果存在图片封面上传，则处理图片
    $pic = '';
    if( $pic_id > 0 ) {
        $sql     = "SELECT `id`, `uid`, `filepath` FROM `{$wconfig['db']['tablepre']}upload` WHERE `id`='{$pic_id}'";
        $pic_row = $wdb->get_row($sql);
        //$pic     = isset($pic_row['filepath']) ? str_replace('upload/'. $m .'/', '', $pic_row['filepath']) : '';
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
    
    // 附件路径替换
    $content = str_replace('src=\"'. $sitepath .'/data/tmp/ueditor', 'src=\"'. $sitepath .'/upload/'. $m .'/ueditor', $content);
    $content = str_replace('href=\"'. $sitepath .'/data/tmp/ueditor', 'href=\"'. $sitepath .'/upload/'. $m .'/ueditor', $content);
    
    // 保存数据
    $sql = "INSERT INTO `{$wconfig['db']['tablepre']}article` (`cid`, `status`, `displayorder`, `title`, `subtitle`, `keywords`, `description`, `pic`, `hits`, `hitstime`, `createtime`, `updatetime`, `jumpurl`, `source`, `sourceurl`, `editor`) VALUES ('{$cid}', '{$status}', '{$displayorder}', '{$title}', '{$subtitle}', '{$keywords}', '{$description}', '{$pic}', '0', '0', '". W_TIMESTAMP ."', '". W_TIMESTAMP ."', '{$jumpurl}', '{$source}', '{$sourceurl}', '{$wuser->username}')";
    if( $wdb->query($sql) ) {
        // 获取insert_id
        $insert_id = $wdb->insert_id;
        // 内容保存
        $sql = "INSERT INTO `{$wconfig['db']['tablepre']}article_content` (`aid`, `content`) VALUES ('{$insert_id}', '{$content}')";
        $wdb->query($sql);
        // [!]如果上传了封面图，就删除 w_upload 表对应的记录(这个功能可有可无)
        if( !empty($pic) && is_file($pic) ) {
            $sql = "DELETE FROM `{$wconfig['db']['tablepre']}upload` WHERE `id`='{$pic_id}'";
            $wdb->query($sql);
        }
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
                    $sql = "INSERT INTO `{$wconfig['db']['tablepre']}article_attachment` (`aid`, `uid`, `attachment`) VALUES ('{$insert_id}', '{$wuser->uid}', '{$val}')";
                    $wdb->query($sql);
                }
            }
        }
        // 增加管理记录
        $wuser->actionlog("添加了文章：". $title);
        // 返回成功消息
        exit('{"status":1, "msg":"操作成功"}');
    } else {
        exit('{"status":0, "msg":"操作失败"}');
    }
    
}
