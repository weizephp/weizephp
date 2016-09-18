<?php
if(!defined('IN_WEIZEPHP')){exit('Access Denied');}
include $_W['template_path'].'/header.html.php';
?>              
    
    <h4 class="w-h4">文章列表</h4>
    
    <div class="row w-row">
        <div class="col-lg-6">
            <div class="input-group">
                <input type="text" class="form-control" disabled="disabled" placeholder="请选分类..." value="<?php echo $cur_categoryname;?>"/>
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">按分类查看 <span class="caret"></span></button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="?m=article&a=list">≡ 全部分类 ≡</a></li>
                        <?php
                        foreach($categories as $v) {
                            echo '<li><a href="?m=article&a=list&categoryid='. $v['categoryid'] .'">'. $v['categoryname_fmt'] .'</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <form action="?" method="get">
                <input type="hidden" name="m" value="article"/>
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
                foreach($articles['paged_data'] as $v) {
                    $status_str = $v['status'] == 1 ? '是' : '<span class="text-danger">否</span>';
                    echo '<tr>' .
                        '<td>'. $v['aid'] .'</td>' .
                        '<td>'. htmlspecialchars($v['title']) .'</td>' .
                        '<td>'. $status_str .'</td>' .
                        '<td>' .
                            '<a class="btn btn-info btn-xs w-a-btn" href="content.php?m=article&a=view&aid='. $v['aid'] .'" target="_blank">查看</a>' .
                            ' - <a class="btn btn-success btn-xs w-a-btn" href="?m=article&a=update&aid='. $v['aid'] .'">更新</a>' .
                            ' - <a class="btn btn-danger btn-xs w-a-btn w-delete" href="?m=article&a=delete&aid='. $v['aid'] .'&formtoken='. $formtoken .'">删除</a>' .
                        '</td>' .
                    '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <!-- 分页 -->
    <nav><?php echo $articles['paged_html'];?></nav>
    
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
