<?php if(!defined('IN_WEIZEPHP')){exit('Access Denied');}?><!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes"/>
        <title>WeizePHP后台管理</title>
        <link href="<?php echo $_W['public_path'];?>/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="<?php echo $_W['skin_path'];?>/css/dashboard.css" rel="stylesheet"/>
        <script src="<?php echo $_W['public_path'];?>/js/jquery.min.js"></script>
        <script src="<?php echo $_W['public_path'];?>/js/bootstrap.min.js"></script>
    </head>
    
    <body>
        <script type="text/javascript">
        if(window.top != window.self) {
        	window.top.location = window.self.location;
        }
        </script>
        
        <nav class="navbar navbar-inverse navbar-fixed-top w-dashboard-navbar">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="?m=home">WeizePHP</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
							<a class="btn dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
								<i class="glyphicon glyphicon-user"></i> <?php echo htmlspecialchars($_W['user']['username']);?>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li><a target="_blank" href="./">站点首页</a></li>
								<li><a href="?m=signin&a=signout">登出</a></li>
							</ul>
						</li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 w-sidebar">
                    <?php foreach($user_menus as $v) {?>
                    <div class="w-nav-block">
                        <h4 class="menu_block_h"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $v['name'];?></h4>
                        <ul class="nav nav-sidebar">
                            <?php
                            foreach($v['child'] as $ck => $cv) {
                                if($cv['display'] == 1) {
                                    if($ck == $_W['permission_flag']) {
                                        echo '<li class="active"><a href="'. $cv['url'] .'"><i class="glyphicon glyphicon-arrow-right"></i> '. $cv['name'] .'</a></li>';
                                    } else {
                                        echo '<li><a href="'. $cv['url'] .'">|- '. $cv['name'] .'</a></li>';
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <?php }?>
                </div><!-- /w-sidebar -->
                
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 w-main">                  
                    