<?php
if(!defined('IN_WEIZEPHP')){exit('Access Denied');}
include $_W['template_path'].'/header.html.php';
?>              
    
    <h4 class="w-h4">错误日志</h4>
    
    <div class="table-responsive">
        <table class="table w-table">
            <thead class="w-thead-1">
                <tr>
                    <th>日志文件</th>
                    <th>操 作</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($files_arr as $v) {
                    echo '<tr>' .
                             '<td>'. $v .'</td>' .
                             '<td>' .
                                 '<a href="?m=system&a=errorlogread&file='. $v .'">[查 看]</a>' .
                                 '- <a class="w-delete" href="?m=system&a=errorlogdelete&file='. $v .'&formtoken='. $formtoken .'">[删 除]</a>' .
                             '</td>' .
                         '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <?php include W_ROOT_PATH . '/templates/w_modals.html.php';?>
    <script type="text/javascript">
    $(document).ready(function() {
        // 删除
        $('.w-delete').click(function() {
            var redirect = $(this).attr('href');
            w_b_modal_confirm("一旦删除将无法恢复，确定要删除吗？", function() {
            	window.location.href = redirect;
            });
            return false;
        });
    });
    </script>
    
<?php include $_W['template_path'].'/footer.html.php';?>
