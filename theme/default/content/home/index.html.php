<?php
/**
 * 网站首页模板
 */
if( !defined('IN_WEIZEPHP') ) {
    exit('Access Denied');
}
include $wconfig['theme_path'] . '/site_cfg.inc.php';
include W_ROOT_PATH . '/config/config_nav.inc.php';
?><!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
        <title><?php echo $site_cfg['site_name'];?></title>
        <meta name="keywords" content="<?php echo $site_cfg['site_keywords'];?>"/>
        <meta name="description" content="<?php echo $site_cfg['site_description'];?>"/>
        <link rel="stylesheet" href="<?php echo $wconfig['public_path'];?>/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="<?php echo $wconfig['theme_skin'];?>/css/content-home-index.css"/>
    </head>
    <body>
        <div class="site-wrapper">
            <div class="site-wrapper-inner">
                <div class="cover-container">

                    <div class="masthead clearfix">
                        <div class="inner">
                        <h3 class="masthead-brand">WeizePHP</h3>
                            <nav>
                                <ul class="nav masthead-nav">
                                    <?php
                                    // 导航菜单（[!]可以单独提取成include文件）
                                    if( !empty($_SERVER['QUERY_STRING']) ) {
                                        $_cur_url = w_rewriteurl( basename($_SERVER['SCRIPT_NAME']) . '?' . $_SERVER['QUERY_STRING'] );
                                    } else {
                                        $_cur_url = w_rewriteurl( basename($_SERVER['SCRIPT_NAME']) );
                                    }
                                    foreach($_nav as $key => $val) {
                                        $target = $val['target'] == 0 ? ' ' : ' target="_blank" '; // 注意空格
                                        if( ($_cur_url == 'index.php') && ($val['url'] == './') ) {
                                            echo '<li class="active"><a'. $target .'href="'. $val['url'] .'">'. $val['name'] .'</a></li>';
                                        } else if( ($val['internal'] == 1) && (strpos($_cur_url, $val['url']) !== false) ) {
                                            echo '<li class="active"><a'. $target .'href="'. $val['url'] .'">'. $val['name'] .'</a></li>';
                                        } else {
                                            echo '<li><a'. $target .'href="'. $val['url'] .'">'. $val['name'] .'</a></li>';
                                        }
                                    }
                                    ?>
                                </ul>
                            </nav>
                        </div>
                    </div>

                    <div class="inner cover">
                        <h1 class="cover-heading">简单又实用的PHP框架。</h1>
                        <p class="lead">WeizePHP是一款超小的PHP框架，使用MIT协议，您可以免费自由的应用于商业。她有后台、有权限管理、有完整的开发手册。入门简单，只要会php语法，就可以快速的开发各种应用和网站^_^。</p>
                        <p class="lead">
                            <a href="admin.php" target="_blank" class="btn btn-lg btn-default">查看演示</a>
                        </p>
                    </div>

                    <div class="mastfoot">
                        <div class="inner">
                            <p>Cover template for <a href="http://getbootstrap.com" target="_blank" rel="nofollow">Bootstrap</a>, by <a href="https://twitter.com/mdo" target="_blank" rel="nofollow">@mdo</a>.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </body>
</html>
