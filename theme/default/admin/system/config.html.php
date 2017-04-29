<?php
if( !defined('IN_WEIZEPHP') ) {
    exit('Access Denied');
}
?><!DOCTYPE HTML>
<html>
    <head>
        <?php include $wconfig['theme_path'] . '/admin/head.inc.php';echo admin_head('网站配置');?>
    </head>
    <body>
        <?php include $wconfig['theme_path'] . '/admin/header.html.php';?>
            <h4 class="w-h4">网站配置</h4>
            <form class="form-horizontal w-form" autocomplete="off">
                <div class="form-group">
                    <label for="site_name" class="col-sm-2 control-label">站点名称</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="site_name" name="site_name" value="<?php echo $site_cfg['site_name'];?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="site_title" class="col-sm-2 control-label">站点标题</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="site_title" name="site_title" value="<?php echo $site_cfg['site_title'];?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="site_keywords" class="col-sm-2 control-label">网站关键字</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="site_keywords" name="site_keywords" value="<?php echo $site_cfg['site_keywords'];?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="site_description" class="col-sm-2 control-label">网站简介</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" id="site_description" name="site_description"><?php echo $site_cfg['site_description'];?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="site_icp" class="col-sm-2 control-label">网站备案号</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="site_icp" name="site_icp" value="<?php echo $site_cfg['site_icp'];?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="site_statistical_code" class="col-sm-2 control-label">站点计代码</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" id="site_statistical_code" name="site_statistical_code"><?php echo $site_cfg['site_statistical_code'];?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="site_footer" class="col-sm-2 control-label">网站尾部</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="8" id="site_footer" name="site_footer"><?php echo $site_cfg['site_footer'];?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">关闭站点</label>
                    <div class="col-sm-10">
                        <?php if($site_cfg['site_closed'] == 0) {?>
                        <label class="radio-inline"><input type="radio" name="site_closed" value="0" checked="checked" />否</label>
                        <label class="radio-inline"><input type="radio" name="site_closed" value="1" />是</label>
                        <?php } else {?>
                        <label class="radio-inline"><input type="radio" name="site_closed" value="0" />否</label>
                        <label class="radio-inline"><input type="radio" name="site_closed" value="1" checked="checked" />是</label>
                        <?php }?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="site_closed_reason" class="col-sm-2 control-label">关闭原因</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" rows="2" id="site_closed_reason" name="site_closed_reason"><?php echo $site_cfg['site_closed_reason'];?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="hidden" name="formtoken" value="<?php echo $wuser->formtoken;?>" />
                        <button type="submit" class="btn btn-default">提 交</button>
                    </div>
                </div>
            </form>
        <?php include $wconfig['theme_path'] . '/admin/footer.html.php';?>
        <?php include $wconfig['theme_path'] . '/w_dialog_success.html.php';?>
        <?php include $wconfig['theme_path'] . '/w_dialog_error.html.php';?>
        <script type="text/javascript">
        $(document).ready(function() {
            $('.w-form').submit(function() {
                $.post("?m=system&a=configupdate", $(this).serialize(), function(result) {
                    if(result.status == 1) {
                        w_dialog_success('更新成功');
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