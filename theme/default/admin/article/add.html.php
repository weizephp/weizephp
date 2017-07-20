<?php
if( !defined('IN_WEIZEPHP') ) {
    exit('Access Denied');
}
?><!DOCTYPE HTML>
<html>
    <head>
        <?php include $wconfig['theme_path'] . '/admin/head.inc.php';echo admin_head('文章添加');?>
        <link rel="stylesheet" type="text/css" href="<?php echo $wconfig['public_path'];?>/webuploader/webuploader.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo $wconfig['theme_skin'];?>/css/w-webuploader.css"/>
    </head>
    <body>
        <?php include $wconfig['theme_path'] . '/admin/header.html.php';?>
            <h4 class="w-h4">文章添加</h4>
            <form class="form-horizontal w-form" autocomplete="off">
                <div class="form-group">
                    <label for="title" class="col-sm-2 control-label">标题<span class="text-danger">(必填)</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="title" name="title" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="subtitle" class="col-sm-2 control-label">副标题</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="subtitle" name="subtitle" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="cid" class="col-sm-2 control-label">分类<span class="text-danger">(必选)</span></label>
                    <div class="col-sm-10">
                        <select class="form-control" id="cid" name="cid">
                            <option value="0">≡ 请选择 ≡</option>
                            <?php
                            foreach($categories as $v) {
                                echo '<option value="'. $v['cid'] .'">'. $v['name_fmt'] .'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="status" class="col-sm-2 control-label">是否显示</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="status" name="status">
                            <option value="0">否</option>
                            <option value="1" selected="selected">是</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="displayorder" class="col-sm-2 control-label">排序</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="displayorder" name="displayorder" value="10" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">封面图</label>
                    <div class="col-sm-10">
                        <div class="w-uploader-box">
                            <div id="fileList" class="uploader-list"></div>
                            <div class="clearfix"></div>
                            <div id="filePicker">选择图片</div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="jumpurl" class="col-sm-2 control-label">跳转网址</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="jumpurl" name="jumpurl" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="source" class="col-sm-2 control-label">文章来源</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="source" name="source" />
                        <span class="help-block">如：新华网</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="sourceurl" class="col-sm-2 control-label">文章来源网址</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="sourceurl" name="sourceurl" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="keywords" class="col-sm-2 control-label">关键字(SEO用)</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="keywords" name="keywords" />
                        <span class="help-block">限100个字符</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">简介<span class="text-danger">(必填)</span></label>
                    <div class="col-sm-10">
                        <textarea rows="3" class="form-control" id="description" name="description"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="content" class="col-sm-2 control-label">内容<span class="text-danger">(必填)</span></label>
                    <div class="col-sm-10">
                        <script id="w-ueditor-container" name="content" type="text/plain"></script>
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
        <script type="text/javascript" src="<?php echo $wconfig['public_path'];?>/ueditor/ueditor.config.js"></script>
        <script type="text/javascript" src="<?php echo $wconfig['public_path'];?>/ueditor/ueditor.all.min.js"></script>
        <script type="text/javascript" src="<?php echo $wconfig['public_path'];?>/webuploader/webuploader.min.js"></script>
        <script type="text/javascript">
        $(document).ready(function() {
            // 实例化编辑器
            var ue = UE.getEditor('w-ueditor-container', {
                //topOffset: 50,
                //autoFloatEnabled: false,
                serverUrl: "<?php echo w_get_siteurl() . 'general.php?m=ueditor&a=controlleradmin&formtoken=' . $wuser->formtoken;?>",
                initialFrameHeight: 600
            });
            // 编辑器全屏bug处理
            ue.addListener('fullscreenchanged', function(event, isFullScreen) {
                $("#w-ueditor-container .edui-editor").css('top', '0px');
            });
            
            //-------------------------------
            
            // 定义封面图上传变量
            var $list = $('#fileList'),
                ratio = window.devicePixelRatio || 1,
                thumbnailWidth = 100 * ratio,
                thumbnailHeight = 100 * ratio,
                uploader;
            
            // 初始化Web Uploader
            uploader = WebUploader.create({
                auto: true,
                swf: '<?php echo $wconfig['public_path'];?>/webuploader/Uploader.swf',
                server: 'general.php?m=upload&a=admin&folder=article&formtoken=<?php echo $wuser->formtoken;?>',
                pick: '#filePicker',
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png'
                }
            });
            
            // 当有文件添加进来的时候
            uploader.on( 'fileQueued', function( file ) {
                var $li = $(
                        '<div id="' + file.id + '" class="file-item thumbnail">' +
                            '<img>' +
                            '<div class="remove">×</div>' +
                            '<div class="info">' + file.name + '</div>' +
                        '</div>'
                        ),
                    $img = $li.find('img');
                
                //$list.append( $li );
                $list.html( $li );
                
                // 移除封面图
                $li.on('click', '.remove', function() {
                    $(this).parent().remove();
                    //uploader.removeFile( file );
                });
                
                // 创建缩略图
                uploader.makeThumb( file, function( error, src ) {
                    if ( error ) {
                        $img.replaceWith('<span>不能预览</span>');
                        return;
                    }
                    $img.attr( 'src', src );
                }, thumbnailWidth, thumbnailHeight );
            });

            // 文件上传过程中创建进度条实时显示。
            uploader.on( 'uploadProgress', function( file, percentage ) {
                var $li = $( '#'+file.id ), $percent = $li.find('.progress span');
                // 避免重复创建
                if ( !$percent.length ) {
                    $percent = $('<p class="progress"><span></span></p>').appendTo( $li ).find('span');
                }
                $percent.css( 'width', percentage * 100 + '%' );
            });

            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader.on( 'uploadSuccess', function( file, response ) {
                //$( '#'+file.id ).addClass('upload-state-done');
                uploader.reset(); // 重置队列
                if( response.status == 1 ) {
                    $( '#'+file.id ).append( '<input type="hidden" name="pic_id" value="'+ response.id +'">' );
                } else {
                    $( '#'+file.id ).remove();
                    w_dialog_error( response.msg );
                }
            });

            // 文件上传失败，现实上传出错。
            uploader.on( 'uploadError', function( file ) {
                var $li = $( '#'+file.id ), $error = $li.find('div.error');
                // 避免重复创建
                if ( !$error.length ) {
                    $error = $('<div class="error"></div>').appendTo( $li );
                }
                $error.text('上传失败');
            });

            // 完成上传完了，成功或者失败，先删除进度条。
            uploader.on( 'uploadComplete', function( file ) {
                $( '#'+file.id ).find('.progress').remove();
            });
            
            //-------------------------------
            
            // 提交数据
            $('.w-form').submit(function() {
                $.post("?m=article&a=add", $(this).serialize(), function(result) {
                    if(result.status == 1) {
                        w_dialog_success(result.msg, '?m=article&a=list');
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