<?php if(!defined('IN_WEIZEPHP')){exit('Access Denied');}?><!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes"/>
        <title>WeizePHP_简单又实用的PHP框架</title>
        <meta name="keywords" content="weizephp,weizephp框架,php框架"/>
        <meta name="description" content="WeizePHP是一款超小的PHP免费开源框架。有后台，有权限管理，有完整的开发手册。入门简单，只要会php语法，就可以快速做网站^_^。"/>
        <link href="<?php echo $_W['public_path'];?>/css/bootstrap.min.css" rel="stylesheet"/>
		<link href="<?php echo $_W['skin_path'];?>/css/weizephp-doc.css" rel="stylesheet"/>
        <script src="<?php echo $_W['public_path'];?>/js/jquery.min.js"></script>
        <script src="<?php echo $_W['public_path'];?>/js/bootstrap.min.js"></script>
    </head>
    
    <body>
	    <?php include $_W['template_path'].'/header.html.php';?>
		
			<div class="jumbotron">
		        <h1>WeizePHP <span class="text-danger">3.0</span></h1>
		        <p>WeizePHP是一款超小的PHP免费开源框架。有后台，有权限管理，有完整的开发手册。入门简单，只要会php语法，就可以快速做网站^_^。</p>
		        <p class="text-center"><a class="btn btn-primary btn-lg" href="?m=home&a=download" role="button">立马下载 &raquo;</a></p>
		   </div>
		    
		    <div class="page-header">
		        <h3>WeizePHP演示</h3>
		    </div>
		    <div class="well">
		        <p>账号：weizephp </p>
		        <p>密码：weizephp </p>
		        <p>地址：<a  target="_blank" href="http://weizephp.75hh.com/admin.php">http://weizephp.75hh.com/admin.php</a></p>
		    </div>
		    
		    <div class="page-header">
		        <h3>WeizePHP特点</h3>
		    </div>
		    <div class="row">
		    	<div class="col-sm-4">
		    		<div role="alert" class="alert alert-danger w-home-alert">
						<h4>版权</h4>
						<p>软件使用相对宽松的MIT协议，可以自由的商用啦^_^。</p>
					</div>
		    	</div>
		    	<div class="col-sm-4">
		    		<div role="alert" class="alert alert-success w-home-alert">
						<h4>缺点</h4>
						<p>很多框架一来就告诉你，他们框架很优秀，用了他们的框架什么都能做。但我负责任的告诉你，WeizePHP有一定的局限性，特殊的需求，可能需要修改核心代码。核心代码就几行而已，想怎么改，就怎么改。</p>
					</div>
		    	</div>
		    	<div class="col-sm-4">
		    		<div role="alert" class="alert alert-info w-home-alert">
						<h4>优点</h4>
						<p>简单、易用、好维护！！！</p>
					</div>
		    	</div>
		    </div>
		    
		<?php include $_W['template_path'].'/footer.html.php';?>
    </body>
</html>
