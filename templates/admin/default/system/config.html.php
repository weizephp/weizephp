<?php
if(!defined('IN_WEIZEPHP')){exit('Access Denied');}
include $_W['template_path'].'/header.html.php';
?>              
    
    <h4 class="w-h4">网站配置</h4>
    <form class="w-form">
        <div class="form-group">
            <label for="site_name">站点名称</label>
            <input type="text" class="form-control" id="site_name" name="site_name" placeholder="" value="<?php echo $site_cfg['site_name'];?>"/>
        </div>
        <div class="form-group">
            <label for="site_keywords">网站关键字</label>
            <input type="text" class="form-control" id="site_keywords" name="site_keywords" placeholder="" value="<?php echo $site_cfg['site_keywords'];?>"/>
        </div>
        <div class="form-group">
            <label for="site_description">网站简介</label>
            <textarea class="form-control" rows="3" id="site_description" name="site_description" placeholder=""><?php echo $site_cfg['site_description'];?></textarea>
        </div>
        <div class="form-group">
            <label for="site_icp">网站备案号</label>
            <input type="text" class="form-control" id="site_icp" name="site_icp" placeholder="" value="<?php echo $site_cfg['site_icp'];?>"/>
        </div>
        <div class="form-group">
            <label for="site_statistical_code">站点计代码</label>
            <textarea class="form-control" rows="3" id="site_statistical_code" name="site_statistical_code" placeholder=""><?php echo $site_cfg['site_statistical_code'];?></textarea>
        </div>
        <div class="form-group">
            <label for="site_footer">网站尾部</label>
            <textarea class="form-control" rows="10" id="site_footer" name="site_footer" placeholder=""><?php echo $site_cfg['site_footer'];?></textarea>
        </div>
        <input type="hidden" id="formtoken" name="formtoken" value="<?php echo $formtoken;?>"/>
        <button type="submit" class="btn btn-success">提 交</button>
    </form>
    
    <?php include W_ROOT_PATH . '/templates/w_modals.html.php';?>
    <script type="text/javascript">
    $(document).ready(function() {
        $('.w-form').submit(function() {
            var formData = $(this).serialize();
            $.post("?m=system&a=configupdate&isajax=yes", formData, function(result) {
                if(result.status == 1) {
                    $("#formtoken").val(result.formtoken);
                	w_b_modal_success('更新成功');
                } else {
                	w_b_modal_error(result.msg);
                }
            }, 'json');
            return false;
        });
    });
    </script>
    
<?php include $_W['template_path'].'/footer.html.php';?>
