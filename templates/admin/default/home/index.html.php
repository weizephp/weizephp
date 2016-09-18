<?php
if(!defined('IN_WEIZEPHP')){exit('Access Denied');}
include $_W['template_path'].'/header.html.php';
?>              
    
    <div class="table-responsive">
        <table class="table w-table">
            <thead class="w-thead">
                <tr>
                    <th colspan="2">系统信息</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>WeizePHP 程序版本</td>
                    <td><?php echo $_W['version'];?> <a target="_blank" href="http://weizephp.75hh.com/">查看最新版本</a></td>
                </tr>
                <tr>
                    <td>服务器系统及 PHP</td>
                    <td><?php echo $server_info;?></td>
                </tr>
                <tr>
                    <td>服务器软件</td>
                    <td><?php echo $server_soft;?></td>
                </tr>
                <tr>
                    <td>服务器 MySQL 版本</td>
                    <td><?php echo $db_version;?></td>
                </tr>
                <tr>
                    <td>当前数据库大小</td>
                    <td><?php echo $db_size;?></td>
                </tr>
                <tr>
                    <td>上传许可</td>
                    <td><?php echo $file_upload;?></td>
                </tr>
            </tbody>
            
            <thead class="w-thead">
                <tr>
                    <th colspan="2">WeizePHP 相关信息</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>版权问题</td>
                    <td><a target="_blank" href="http://opensource.org/licenses/MIT">MIT License.</a> (可以自由商业化的哟^_^)</td>
                </tr>
                <tr>
                    <td>项目总策划</td>
                    <td>韦泽</td>
                </tr>
                <tr>
                    <td>特别感谢好友</td>
                    <td>黄权</td>
                </tr>
                <tr>
                    <td>产品设计与研发团队</td>
                    <td>期待您的参与，让这个软件更强壮</td>
                </tr>
                <tr>
                    <td>界面与用户体验团队</td>
                    <td>如果您觉得我们的界面很差和用户体验很不好，请您参与完善…</td>
                </tr>
                <tr>
                    <td>特别鸣谢</td>
                    <td>赞助的单位和个人将会出现在这里...</td>
                </tr>
                <tr>
                    <td>系统交流QQ群</td>
                    <td>297634163（WeizePHP框架）</td>
                </tr>
            </tbody>
        </table>
    </div>
    
<?php include $_W['template_path'].'/footer.html.php';?>
