<?php
if(!defined('IN_WEIZEPHP')){exit('Access Denied');}
include $_W['template_path'].'/header.html.php';
?>              
    
    <h4 class="w-h4">用户添加</h4>
    <form class="w-form" autocomplete="off">
        <div class="form-group">
            <label for="username">用户名<span class="text-danger">(必填)</span></label>
            <input type="text" class="form-control" id="username" name="username"/>
        </div>
        <div class="form-group">
            <label for="username_again">用户名确认<span class="text-danger">(必填)</span></label>
            <input type="text" class="form-control" id="username_again" name="username_again"/>
        </div>
        <div class="form-group">
            <label for="roleid">所属角色<span class="text-danger">(必填)</span></label>
            <select class="form-control" id="roleid" name="roleid">
                <option value="0">请选择角色...</option>
                <?php
                foreach($roles as $v) {
                    echo '<option value="'. $v['roleid'] .'">'. $v['rolename'] .'</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="password">用户密码<span class="text-danger">(必填)</span></label>
            <input type="password" class="form-control" id="password" name="password"/>
        </div>
        <div class="form-group">
            <label for="password_again">用户密码确认<span class="text-danger">(必填)</span></label>
            <input type="password" class="form-control" id="password_again" name="password_again"/>
        </div>
        <div class="form-group">
            <label for="email">用户邮箱<span class="text-danger">(必填)</span></label>
            <input type="email" class="form-control" id="email" name="email"/>
        </div>
        <div class="form-group">
            <label for="email_again">用户邮箱确认<span class="text-danger">(必填)</span></label>
            <input type="email" class="form-control" id="email_again" name="email_again"/>
        </div>
        <div class="form-group">
            <label for="mobile">手机</label>
            <input type="text" class="form-control" id="mobile" name="mobile"/>
        </div>
        <input type="hidden" id="formtoken" name="formtoken" value="<?php echo $formtoken;?>"/>
        <button type="submit" class="btn btn-success">提 交</button>
    </form>
    
    <?php include W_ROOT_PATH . '/templates/w_modals.html.php';?>
    <script type="text/javascript">
    $(document).ready(function() {
        $('.w-form').submit(function() {
            var formData = $(this).serialize();
            $.post("?m=user&a=add", formData, function(result) {
                if(result.status == 1) {
                	w_b_modal_success('操作成功', function() {
                		window.location.href = '?m=user&a=list';
                    });
                } else {
                	w_b_modal_error(result.msg);
                }
            }, 'json');
            return false;
        });
    });
    </script>
    
<?php include $_W['template_path'].'/footer.html.php';?>
