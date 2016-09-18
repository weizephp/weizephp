<?php
if(!defined('IN_WEIZEPHP')){exit('Access Denied');}
include $_W['template_path'].'/header.html.php';
?>              
    
    <h4 class="w-h4">角色权限控制</h4>
    <div class="table-responsive">
        <table class="table w-table">
            <thead class="w-thead-1">
                <tr>
                    <th>ID</th>
                    <th>角色名称</th>
                    <th>权限操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($roles as $v) {?>
                <tr>
                    <td><?php echo $v['roleid'];?></td>
                    <td><?php echo $v['rolename'];?></td>
                    <td><a href="?m=user&a=rolepermissionassign&roleid=<?php echo $v['roleid'];?>">[权限分配]</a></td>
                </tr>
                <?php }?>
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    
<?php include $_W['template_path'].'/footer.html.php';?>
