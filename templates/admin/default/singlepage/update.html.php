<?php
if(!defined('IN_WEIZEPHP')){exit('Access Denied');}
include $_W['template_path'].'/header.html.php';
?>              
    
    <h4 class="w-h4">单页更新</h4>
    <form class="w-form">
        <div class="form-group">
            <label for="title">标题<span class="text-danger">(必填)</span></label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo $row['title'];?>"/>
        </div>
        <div class="form-group">
            <label for="subtitle">副标题</label>
            <input type="text" class="form-control" id="subtitle" name="subtitle" value="<?php echo $row['subtitle'];?>"/>
        </div>
        <div class="form-group">
            <label for="status">是否显示</label>
            <select class="form-control" id="status" name="status">
                <?php
                if($row['status'] == 1) {
                    echo '<option value="0">否</option><option value="1" selected="selected">是</option>';
                } else {
                    echo '<option value="0" selected="selected">否</option><option value="1">是</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="displayorder">排序</label>
            <input type="text" class="form-control" id="displayorder" name="displayorder" value="<?php echo $row['displayorder'];?>"/>
        </div>
        <div class="form-group">
            <label for="w_upload_pic">图片</label>
            <button type="button" class="btn btn-info btn-block w_upload_pic">+上传图片</button>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-4" id="w_upload_pic_thumb">
                <?php if(!empty($row['pic'])) {?>
                <div class="thumbnail">
                    <img src="<?php echo w_thumb_url($_W['module'], $row['pic'], 242, 200);?>"/>
                    <div class="caption"><p class="text-center"><a href="javascript:;" class="btn btn-primary w-a-btn w-pic-delete" role="button">x 删除</a></p></div>
                </div>
                <?php }?>
            </div>
        </div>
        <div class="form-group">
            <label for="jumpurl">跳转网址</label>
            <input type="text" class="form-control" id="jumpurl" name="jumpurl" value="<?php echo $row['jumpurl'];?>"/>
        </div>
        <div class="form-group">
            <label for="keywords">关键字(SEO用)</label>
            <input type="text" class="form-control" id="keywords" name="keywords" value="<?php echo $row['keywords'];?>"/>
            <span class="help-block">限100个字符</span>
        </div>
        <div class="form-group">
            <label for="description">简介<span class="text-danger">(必填)</span></label>
            <textarea class="form-control" rows="3" id="description" name="description"><?php echo $row['description'];?></textarea>
        </div>
        <div class="form-group">
            <label for="w-ueditor-container">内容<span class="text-danger">(必填)</span></label>
            <!-- 加载编辑器的容器 -->
            <script id="w-ueditor-container" name="content" type="text/plain"><?php echo $row['content'];?></script>
        </div>
        <input type="hidden" id="spid" name="spid" value="<?php echo $row['spid'];?>"/>
        <input type="hidden" id="formtoken" name="formtoken" value="<?php echo $formtoken;?>"/>
        <button type="submit" class="btn btn-success btn-lg btn-block">提 交</button>
    </form>
    
    <br/>
    <a href="<?php echo $redirect;?>" class="btn btn-warning btn-lg btn-block w-a-btn w-back">返回上一页</a>
    
    <script src="<?php echo $_W['public_path'];?>/js/jquery.ajax_file_upload.js"></script>
    <?php include W_ROOT_PATH . '/templates/w_modals.html.php';?>
    <script type="text/javascript" src="<?php echo $_W['public_path'];?>/ueditor/w-ueditor.config.js"></script>
    <script type="text/javascript" src="<?php echo $_W['public_path'];?>/ueditor/ueditor.all.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        // 实例化编辑器
    	var ue = UE.getEditor('w-ueditor-container', {
    		topOffset: 50,
    		initialFrameHeight: 600,
    		autoFloatEnabled: false
        });
        // 上传图片
    	$('.w_upload_pic').click(function(e) {
        	$.ajax_file_upload(
        		{url: 'generals.php?m=upload&a=upload&folder=singlepage'},
        		function(data) {
            		if(data) {
            			var result = $.parseJSON(data);
                	} else {
                		var result = {"status": 0, "msg": "上传出错"};
                    }
                    if(result.status == 1) {
                        var thumbHtml = '<div class="thumbnail">' +
                            '<img src="generals.php?m=image&a=thumb&f=singlepage&s='+ result.data.save_name +'&w=242&h=200"/>' +
                            '<input type="hidden" name="pic_id" value="'+ result.id +'"/>' +
                            '<div class="caption"><p class="text-center"><a href="javascript:;" class="btn btn-primary w-a-btn w-pic-delete" role="button">x 删除</a></p></div>' +
                        '</div>';
                        $('#w_upload_pic_thumb').html(thumbHtml);
                        thumbHtml = '';
                    } else {
                    	w_b_modal_error(result.msg);
                    }
            	}
            );
        });
    	// 删除图片
        $('#w_upload_pic_thumb').on('click', '.w-pic-delete', function() {
            w_b_modal_confirm("确认删除吗？", function() {
            	$('#w_upload_pic_thumb').html('');
            });
        });
        // 提交
        $('.w-form').submit(function() {
            var formData = $(this).serialize();
            $.post("?m=singlepage&a=update", formData, function(result) {
                if(result.status == 1) {
                    // 更新最新的fortoken
                    $('#formtoken').val(result.formtoken);
                    // 弹出成功提示
                	w_b_modal_success('操作成功');
                } else {
                	w_b_modal_error(result.msg);
                }
            }, 'json');
            return false;
        });
        // 点击返回上一页
        $('.w-back').click(function() {
            var href = $(this).attr('href');
        	w_b_modal_confirm("确认要返回上一页吗？", function() {
        		window.location.href = href;
            });
            return false;
        });
    });
    </script>
    
<?php include $_W['template_path'].'/footer.html.php';?>
