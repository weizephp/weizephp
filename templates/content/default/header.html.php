<?php if(!defined('IN_WEIZEPHP')){exit('Access Denied');}?>
<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<a class="navbar-brand" href="./">WeizePHP</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
			    <?php
				// 首页
				if($_W['module'] == 'home' && $_W['action'] == 'index') {
					echo '<li class="active"><a href="./">首页</a></li>';
				} else {
					echo '<li><a href="./">首页</a></li>';
				}
				// 下载
				if($_W['module'] == 'home' && $_W['action'] == 'download') {
					echo '<li class="active"><a href="?m=home&a=download">下载</a></li>';
				} else {
					echo '<li><a href="?m=home&a=download">下载</a></li>';
				}
				// 联系作者
				if($_W['module'] == 'home' && $_W['action'] == 'contact') {
					echo '<li class="active"><a href="?m=home&a=contact">联系作者</a></li>';
				} else {
					echo '<li><a href="?m=home&a=contact">联系作者</a></li>';
				}
				?>
				<li class="开发手册">
					<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">开发手册 <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="javascript:void(0);">目录结构</a></li>
						<li><a href="javascript:void(0);">系统入口说明</a></li>
						<li><a href="javascript:void(0);">常量、变量、函数</a></li>
						<li><a href="javascript:void(0);">编码规范</a></li>
						<li><a href="javascript:void(0);">数据表字典</a></li>
						<li role="separator" class="divider"></li>
						<li class="dropdown-header">后台开发</li>
						<li><a href="javascript:void(0);">添加菜单</a></li>
						<li role="separator" class="divider"></li>
						<li class="dropdown-header">模块开发实例</li>
						<li><a href="javascript:void(0);">微信公众平台开发</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>

<div class="container theme-showcase" role="main">
