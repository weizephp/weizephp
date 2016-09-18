<?php if(!defined('IN_WEIZEPHP')){exit('Access Denied');}?><!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes"/>
        <title>联系作者_WeizePHP框架</title>
        <meta name="description" content="联系WeizePHP框架作者"/>
        <link href="<?php echo $_W['public_path'];?>/css/bootstrap.min.css" rel="stylesheet"/>
		<link href="<?php echo $_W['skin_path'];?>/css/weizephp-doc.css" rel="stylesheet"/>
        <script src="<?php echo $_W['public_path'];?>/js/jquery.min.js"></script>
        <script src="<?php echo $_W['public_path'];?>/js/bootstrap.min.js"></script>
    </head>
    
    <body>
	    <?php include $_W['template_path'].'/header.html.php';?>
		
		    <h3>联系作者</h3>
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<tbody>
						<tr>
							<td scope="row">QQ群</td>
							<td>297634163(WeizePHP框架)</td>
						</tr>
						<tr>
							<td scope="row">Github</td>
							<td><a target="_blank" href="https://github.com/weizephp">https://github.com/weizephp</a></td>
						</tr>
						<tr>
							<td scope="row">Twitter</td>
							<td><a target="_blank" href="https://twitter.com/weizesw">https://twitter.com/weizesw</a></td>
						</tr>
						<tr>
							<td scope="row">微博</td>
							<td><a target="_blank" href="https://twitter.com/weizesw">http://weibo.com/weize888</a></td>
						</tr>
						<tr>
							<td scope="row">E-mail</td>
							<td>310472156@qq.com</td>
						</tr>
						<tr>
							<td scope="row">QQ</td>
							<td>310472156</td>
						</tr>
					</tbody>
				</table>
			</div>
		    
		<?php include $_W['template_path'].'/footer.html.php';?>
    </body>
</html>
