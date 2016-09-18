<?php
if(!defined('IN_WEIZEPHP')){exit('Access Denied');}
include $_W['template_path'].'/header.html.php';
?>              
    
    <h4 class="w-h4">分类列表</h4>
    
    <div class="table-responsive">
        <table class="table w-table">
            <thead class="w-thead-1">
                <tr>
                    <th>ID</th>
                    <th>名称</th>
                    <th>显示</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($categories as $v) {
                    $status_str = $v['status'] == 1 ? '是' : '<span class="text-danger">否</span>';
                    echo '<tr>' .
                        '<td>'. $v['categoryid'] .'</td>' .
                        '<td>'. $v['categoryname_fmt'] .'</td>' .
                        '<td>'. $status_str .'</td>' .
                        '<td>' .
                            '<a class="btn btn-success btn-xs w-a-btn" href="?m=article&a=categoryupdate&categoryid='. $v['categoryid'] .'">更新</a>' .
                            ' - <a class="btn btn-danger btn-xs w-a-btn w-delete" href="?m=article&a=categorydelete&categoryid='. $v['categoryid'] .'&formtoken='. $formtoken .'">删除</a>' .
                        '</td>' .
                    '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <?php include W_ROOT_PATH . '/templates/w_modalconfirm.html.php';?>
    <script type="text/javascript">
    $(document).ready(function() {
        $('.w-delete').click(function() {
            var href = $(this).attr('href');
            w_b_modal_confirm("确认删除吗？", function() {
            	window.location.href = href;
            });
            return false;
        });
    });
    </script>
    
<?php include $_W['template_path'].'/footer.html.php';?>
