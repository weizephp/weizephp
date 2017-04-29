<?php
if( !defined('IN_WEIZEPHP') ) {
    exit('Access Denied');
}
?><!DOCTYPE HTML>
<html>
    <head>
        <?php include $wconfig['theme_path'] . '/admin/head.inc.php';echo admin_head('用户列表');?>
    </head>
    <body>
        <?php include $wconfig['theme_path'] . '/admin/header.html.php';?>
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
                        foreach($users as $v) {
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
                                        ' - <a class="btn btn-danger btn-xs w-a-btn w-delete" href="?m=user&a=delete&uid='. $v['uid'] .'&formtoken='. $wuser->formtoken .'">删除</a>' .
                                    '</td>' .
                                '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <nav><?php echo $pagination_output;?></nav>
        <?php include $wconfig['theme_path'] . '/admin/footer.html.php';?>
        <?php include $wconfig['theme_path'] . '/w_dialog_success.html.php';?>
        <?php include $wconfig['theme_path'] . '/w_dialog_error.html.php';?>
        <?php include $wconfig['theme_path'] . '/w_dialog_confirm.html.php';?>
        <script type="text/javascript">
        $(document).ready(function() {
            $('.w-delete').click(function() {
                var href = $(this).attr('href');
                w_dialog_confirm("一旦删除，将不能恢复，确认吗？", function() {
                    $.getJSON(href, function(data) {
                        if( data.status == 1 ) {
                            window.location.reload(true);
                        } else {
                            w_dialog_error(data.msg);
                        }
                    });
                });
                return false;
            });
        });
        </script>
    </body>
</html>