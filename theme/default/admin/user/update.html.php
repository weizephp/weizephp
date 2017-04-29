<?php
if( !defined('IN_WEIZEPHP') ) {
    exit('Access Denied');
}
?><!DOCTYPE HTML>
<html>
    <head>
        <?php include $wconfig['theme_path'] . '/admin/head.inc.php';echo admin_head('用户更新');?>
    </head>
    <body>
        <?php include $wconfig['theme_path'] . '/admin/header.html.php';?>
            <h4 class="w-h4">用户更新</h4>
            <form class="form-horizontal w-form" autocomplete="off">
                <div class="form-group">
                    <label for="username" class="col-sm-2 control-label">用户名</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="username" disabled="disabled" value="<?php echo $user['username'];?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="roleid" class="col-sm-2 control-label">所属角色</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="roleid" name="roleid">
                            <option value="0">请选择角色...</option>
                            <?php
                            foreach($roles as $v) {
                                if($v['roleid'] == $user['roleid']) {
                                    echo '<option value="'. $v['roleid'] .'" selected="selected">'. $v['rolename'] .'</option>';
                                } else {
                                    echo '<option value="'. $v['roleid'] .'">'. $v['rolename'] .'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">用户密码</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="password" name="password" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="password_again" class="col-sm-2 control-label">用户密码确认</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="password_again" name="password_again" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">用户邮箱</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email'];?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="mobile" class="col-sm-2 control-label">手机</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $user['mobile'];?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="status" class="col-sm-2 control-label">是否启用</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="status" name="status">
                            <?php
                            if($user['status'] == 1) {
                                echo '<option value="0">否</option><option value="1" selected="selected">是</option>';
                            } else {
                                echo '<option value="0" selected="selected">否</option><option value="1">是</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="regtime" class="col-sm-2 control-label">注册时间</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="regtime" disabled="disabled" value="<?php echo date('Y-m-d H:i:s', $user['regtime']);?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="regip" class="col-sm-2 control-label">注册IP</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="regip" disabled="disabled" value="<?php echo $user['regip'];?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastlogintime" class="col-sm-2 control-label">最后登录时间</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="lastlogintime" disabled="disabled" value="<?php echo date('Y-m-d H:i:s', $user['lastlogintime']);?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastloginip" class="col-sm-2 control-label">最后登录IP</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="lastloginip" disabled="disabled" value="<?php echo $user['lastloginip'];?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="logincount" class="col-sm-2 control-label">登陆次数</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="logincount" disabled="disabled" value="<?php echo $user['logincount'];?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="point" class="col-sm-2 control-label">积分</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="point" disabled="disabled" value="<?php echo $user['point'];?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="balance" class="col-sm-2 control-label">余额</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="balance" disabled="disabled" value="<?php echo $user['balance'];?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="realname" class="col-sm-2 control-label">真名</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="realname" name="realname" value="<?php echo $user['realname'];?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="nickname" class="col-sm-2 control-label">昵称</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nickname" name="nickname" value="<?php echo $user['nickname'];?>" />
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="hidden" name="uid" value="<?php echo $user['uid'];?>" />
                        <input type="hidden" name="formtoken" value="<?php echo $wuser->formtoken;?>" />
                        <button type="submit" class="btn btn-default">提 交</button>
                        - <a class="btn btn-info w-a-btn" href="<?php echo $redirect;?>">返回上一页</a>
                    </div>
                </div>
            </form>
        <?php include $wconfig['theme_path'] . '/admin/footer.html.php';?>
        <?php include $wconfig['theme_path'] . '/w_dialog_success.html.php';?>
        <?php include $wconfig['theme_path'] . '/w_dialog_error.html.php';?>
        <script type="text/javascript">
        $(document).ready(function() {
            $('.w-form').submit(function() {
                $.post("?m=user&a=update", $(this).serialize(), function(result) {
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