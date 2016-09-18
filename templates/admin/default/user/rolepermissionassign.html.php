<?php
if(!defined('IN_WEIZEPHP')){exit('Access Denied');}
include $_W['template_path'].'/header.html.php';
?>              
    
    <h4 class="w-mt-0">
        <span class="text-danger">（<?php echo $role['rolename'];?>）</span>
        <span>权限分配</span>
        <a class="btn btn-success btn-xs w-a-btn" href="?m=user&a=rolepermissioncontrol">返回上一页</a>
    </h4>
    
    <form class="w-form" autocomplete="off">
        <?php foreach($menus as $k => $v) {?>
        <div class="table-responsive">
            <table class="table w-table">
                <thead class="w-thead">
                    <tr>
                        <th>
                            <label class="checkbox-inline w-checkbox-inline app">
                                <?php
                                if(in_array($k, $permissions)) {
                                    echo '<input type="checkbox" name="permission[]" value="'. $k .'" checked="checked"/> '. $v['name'];
                                } else {
                                    echo '<input type="checkbox" name="permission[]" value="'. $k .'"/> '. $v['name'];
                                }
                                ?>
                            </label>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($v['children'] as $ck => $cv) {?>
                    <tr>
                        <td>
                            <div class="rolepermissionassign-title">
                                <label class="checkbox-inline module">
                                    <?php
                                    if(in_array($ck, $permissions)) {
                                        echo '<input type="checkbox" name="permission[]" value="'. $ck .'" checked="checked"/> '. $cv['name'];
                                    } else {
                                        echo '<input type="checkbox" name="permission[]" value="'. $ck .'"/> '. $cv['name'];
                                    }
                                    ?>
                                </label>
                            </div>
                            <ul class="rolepermissionassign-ul">
                                <?php foreach($cv['children'] as $cck => $ccv) {?>
                                <li>
                                    <label class="checkbox-inline action">
                                        <?php
                                        if(in_array($cck, $permissions)) {
                                            echo '<input type="checkbox" name="permission[]" value="'. $cck .'" checked="checked"/> '. $ccv['name'];
                                        } else {
                                            echo '<input type="checkbox" name="permission[]" value="'. $cck .'"/> '. $ccv['name'];
                                        }
                                        ?>
                                    </label>
                                </li>
                                <?php }?>
                            </ul>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
        <?php }?>
        <input type="hidden" name="roleid" value="<?php echo $role['roleid'];?>"/>
        <input type="hidden" id="formtoken" name="formtoken" value="<?php echo $formtoken;?>"/>
        <button type="submit" class="btn btn-success w-a-btn">提 交</button>
    </form>
    
    <?php include W_ROOT_PATH . '/templates/w_modals.html.php';?>
    <script type="text/javascript">
    $(document).ready(function() {
        // 点击app级
        $('.app').click(function() {
        	var parent = $(this).parent().parent().parent().parent();
        	var module = parent.find('.module');
        	var action = parent.find('.action');
        	if($(this).children('input').prop('checked') == true) {
        		module.children('input').prop('checked', true);
        		action.children('input').prop('checked', true);
            } else {
            	module.children('input').prop('checked', false);
        		action.children('input').prop('checked', false);
            }
        });
        
        // 点击module级
        $('.module').click(function() {
            var parent = $(this).parent().parent().parent().parent().parent();
            var app    = parent.find('.app');
            var module = parent.find('.module');
            var action = $(this).parent().siblings('ul').find('.action');
            if($(this).children('input').prop('checked') == true) {
            	app.children('input').prop('checked', true);
            	action.children('input').prop('checked', true);
            } else {
            	action.children('input').prop('checked', false);
            }
            $status = false;
            module.each(function(k) {
                if(module.eq(k).children('input').prop('checked') == true) {
                	$status = true;
                }
            });
            if($status == false) {
            	app.children('input').prop('checked', false);
            }
        });
        
        // 点击action级
        $('.action').click(function() {
            var app     = $(this).parent().parent().parent().parent().parent().siblings('.w-thead').find('.app');
            var module  = $(this).parent().parent().siblings('.rolepermissionassign-title').find('.module');
            var action  = $(this).parent().parent().find('.action');
            var actions = $(this).parent().parent().parent().parent().parent().find('.action');
            //----
            if($(this).children('input').prop('checked') == true) {
            	app.children('input').prop('checked', true);
            	module.children('input').prop('checked', true);
            }
            //-----
            $cur_status = false;
            action.each(function(k) {
                if(action.eq(k).children('input').prop('checked') == true) {
                	$cur_status = true;
                }
            });
            if($cur_status == false) {
            	module.children('input').prop('checked', false);
            }
            //-----
            $status = false;
            actions.each(function(k) {
                if(actions.eq(k).children('input').prop('checked') == true) {
                	$status = true;
                }
            });
            if($status == false) {
            	app.children('input').prop('checked', false);
            }
        });

        // 提交
        $('.w-form').submit(function() {
            var formData = $(this).serialize();
            $.post("?m=user&a=rolepermissionupdate", formData, function(result) {
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
