<?php
if(!defined('IN_WEIZEPHP')){exit('Access Denied');}
include $_W['template_path'].'/header.html.php';
?>              
    
    <h4 class="w-h4">单页列表</h4>
    
    <div class="row w-row">
        <div class="col-lg-6">
            <form action="?" method="get">
                <input type="hidden" name="m" value="singlepage"/>
                <input type="hidden" name="a" value="list"/>
                <div class="input-group">
                    <input type="text" class="form-control" name="wd" placeholder="标题关键字..." value="<?php echo $wd;?>"/>
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">搜索!</button>
                    </span>
                </div>
            </form>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table w-table">
            <thead class="w-thead-1">
                <tr>
                    <th>ID</th>
                    <th>标题</th>
                    <th>显示</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($lists['paged_data'] as $v) {
                    $status_str = $v['status'] == 1 ? '是' : '<span class="text-danger">否</span>';
                    echo '<tr>' .
                        '<td>'. $v['spid'] .'</td>' .
                        '<td>'. htmlspecialchars($v['title']) .'</td>' .
                        '<td>'. $status_str .'</td>' .
                        '<td>' .
                            '<a class="btn btn-info btn-xs w-a-btn" href="content.php?m=singlepage&a=view&spid='. $v['spid'] .'" target="_blank">查看</a>' .
                            ' - <a class="btn btn-success btn-xs w-a-btn" href="?m=singlepage&a=update&spid='. $v['spid'] .'">更新</a>' .
                            ' - <a class="btn btn-danger btn-xs w-a-btn w-delete" href="?m=singlepage&a=delete&spid='. $v['spid'] .'&formtoken='. $formtoken .'">删除</a>' .
                        '</td>' .
                    '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <!-- 分页 -->
    <nav><?php echo $lists['paged_html'];?></nav>
    
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
