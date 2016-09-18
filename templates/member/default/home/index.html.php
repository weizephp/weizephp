<?php
if(!defined('IN_WEIZEPHP')){exit('Access Denied');}
include $_W['template_path'].'/header.html.php';
?>
    
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th colspan="2">用户中心 - 首页（大家自己去设计更漂亮的页面吧...）</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td width="80">账号</td>
                    <td>
					    <?php echo $_W['user']['username'];?>
						- <a href="?m=signin&a=signout">[登出]</a>
					</td>
                </tr>
				<tr>
                    <td>真名</td>
                    <td><?php echo $_W['user']['realname'];?></td>
                </tr>
				<tr>
                    <td>积分</td>
                    <td><?php echo $_W['user']['points'];?></td>
                </tr>
				<tr>
                    <td>余额</td>
                    <td><?php echo $_W['user']['balances'];?></td>
                </tr>
            </tbody>
        </table>
    </div>
    
<?php include $_W['template_path'].'/footer.html.php';?>
