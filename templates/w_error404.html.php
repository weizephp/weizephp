<?php
if(!defined('IN_WEIZEPHP')){exit('Access Denied');}
if(empty($url)) {
    $url = 'javascript:window.history.back();';
}
?><!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes"/>
        <title>404错误页面提示</title>
        <link href="<?php echo $_W['public_path'];?>/css/bootstrap.min.css" rel="stylesheet"/>
		<style type="text/css">
		body {
			background-color: #f5f5f5;
		}
		.weizephp-modal {
			padding: 45px 15px 15px;
		}
		.weizephp-modal .modal {
			position: relative;
			top: auto;
			right: auto;
			bottom: auto;
			left: auto;
			z-index: 1;
			display: block;
		}
		.weizephp-modal .modal-dialog {
			left: auto;
			margin-right: auto;
			margin-left: auto;
		}
		</style>
    </head>
    
    <body>
        <div class="weizephp-modal">
			<div class="modal" tabindex="-1" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<a href="<?php echo $url;?>" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>
							<h4 class="modal-title text-info">404错误提示</h4>
						</div>
						<div class="modal-body">
							<p>服务器没有找到您请求的页面！</p>
						</div>
						<div class="modal-footer">
							<a class="btn btn-info" href="<?php echo $url;?>">返 回</a>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
		</div>
		<script src="<?php echo $_W['public_path'];?>/js/jquery.min.js"></script>
		<script src="<?php echo $_W['public_path'];?>/js/bootstrap.min.js"></script>
    </body>
</html>