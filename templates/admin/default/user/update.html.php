<?php
if(!defined('IN_WEIZEPHP')){exit('Access Denied');}
include $_W['template_path'].'/header.html.php';
?>              
    
    <h4 class="w-h4">用户更新</h4>
    <form class="w-form" autocomplete="off">
        <div class="form-group">
            <label for="username">用户名</label>
            <input type="text" class="form-control" id="username" disabled="disabled" value="<?php echo $user['username'];?>"/>
        </div>
        <div class="form-group">
            <label for="roleid">所属角色</label>
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
        <div class="form-group">
            <label for="password">用户密码</label>
            <input type="password" class="form-control" id="password" name="password"/>
        </div>
        <div class="form-group">
            <label for="password_again">用户密码确认</label>
            <input type="password" class="form-control" id="password_again" name="password_again"/>
        </div>
        <div class="form-group">
            <label for="email">用户邮箱</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email'];?>"/>
        </div>
        <div class="form-group">
            <label for="mobile">手机</label>
            <input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo $user['mobile'];?>"/>
        </div>
        <div class="form-group">
            <label for="status">是否启用</label>
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
        <div class="form-group">
            <label for="regtime">注册时间</label>
            <input type="text" class="form-control" id="regtime" disabled="disabled" value="<?php echo date('Y-m-d H:i:s', $user['regtime']);?>"/>
        </div>
        <div class="form-group">
            <label for="regip">注册IP</label>
            <input type="text" class="form-control" id="regip" disabled="disabled" value="<?php echo $user['regip'];?>"/>
        </div>
        <div class="form-group">
            <label for="lastlogintime">最后登录时间</label>
            <input type="text" class="form-control" id="lastlogintime" disabled="disabled" value="<?php echo date('Y-m-d H:i:s', $user['lastlogintime']);?>"/>
        </div>
        <div class="form-group">
            <label for="lastloginip">最后登录IP</label>
            <input type="text" class="form-control" id="lastloginip" disabled="disabled" value="<?php echo $user['lastloginip'];?>"/>
        </div>
        <div class="form-group">
            <label for="logincount">登陆次数</label>
            <input type="text" class="form-control" id="logincount" disabled="disabled" value="<?php echo $user['logincount'];?>"/>
        </div>
        <div class="form-group">
            <label for="points">积分</label>
            <input type="text" class="form-control" id="points" disabled="disabled" value="<?php echo $user['points'];?>"/>
        </div>
        <div class="form-group">
            <label for="balances">余额</label>
            <input type="text" class="form-control" id="balances" disabled="disabled" value="<?php echo $user['balances'];?>"/>
        </div>
        <div class="form-group">
            <label for="realname">真名</label>
            <input type="text" class="form-control" id="realname" name="realname" value="<?php echo $user['realname'];?>"/>
        </div>
        <div class="form-group">
            <label for="nickname">昵称</label>
            <input type="text" class="form-control" id="nickname" name="nickname" value="<?php echo $user['nickname'];?>"/>
        </div>
        <input type="hidden" id="uid" name="uid" value="<?php echo $user['uid'];?>"/>
        <input type="hidden" id="formtoken" name="formtoken" value="<?php echo $formtoken;?>"/>
        <button type="submit" class="btn btn-success">提 交</button>
        - <a class="btn btn-info w-a-btn" href="<?php echo $redirect;?>">返回上一页</a>
    </form>
    
    <?php include W_ROOT_PATH . '/templates/w_modals.html.php';?>
    <script type="text/javascript">
    $(document).ready(function() {
        $('.w-form').submit(function() {
            var formData = $(this).serialize();
            $.post("?m=user&a=update", formData, function(result) {
                if(result.status == 1) {
                    $('#formtoken').val(result.formtoken);
                	w_b_modal_success('操作成功');
                } else {
                	w_b_modal_error(result.msg);
                }
            }, 'json');
            return false;
        });
    });
    </script>
    
<?php include $_W['template_path'].'/footer.html.php';?>
