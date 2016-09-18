<?php
if(!defined('IN_WEIZEPHP')){exit('Access Denied');}
include $_W['template_path'].'/header.html.php';
?>              
    
    <h4 class="w-h4">用户列表</h4>
    
    <div class="table-responsive">
        <table class="table w-table">
            <thead class="w-thead-1">
                <tr>
                    <th>UID</th>
                    <th>角色</th>
                    <th>用户名</th>
                    <th>邮箱</th>
                    <th>启用</th>
                    <th>最后登录时间</th>
                    <th>最后登录IP</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($users['paged_data'] as $v) {
                    $status_str = $v['status'] == 1 ? '是' : '<span class="text-danger">否</span>';
                    echo '<tr>' .
                        '<td>'. $v['uid'] .'</td>' .
                        '<td>'. $v['rolename'] .'</td>' .
                        '<td>'. $v['username'] .'</td>' .
                        '<td>'. $v['email'] .'</td>' .
                        '<td>'. $status_str .'</td>' .
                        '<td>'. date('Y-m-d H:i:s', $v['lastlogintime']) .'</td>' .
                        '<td>'. $v['lastloginip'] .'</td>' .
                        '<td>' .
                            '<a class="btn btn-success btn-xs w-a-btn" href="?m=user&a=update&uid='. $v['uid'] .'">修改</a>' .
                            ' - <a class="btn btn-danger btn-xs w-a-btn w-delete" href="?m=user&a=delete&uid='. $v['uid'] .'&formtoken='. $formtoken .'">删除</a>' .
                        '</td>' .
                    '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <!-- 分页 -->
    <nav><?php echo $users['paged_html'];?></nav>
    
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
