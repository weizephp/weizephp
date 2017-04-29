<?php
if( !defined('IN_WEIZEPHP') ) {
	exit('Access Denied');
}
?>
<div class="w-header container-fluid navbar-inverse">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" id="w-navbar-toggle">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="?m=home">C/S Store</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
                            <i class="glyphicon glyphicon-user"></i>
                            <?php echo $wuser->username;?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a target="_blank" href="./">站点首页</a></li>
                            <li><a href="?m=login&a=logout">登出</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
        
        <div id="w-user-guide" class="container-fluid">
            <span class="label label-info">Hi, <?php echo $wuser->username;?></span>
            <a class="label label-warning" href="./" target="_blank">站点首页</a>
            <a class="label label-danger" href="?m=login&a=logout">登出</a>
        </div>
        
        <div class="container-fluid">
            <div class="row">
                <div id="w-navbar" class="col-sm-3 col-md-2 w-sidebar">
                    <?php foreach($wuser->get_curapp_user_menu() as $k => $v) {?>
                    <div class="w-sidebar-item">
                        <h4><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $v['name'];?></h4>
                        <ul class="nav nav-sidebar">
                            <?php
                            foreach($v['children'] as $ck => $cv) {
                                if($cv['display'] == 1) {
                                    if($wflag == $cv['flag']) {
                                        echo '<li class="active"><a href="'. $cv['url'] .'"><i class="glyphicon glyphicon-arrow-right"></i> '. $cv['name'] .'</a></li>';
                                    } else {
                                        echo '<li><a href="'. $cv['url'] .'"><i>|-</i> '. $cv['name'] .'</a></li>';
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <?php }?>
                </div>
                
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 w-main">
