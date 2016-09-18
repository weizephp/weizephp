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
     * 文章更新 - 页面
     */
    
    // 接收参数
    $spid = isset($_GET['spid']) ? intval($_GET['spid']) : 0;
    if($spid < 1) {
        w_errormessage('错误，非法索引ID');
    }
    
    // 返回上一页的重定向URL
    $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '?m=singlepage&a=list';
    
    // 载入扩展函数
    include 'includes/w_extend.func.php';
    
    // 设置formtoken
    $formtoken  = w_security::set_formtoken();
    
    // 获取数据
    $sql = "SELECT * FROM `{$_W['db']['tablepre']}singlepage` WHERE `spid`='{$spid}'";
    $row = $WDB->get_row($sql);
    
    if(empty($row)) {
        w_errormessage('抱歉，找不到文章，或者文章已经被删除');
    }
    
    // 获取内容
    $sql     = "SELECT * FROM `{$_W['db']['tablepre']}singlepagecontent` WHERE `spid`='{$spid}'";
    $content = $WDB->get_row($sql);
    
    $row['content'] = $content['content'];
    unset($content);
    
    // htmlspecialchars安全处理
    foreach($row as $k => $v) {
        if($k != 'content') {
            $row[$k] = htmlspecialchars($v);
        }
    }
    
    // 编辑器图片路径输出替换
    $row['content'] = str_replace('src="data/attachments/'. $_W['module'] .'/ueditor', 'src="'. $_W['script_dir'] .'data/attachments/'. $_W['module'] .'/ueditor', $row['content']);
    $row['content'] = str_replace('href="data/attachments/'. $_W['module'] .'/ueditor', 'href="'. $_W['script_dir'] .'data/attachments/'. $_W['module'] .'/ueditor', $row['content']);
    $row['content'] = str_replace('src="public/ueditor/dialogs/emotion/images', 'src="'. $_W['siteurl'] .'public/ueditor/dialogs/emotion/images', $row['content']); // 表情
    $row['content'] = str_replace('src="public/ueditor/dialogs/attachment/fileTypeImages', 'src="'. $_W['siteurl'] .'public/ueditor/dialogs/attachment/fileTypeImages', $row['content']); // 附件图标
    
    // 载入模板
    include $_W['template_path'].'/singlepage/update.html.php';
    
} else {
    
    /**
     * 文章更新 - 数据保存
     */
    
    // 1、formtoken验证
    $result = w_security::check_formtoken($_POST['formtoken']);
    if($result == false) {
        exit('{"status":0, "msg":"非法操作"}');
    }
    
    // 2、接收数据
    $spid         = isset($_POST['spid']) ? addslashes(trim($_POST['spid'])) : '';
    $title        = isset($_POST['title']) ? addslashes(trim($_POST['title'])) : '';
    $subtitle     = isset($_POST['subtitle']) ? addslashes(trim($_POST['subtitle'])) : '';
    
    $status       = isset($_POST['status']) ? intval($_POST['status']) : 1;
    $displayorder = isset($_POST['displayorder']) ? intval($_POST['displayorder']) : 10;
    
    $pic_id       = isset($_POST['pic_id']) ? intval($_POST['pic_id']) : 0;
    
    $jumpurl      = isset($_POST['jumpurl']) ? addslashes(trim($_POST['jumpurl'])) : '';
    $keywords     = isset($_POST['keywords']) ? addslashes(trim($_POST['keywords'])) : '';
    $description  = isset($_POST['description']) ? addslashes(trim($_POST['description'])) : '';
    $content      = isset($_POST['content']) ? addslashes(trim($_POST['content'])) : '';
    
    // 3、输入检查
    if($spid < 1) {
        w_errormessage('错误，非法索引ID');
    }
    if(empty($title)) {
        exit('{"status":0, "msg":"标题不能为空"}');
    }
    if(empty($description)) {
        exit('{"status":0, "msg":"简介不能为空"}');
    }
    if(empty($content)) {
        exit('{"status":0, "msg":"内容不能为空"}');
    }
    
    // 4、读取旧数据
    $sql = "SELECT `spid`, `status`, `title`, `pic` FROM `{$_W['db']['tablepre']}singlepage` WHERE `spid`='{$spid}'";
    $old = $WDB->get_row($sql);
    if(empty($old)) {
        exit('{"status":0, "msg":"抱歉，找不到文章，或者文章已经被删除，无需操作"}');
    }
    
    $sql = "SELECT * FROM `{$_W['db']['tablepre']}singlepagecontent` WHERE `spid`='{$spid}'";
    $old_db_content = $WDB->get_row($sql);
    
    $old['content'] = $old_db_content['content'];
    unset($old_db_content);
    
    $sql = "SELECT `id`, `spid`, `uid`, `attachment` FROM `{$_W['db']['tablepre']}singlepageattachment` WHERE `spid`='{$spid}'";
    $old['attachments'] = $WDB->get_all($sql);
    
    // 5、同名检查...
    
    // 6、如果存在图片上传操作，处理图片
    $pic = '';
    if($pic_id > 0) {
        $sql     = "SELECT `id`, `uid`, `filepath` FROM `{$_W['db']['tablepre']}upload` WHERE `id`='{$pic_id}'";
        $pic_row = $WDB->get_row($sql);
        $pic     = isset($pic_row['filepath']) ? str_replace('data/attachments/'. $_W['module'] .'/', '', $pic_row['filepath']) : '';
    }
    
    // 7、pattern_script_dir 反斜线处理
    $pattern_script_dir = str_ireplace('/', '\/', $_W['script_dir']);
    
    // 8、用正则获取编辑器提交过来的“新附件”
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
    
    // 9、用正则获取编辑器提交过来的“旧附件”
    $old_attachments = array();
    
    $src_matches = array();
    preg_match_all('/src=\\\"'. $pattern_script_dir .'data\/attachments\/'. $_W['module'] .'\/ueditor\/([a-z]+\/[0-9]+\/[0-9]+.[a-zA-z0-9]+)\\\"/', $content, $src_matches);
    if(!empty($src_matches[1])) {
        foreach($src_matches[1] as $k => $v) {
            $old_attachments[] = $v;
        }
    }
    
    $href_matches = array();
    preg_match_all('/href=\\\"'. $pattern_script_dir .'data\/attachments\/'. $_W['module'] .'\/ueditor\/([a-z]+\/[0-9]+\/[0-9]+.[a-zA-z0-9]+)\\\"/', $content, $href_matches);
    if(!empty($href_matches[1])) {
        foreach($href_matches[1] as $k => $v) {
            $old_attachments[] = $v;
        }
    }
    
    // 10、附件路径替换
    $content = str_replace('src=\"'. $_W['script_dir'] .'data/temporaries/ueditor', 'src=\"data/attachments/'. $_W['module'] .'/ueditor', $content);
    $content = str_replace('href=\"'. $_W['script_dir'] .'data/temporaries/ueditor', 'href=\"data/attachments/'. $_W['module'] .'/ueditor', $content);
    $content = str_replace('src=\"'. $_W['siteurl'] .'public/ueditor/dialogs/emotion/images', 'src=\"public/ueditor/dialogs/emotion/images', $content); // 表情
    $content = str_replace('src=\"'. $_W['siteurl'] .'public/ueditor/dialogs/attachment/fileTypeImages', 'src=\"public/ueditor/dialogs/attachment/fileTypeImages', $content); // 附件图标
    
    $content = str_replace('src=\"'. $_W['script_dir'] .'data/attachments/'. $_W['module'] .'/ueditor', 'src=\"data/attachments/'. $_W['module'] .'/ueditor', $content);
    $content = str_replace('href=\"'. $_W['script_dir'] .'data/attachments/'. $_W['module'] .'/ueditor', 'href=\"data/attachments/'. $_W['module'] .'/ueditor', $content);
    
    // 11、把数据更新进数据库
    $sql = "UPDATE `{$_W['db']['tablepre']}singlepage` SET `status`='{$status}',`displayorder`='{$displayorder}',`title`='{$title}',`subtitle`='{$subtitle}',`keywords`='{$keywords}',`description`='{$description}',`pic`='{$pic}',`updatetime`='".W_TIMESTAMP."',`jumpurl`='{$jumpurl}',`editor`='{$_W['user']['username']}' WHERE `spid`='{$spid}'";
    if($WDB->query($sql)) {
        // 更新内容
        $sql = "UPDATE `{$_W['db']['tablepre']}singlepagecontent` SET `content`='{$content}' WHERE `spid`='{$spid}'";
        $WDB->query($sql);
        
        // 如果上传了图片，就删除 w_upload 表对应的记录，同时删除旧图片。
        if(!empty($pic) && is_file($pic)) {
            $sql = "DELETE FROM `{$_W['db']['tablepre']}upload` WHERE `id`='{$pic_id}'";
            $WDB->query($sql);
            $old_pic = './data/attachments/'. $_W['module'] .'/'. $old['pic'];
            if( (trim($old['pic']) != '') && is_file($old_pic) ) {
                unlink($old_pic);
            }
        }
        
        // 移动编辑器“新附件”到正式目录
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
                    $sql = "INSERT INTO `{$_W['db']['tablepre']}singlepageattachment`(`spid`, `uid`, `attachment`) VALUES ('{$spid}', '{$_W['user']['uid']}', '{$v}')";
                    $WDB->query($sql);
                }
            }
        }
        
        // 删除没有用的“旧附件”
        foreach($old['attachments'] as $v) {
            if(!in_array($v['attachment'], $old_attachments)) {
                $attachment_filename = './data/attachments/'. $_W['module'] .'/ueditor/'. $v['attachment'];
                if(is_file($attachment_filename)) {
                    $sql = "DELETE FROM `{$_W['db']['tablepre']}singlepageattachment` WHERE `id`='{$v['id']}'";
                    $WDB->query($sql);
                    unlink($attachment_filename);
                }
            }
        }
        
        //[*]为了安全，需要重置formtoken
        $formtoken = w_security::set_formtoken();
        
        // 增加管理记录
        if($old['title'] != $title) {
            w_adminlog('把单页“'. $old['title'] .'”更新为：'. $title);
        } else {
            w_adminlog("更新了单页：". $title);
        }
        
        // 成功提示
        exit('{"status":1, "msg":"操作成功", "formtoken":"'. $formtoken .'"}');
    } else {
        exit('{"status":0, "msg":"操作失败"}');
    }
    
}
