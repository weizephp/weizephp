<?php
if(!defined('IN_WEIZEPHP')){exit('Access Denied');}
include $_W['template_path'].'/header.html.php';
?>              
    
    <h4 class="w-h4">清理数据</h4>
    
    <div class="table-responsive">
        <table class="table w-table">
            <thead class="w-thead-1">
                <tr>
                    <th>名称</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>清理上传的临时文件</td>
                    <td><a class="btn btn-success btn-sm w-a-btn" href="?m=system&a=cleardata&operation=upload&formtoken=<?php echo $formtoken;?>">清理</a></td>
                </tr>
                <tr>
                    <td>清理编辑器的临时文件</td>
                    <td><a class="btn btn-success btn-sm w-a-btn" href="?m=system&a=cleardata&operation=ueditor&formtoken=<?php echo $formtoken;?>">清理</a></td>
                </tr>
                <tr>
                    <td>清理缩略图</td>
                    <td><a class="btn btn-success btn-sm w-a-btn" href="?m=system&a=cleardata&operation=thumb&formtoken=<?php echo $formtoken;?>">清理</a></td>
                </tr>
                <tr>
                    <td>清理缓存</td>
                    <td><a class="btn btn-success btn-sm w-a-btn" href="?m=system&a=cleardata&operation=cache&formtoken=<?php echo $formtoken;?>">清理</a></td>
                </tr>
            </tbody>
        </table>
    </div>
    
<?php include $_W['template_path'].'/footer.html.php';?>
