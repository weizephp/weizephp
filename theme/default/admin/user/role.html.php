<?php
if( !defined('IN_WEIZEPHP') ) {
    exit('Access Denied');
}
?><!DOCTYPE HTML>
<html>
    <head>
        <?php include $wconfig['theme_path'] . '/admin/head.inc.php';echo admin_head('角色管理');?>
    </head>
    <body>
        <?php include $wconfig['theme_path'] . '/admin/header.html.php';?>
            <h4 class="w-h4">角色添加</h4>
            <div class="row w-row">
                <div class="col-lg-6">
                    <form class="w-form" method="post">
                        <input type="hidden" name="formtoken" value="<?php echo $wuser->formtoken;?>"/>
                        <div class="input-group">
                            <input type="text" class="form-control" name="rolename" placeholder="角色名称..."/>
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-default">+角色添加</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
            <h4 class="w-h4">角色列表 </h4>
            <?php foreach($roles as $v) {?>
            <form class="form-inline w-role-form-inline w-mb-10" method="post">
                <input type="hidden" name="formtoken" value="<?php echo $wuser->formtoken;?>"/>
                <input type="hidden" name="roleid" value="<?php echo $v['roleid'];?>"/>
                <span><?php echo $v['roleid'];?></span>
                <input type="text" class="form-control" name="rolename" value="<?php echo $v['rolename'];?>"/>
                <select class="form-control" name="status">
                    <?php
                    if($v['status'] == 1) {
                        echo '<option value="0">不启用</option><option value="1" selected="selected">启用</option>';
                    } else {
                        echo '<option value="0" selected="selected">不启用</option><option value="1">启用</option>';
                    }
                    ?>
                </select>
                <button type="submit" class="btn btn-primary">更新</button>
                <?php echo '<a class="btn btn-danger w-a-btn w-delete" href="?m=user&a=roledelete&roleid='. $v['roleid'] .'&formtoken='. $wuser->formtoken .'">删 除</a>';?>
            </form>
            <?php }?>
        <?php include $wconfig['theme_path'] . '/admin/footer.html.php';?>
        <?php include $wconfig['theme_path'] . '/w_dialog_success.html.php';?>
        <?php include $wconfig['theme_path'] . '/w_dialog_error.html.php';?>
        <?php include $wconfig['theme_path'] . '/w_dialog_confirm.html.php';?>
        <script type="text/javascript">
        $(document).ready(function() {
            // 添加
            $('.w-form').submit(function() {
                $.post("?m=user&a=roleadd", $(this).serialize(), function(result) {
                    if(result.status == 1) {
                        w_dialog_success(result.msg, '?m=user&a=role');
                    } else {
                        w_dialog_error(result.msg);
                    }
                }, 'json');
                return false;
            });
            // 更新
            $('.w-role-form-inline').submit(function() {
                $.post("?m=user&a=roleupdate", $(this).serialize(), function(result) {
                    if(result.status == 1) {
                        w_dialog_success(result.msg);
                    } else {
                        w_dialog_error(result.msg);
                    }
                }, 'json');
                return false;
            });
            // 删除
            $('.w-delete').click(function() {
                var href = $(this).attr('href');
                w_dialog_confirm("一旦删除将无法恢复，确定要删除吗？", function() {
                    $.getJSON(href, function(result) {
                        if(result.status == 1) {
                            window.location.reload(true);
                        } else {
                            w_dialog_error(result.msg);
                        }
                    });
                });
                return false;
            });
        });
        </script>
    </body>
</html>