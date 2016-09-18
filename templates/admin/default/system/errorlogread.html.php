<?php
if(!defined('IN_WEIZEPHP')){exit('Access Denied');}
include $_W['template_path'].'/header.html.php';
?>              
    
    <h4 class="w-h4">查看错误日志</h4>
    
    <div class="table-responsive">
        <table class="table w-table">
            <thead class="w-thead-1">
                <tr>
                    <th><?php echo $file;?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($lines as $v) {
                    echo '<tr><td>'. htmlspecialchars(htmlspecialchars_decode($v)) .'</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <a class="btn btn-success w-a-btn" href="?m=system&a=errorlog">返回上层</a>
    
<?php include $_W['template_path'].'/footer.html.php';?>
