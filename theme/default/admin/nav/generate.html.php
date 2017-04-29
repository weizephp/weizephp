<?php
if( !defined('IN_WEIZEPHP') ) {
    exit('Access Denied');
}
?><!DOCTYPE HTML>
<html>
    <head>
        <?php include $wconfig['theme_path'] . '/admin/head.inc.php';echo admin_head('导航生成');?>
    </head>
    <body>
        <?php include $wconfig['theme_path'] . '/admin/header.html.php';?>
            <h4 class="w-h4">导航生成</h4>
            <div class="alert alert-success alert-dismissible">
                <h4>导航菜单生成说明：</h4>
                <p>主要生成 ./config/config_nav.inc.php 配置文件，方便前台菜单调用。这样做的目的是减少菜单对数据库的请求，同时前台程序更容易遍历的导航菜单。</p>
                <p>
                    <button type="button" class="btn btn-danger w-do">立马生成！</button>
                </p>
            </div>
        <?php include $wconfig['theme_path'] . '/admin/footer.html.php';?>
        <?php include $wconfig['theme_path'] . '/w_dialog_success.html.php';?>
        <?php include $wconfig['theme_path'] . '/w_dialog_error.html.php';?>
        <script type="text/javascript">
        $(document).ready(function() {
            $('.w-do').on('click', function() {
                $.getJSON("?m=nav&a=generate&op=do&formtoken=<?php echo $wuser->formtoken;?>", function(result) {
                    if(result.status == 1) {
                        w_dialog_success(result.msg);
                    } else {
                        w_dialog_error(result.msg);
                    }
                }, 'json');
                return false;
            });
        });
        </script>
    </body>
</html>