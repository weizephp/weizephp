<?php if(!defined('IN_WEIZEPHP')){exit('Access Denied');}?><!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes"/>
        <title>下载_WeizePHP框架</title>
        <meta name="keywords" content="weizephp下载,weizephp框架下载"/>
        <meta name="description" content="weizephp框架下载"/>
        <link href="<?php echo $_W['public_path'];?>/css/bootstrap.min.css" rel="stylesheet"/>
		<link href="<?php echo $_W['skin_path'];?>/css/weizephp-doc.css" rel="stylesheet"/>
        <script src="<?php echo $_W['public_path'];?>/js/jquery.min.js"></script>
        <script src="<?php echo $_W['public_path'];?>/js/bootstrap.min.js"></script>
    </head>
    
    <body>
	    <?php include $_W['template_path'].'/header.html.php';?>
		
		    <h3>WeizePHP下载</h3>
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Version</th>
							<th>Release Date</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td scope="row">WeizePHP_3.0_SC_UTF8<span class="text-danger">(最新)</span></td>
							<td>2016-09-18</td>
							<td><a target="_blank" href="http://weizephp.75hh.com/data/download/WeizePHP_3.0_SC_UTF8.zip">[下载]</a></td>
						</tr>
						<tr>
							<td scope="row">WeizePHP_2.1_SC_UTF8</td>
							<td>2014-08-10</td>
							<td><a target="_blank" href="http://pan.baidu.com/s/1hqikYQo">[下载]</a></td>
						</tr>
						<tr>
							<td scope="row">WeizePHP_2.0_SC_UTF8</td>
							<td>2013-05-23</td>
							<td><a target="_blank" href="http://pan.baidu.com/s/1pJ5FqQZ">[下载]</a></td>
						</tr>
						<tr>
							<td scope="row">WeizePHP_1.0Beta_SC_UTF8</td>
							<td>2013-03-01</td>
							<td><a target="_blank" href="http://pan.baidu.com/s/1eQELfMi">[下载]</a></td>
						</tr>
					</tbody>
				</table>
			</div>
		    
		<?php include $_W['template_path'].'/footer.html.php';?>
    </body>
</html>
