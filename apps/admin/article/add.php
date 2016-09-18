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

if(!defined('IN_WEIZEPHP')){exit('Access Denied');}

if(!isset($_POST['title'])) {
    
    /**
     * 文章添加 - 页面
     */
    include 'includes/w_extend.func.php';
    $formtoken  = w_security::set_formtoken();
    $sql        = "SELECT `categoryid`, `pid`, `categoryname`, `status`, `displayorder` FROM `{$_W['db']['tablepre']}articlescategories`";
    $categories = $WDB->get_all($sql);
    $categories = w_category_tree_html($categories);
    include $_W['template_path'].'/article/add.html.php';
    
} else {
    
    /**
     * 文章添加 - 数据保存
     */
    
    // 1、formtoken验证
    $result = w_security::check_formtoken($_POST['formtoken']);
    if($result == false) {
        exit('{"status":0, "msg":"非法操作"}');
    }
    
    // 2、接收数据
    $title        = isset($_POST['title']) ? addslashes(trim($_POST['title'])) : '';
    $subtitle     = isset($_POST['subtitle']) ? addslashes(trim($_POST['subtitle'])) : '';
    
    $categoryid   = isset($_POST['categoryid']) ? intval($_POST['categoryid']) : 0;
    $status       = isset($_POST['status']) ? intval($_POST['status']) : 1;
    $displayorder = isset($_POST['displayorder']) ? intval($_POST['displayorder']) : 10;
    
    $pic_id       = isset($_POST['pic_id']) ? intval($_POST['pic_id']) : 0;
    
    $jumpurl      = isset($_POST['jumpurl']) ? addslashes(trim($_POST['jumpurl'])) : '';
    $source       = isset($_POST['source']) ? addslashes(trim($_POST['source'])) : '';
    $sourceurl    = isset($_POST['sourceurl']) ? addslashes(trim($_POST['sourceurl'])) : '';
    $keywords     = isset($_POST['keywords']) ? addslashes(trim($_POST['keywords'])) : '';
    $description  = isset($_POST['description']) ? addslashes(trim($_POST['description'])) : '';
    $content      = isset($_POST['content']) ? addslashes(trim($_POST['content'])) : '';
    
    // 3、输入检查
    if(empty($title)) {
        exit('{"status":0, "msg":"标题不能为空"}');
    }
    if($categoryid < 1) {
        exit('{"status":0, "msg":"分类必须"}');
    }
    if(empty($description)) {
        exit('{"status":0, "msg":"简介不能为空"}');
    }
    if(empty($content)) {
        exit('{"status":0, "msg":"内容不能为空"}');
    }
    
    // 4、同名检查...
    
    // 5、如果存在图片上传操作，处理图片
    $pic = '';
    if($pic_id > 0) {
        $sql     = "SELECT `id`, `uid`, `filepath` FROM `{$_W['db']['tablepre']}upload` WHERE `id`='{$pic_id}'";
        $pic_row = $WDB->get_row($sql);
        $pic     = isset($pic_row['filepath']) ? str_replace('data/attachments/'. $_W['module'] .'/', '', $pic_row['filepath']) : '';
    }
    
    // 6、$pattern_script_dir 反斜线处理
    $pattern_script_dir = str_ireplace('/', '\/', $_W['script_dir']);
    
    // 7、用正则获取编辑器附件
    $attachments = array();
    
    $src_matches = array();
    preg_match_all('/src=\\\"'. $pattern_script_dir .'data\/temporaries\/ueditor\/([a-z]+\/[0-9]+\/[0-9]+.[a-zA-z0-9]+)\\\"/', $content, $src_matches);
    if(!empty($src_matches[1])) {
        foreach($src_matches[1] as $k => $v) {
            $attachments[] = $v;
        }
    }
    
    $href_matches = array();
    preg_match_all('/href=\\\"'. $pattern_script_dir .'data\/temporaries\/ueditor\/([a-z]+\/[0-9]+\/[0-9]+.[a-zA-z0-9]+)\\\"/', $content, $href_matches);
    if(!empty($href_matches[1])) {
        foreach($href_matches[1] as $k => $v) {
            $attachments[] = $v;
        }
    }
    
    // 8、附件路径替换
    $content = str_replace('src=\"'. $_W['script_dir'] .'data/temporaries/ueditor', 'src=\"data/attachments/'. $_W['module'] .'/ueditor', $content);
    $content = str_replace('href=\"'. $_W['script_dir'] .'data/temporaries/ueditor', 'href=\"data/attachments/'. $_W['module'] .'/ueditor', $content);
    $content = str_replace('src=\"'. $_W['siteurl'] .'public/ueditor/dialogs/emotion/images', 'src=\"public/ueditor/dialogs/emotion/images', $content); // 表情
    $content = str_replace('src=\"'. $_W['siteurl'] .'public/ueditor/dialogs/attachment/fileTypeImages', 'src=\"public/ueditor/dialogs/attachment/fileTypeImages', $content); // 附件图标
    
    // 9、把数据写入数据库
    $sql = "INSERT INTO `{$_W['db']['tablepre']}articles`(`categoryid`, `status`, `displayorder`, `title`, `subtitle`, `keywords`, `description`, `pic`, `hits`, `hitstime`, `createtime`, `updatetime`, `jumpurl`, `source`, `sourceurl`, `editor`) VALUES ('{$categoryid}', '{$status}', '{$displayorder}', '{$title}', '{$subtitle}', '{$keywords}', '{$description}', '{$pic}', '0', '0', '".W_TIMESTAMP."', '".W_TIMESTAMP."', '{$jumpurl}', '{$source}', '{$sourceurl}', '{$_W['user']['username']}')";
    if($WDB->query($sql)) {
        // 获取insert_id
        $insert_id = $WDB->insert_id;
        
        // 保存内容
        $sql = "INSERT INTO `{$_W['db']['tablepre']}articlescontents`(`aid`, `content`) VALUES ('{$insert_id}', '{$content}')";
        $WDB->query($sql);
        
        // 如果上传了图片，就删除 w_upload 表对应的记录
        if(!empty($pic) && is_file($pic)) {
            $sql = "DELETE FROM `{$_W['db']['tablepre']}upload` WHERE `id`='{$pic_id}'";
            $WDB->query($sql);
        }
        
        // 移动编辑器附件到正式目录
        if(!empty($attachments)) {
            foreach($attachments as $v) {
                $tmp_filename = './data/temporaries/ueditor/'. $v;
                $new_filename = './data/attachments/'. $_W['module'] .'/ueditor/'. $v;
                $new_path     = dirname($new_filename);
                if(is_file($tmp_filename)) {
                    if(!is_dir($new_path)) {
                        mkdir($new_path, 0777, TRUE);
                    }
                    rename($tmp_filename, $new_filename);
                    $sql = "INSERT INTO `{$_W['db']['tablepre']}articlesattachments`(`aid`, `uid`, `attachment`) VALUES ('{$insert_id}', '{$_W['user']['uid']}', '{$v}')";
                    $WDB->query($sql);
                }
            }
        }
        
        //[*]为了安全，需要重置formtoken
        $formtoken  = w_security::set_formtoken();
        
        // 增加管理记录
        w_adminlog("添加了文章：". $title);
        
        // 返回成功结果
        exit('{"status":1, "msg":"操作成功"}');
    } else {
        exit('{"status":0, "msg":"操作失败"}');
    }
    
}
