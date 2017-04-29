<?php
if( !defined('IN_WEIZEPHP') ) {
    exit('Access Denied');
}
?><!DOCTYPE HTML>
<html>
    <head>
        <?php include $wconfig['theme_path'] . '/admin/head.inc.php';echo admin_head('用户添加');?>
    </head>
    <body>
        <?php include $wconfig['theme_path'] . '/admin/header.html.php';?>
            <h4 class="w-h4">用户添加</h4>
            <form class="form-horizontal w-form" autocomplete="off">
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label">用户名<span class="text-danger">(必填)</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="username" name="username" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="username_again" class="col-sm-2 control-label">用户名确认<span class="text-danger">(必填)</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="username_again" name="username_again" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="roleid" class="col-sm-2 control-label">所属角色<span class="text-danger">(必填)</span></label>
                    <div class="col-sm-10">
                        <select class="form-control" id="roleid" name="roleid">
                            <option value="0">请选择角色...</option>
                            <?php
                            foreach($roles as $v) {
                                echo '<option value="'. $v['roleid'] .'">'. $v['rolename'] .'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">用户密码<span class="text-danger">(必填)</span></label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="password" name="password" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="password_again" class="col-sm-2 control-label">用户密码确认<span class="text-danger">(必填)</span></label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="password_again" name="password_again" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">用户邮箱<span class="text-danger">(必填)</span></label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="email" name="email" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="email_again" class="col-sm-2 control-label">用户邮箱确认<span class="text-danger">(必填)</span></label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="email_again" name="email_again" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="mobile" class="col-sm-2 control-label">手机</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="mobile" name="mobile" />
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
                $.post("?m=user&a=add", $(this).serialize(), function(result) {
                    if(result.status == 1) {
                        w_dialog_success(result.msg, '?m=user&a=list');
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