<?php
if(!defined('IN_WEIZEPHP')){exit('Access Denied');}
include $_W['template_path'].'/header.html.php';
?>              
    
    <h4 class="w-h4">角色添加</h4>
    <div class="row w-row">
        <div class="col-lg-6">
            <form class="w-form" method="post" action="?m=user&a=roleadd">
                <input type="hidden" name="formtoken" value="<?php echo $formtoken;?>"/>
                <div class="input-group">
                    <input type="text" class="form-control" name="rolename" placeholder="角色名称..."/>
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">+角色添加</button>
                    </span>
                </div>
            </form>
        </div>
    </div>
    
    <h4 class="w-h4">角色列表 </h4>
    <?php foreach($roles as $v) {?>
    <form class="form-inline w-role-form-inline w-mb-10" method="post" action="?m=user&a=roleupdate">
        <input type="hidden" name="formtoken" value="<?php echo $formtoken;?>"/>
        <input type="hidden" name="roleid" value="<?php echo $v['roleid'];?>"/>
        <span><?php echo $v['roleid'];?></span>
        <input type="text" class="form-control" name="rolename" value="<?php echo $v['rolename'];?>"/>
        <select class="form-control" name="status">
            <?php
            if($v['status'] == 1) {
                echo '<option value="0">否</option><option value="1" selected="selected">是</option>';
            } else {
                echo '<option value="0" selected="selected">否</option><option value="1">是</option>';
            }
            ?>
        </select>
        <button type="submit" class="btn btn-primary">更新</button>
        <?php echo '<a class="btn btn-danger w-a-btn w-delete" href="?m=user&a=roledelete&roleid='. $v['roleid'] .'&formtoken='. $formtoken .'">删 除</a>';?>
    </form>
    <?php }?>
    
    <?php include W_ROOT_PATH . '/templates/w_modalconfirm.html.php';?>
    <script type="text/javascript">
    $(document).ready(function() {
        $('.w-delete').click(function() {
            var href = $(this).attr('href');
            w_b_modal_confirm("一旦删除将无法恢复，确定要删除吗？", function() {
            	window.location.href = href;
            });
            return false;
        });
    });
    </script>
    
<?php include $_W['template_path'].'/footer.html.php';?>
