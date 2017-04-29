<?php
if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}
?><!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
        <meta name="keywords" content=""/>
        <meta name="description" content=""/>
        <title>会员中心</title>
        <link rel="stylesheet" href="<?php echo $wconfig['public_path'];?>/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="<?php echo $wconfig['public_path'];?>/css/metisMenu.min.css"/>
        <link rel="stylesheet" href="<?php echo $wconfig['public_path'];?>/css/sb-admin-2.min.css"/>
        <link rel="stylesheet" href="<?php echo $wconfig['public_path'];?>/css/font-awesome.min.css"/>
        <script src="<?php echo $wconfig['public_path'];?>/js/jquery-1.12.4.min.js"></script>
        <script src="<?php echo $wconfig['public_path'];?>/js/bootstrap.min.js"></script>
        <script src="<?php echo $wconfig['public_path'];?>/js/metisMenu.min.js"></script>
        <script src="<?php echo $wconfig['public_path'];?>/js/sb-admin-2.min.js"></script>
    </head>
    <body>
        <div id="wrapper">
            
            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.html">会员中心</a>
                </div>
                <!-- /.navbar-header -->

                <ul class="nav navbar-top-links navbar-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#"><i class="fa fa-user fa-fw"></i> 个人信息</a>
                            </li>
                            <li><a href="#"><i class="fa fa-gear fa-fw"></i> 设置</a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="?m=login&a=logout"><i class="fa fa-sign-out fa-fw"></i> 登出</a>
                            </li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <!-- /.dropdown -->
                </ul>
                <!-- /.navbar-top-links -->

                <div class="navbar-default sidebar" role="navigation">
                    <div class="sidebar-nav navbar-collapse">
                        <ul class="nav" id="side-menu">
                            <li class="sidebar-search">
                                <div class="input-group custom-search-form">
                                    <input type="text" class="form-control" placeholder="Search...">
                                    <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                                </div>
                            </li>
                            <?php
                            // 导航菜单
                            foreach( $wuser->get_curapp_user_menu() as $val ) {
                                if($val['flag'] == 'member/home') {
                                    echo '<li><a href="'. $val['children'][0]['url'] .'">'. $val['children'][0]['name'] .'</a></li>';
                                } else {
                            ?>
                                <li>
                                    <?php echo '<a href="'. $val['url'] .'">'. $val['name'] .'<span class="fa arrow"></span></a>';?>
                                    <ul class="nav nav-second-level">
                                        <?php
                                        foreach($val['children'] as $cval) {
                                            echo '<li><a href="'. $cval['url'] .'">'. $cval['name'] .'</a></li>';
                                        }
                                        ?>
                                    </ul>
                                </li>
                            <?php }}?>
                        </ul>
                    </div>
                    <!-- /.sidebar-collapse -->
                </div>
                <!-- /.navbar-static-side -->
            </nav>
            
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">会员首页</h1>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="well">
                            <h4>很高兴您能够使用本站系统！</h4>
                            <p>这是一个示例，您可以根据自己的需要做改动，目前会员中心采用的模板来至 <a target="_blank" href="https://github.com/BlackrockDigital/startbootstrap-sb-admin-2">https://github.com/BlackrockDigital/startbootstrap-sb-admin-2</a> 。非常感谢他们提供的模板。再次致谢！</p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">您的个人信息</div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <tbody>
                                            <tr>
                                                <td width="80">用户名</td>
                                                <td><?php echo $wuser->username;?></td>
                                            </tr>
                                            <tr>
                                                <td>积分</td>
                                                <td><?php echo $wuser->point;?></td>
                                            </tr>
                                            <tr>
                                                <td>余额</td>
                                                <td><?php echo $wuser->balance;?></td>
                                            </tr>
                                            <tr>
                                                <td>实名</td>
                                                <td><?php if(empty($wuser->realname)) { echo '没有填写'; } else { echo $wuser->realname; }?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /#page-wrapper -->
            
        </div>
        <?php include $wconfig['theme_path'] . '/w_dialog_error.html.php';?>
        <script>
        </script>
    </body>
</html>