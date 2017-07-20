<?php
/**
 * 网站首页模板
 */
if( !defined('IN_WEIZEPHP') ) {
    exit('Access Denied');
}
include $wconfig['theme_path'] . '/site_cfg.inc.php';
?><!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
        <title><?php echo $row['title'] . '_' . $site_cfg['site_name'];?></title>
        <meta name="keywords" content="<?php echo $row['keywords'];?>"/>
        <meta name="description" content="<?php echo $row['description'];?>"/>
        <link rel="stylesheet" href="<?php echo $wconfig['theme_skin'];?>/css/content.css"/>
    </head>
    <body>
        <div class="a-s-container">
            <ol class="a-s-breadcrumb">
                <li><a href="./">首页</a></li>
                <li> / </li>
                <li class="active">页面详情</li>
            </ol>
            
            <h1 class="a-s-h1"><?php echo $row['title'];?></h1>
            <div class="a-s-time-source"><?php echo date('Y-m-d H:i:s', $row['updatetime']);?>　编辑员: <?php echo $row['editor'];?></div>
            
            <div class="a-s-body">
                <?php echo $row['content'];?>
            </div>
            
            <div class="a-s-footer">
                <p>Copyright &copy; 2013 - 2113 WeizePHP. <a target="_blank" href="http://opensource.org/licenses/MIT" rel="nofollow">MIT License</a></p>
            </div>
        </div>
        <?php if( strpos($row['content'], '<pre class="brush:') !== false ) {?>
        <!-- 代码高亮 -->
        <script src="<?php echo $wconfig['public_path'];?>/ueditor/ueditor.parse.min.js"></script>
        <script>
            uParse('.a-s-body', {
                rootPath : '<?php echo $wconfig['public_path'];?>/ueditor/'
            });
        </script>
        <?php }?>
    </body>
</html>
