<?php
if( !defined('IN_WEIZEPHP') ) {
    exit('Access Denied');
}
?><!DOCTYPE HTML>
<html>
    <head>
        <?php include $wconfig['theme_path'] . '/admin/head.inc.php';echo admin_head('管理日志');?>
    </head>
    <body>
        <?php include $wconfig['theme_path'] . '/admin/header.html.php';?>
            <h4 class="w-h4">管理日志</h4>
            <form class="w-form" method="post" action="?m=system&a=adminlogdelete">
                <input type="hidden" name="formtoken" value="<?php echo $wuser->formtoken;?>" />
                <div class="table-responsive">
                    <table class="table w-table">
                        <thead class="w-thead-1">
                            <tr>
                                <th><label><input type="checkbox" class="select_all"/></label></th>
                                <th>#ID</th>
                                <th>操作者</th>
                                <th>操作日期</th>
                                <th>IP地址</th>
                                <th>操作详情</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($logs as $val) {
                                echo '<tr>' .
                                         '<td><label><input type="checkbox" class="check_arr" name="logid[]" value="'. $val['logid'] .'"/></label></td>' .
                                         '<td>'. $val['logid'] .'</td>' .
                                         '<td>'. $val['username'] .'</td>' .
                                         '<td>'. date('Y-m-d H:i:s', $val['logtime']) .'</td>' .
                                         '<td>'. $val['ip'] .'</td>' .
                                         '<td>'. htmlspecialchars($val['loginfo']) .'</td>' .
                                     '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </form>
            <button type="button" class="btn btn-success w-delete">删 除</button>
            <nav><?php echo $output;?></nav>
        <?php include $wconfig['theme_path'] . '/admin/footer.html.php';?>
        <?php include $wconfig['theme_path'] . '/w_dialog_error.html.php';?>
        <?php include $wconfig['theme_path'] . '/w_dialog_confirm.html.php';?>
        <script type="text/javascript">
        $(document).ready(function() {
            // 全选或全取消
            $('.select_all').click(function(e) {
                if( $(this).prop('checked') == true ) {
                    $('.check_arr').prop('checked', true);
                } else {
                    $('.check_arr').prop('checked', false);
                }
            });
            // 删除提交
            $('.w-delete').click(function(e) {
                w_dialog_confirm("一旦删除将无法恢复，确定要删除吗？", function() {
                    $.post("?m=system&a=adminlogdelete", $('.w-form').serialize(), function(data) {
                        if( data.status == 1 ) {
                            window.location.href = self.location.href;
                        } else {
                            w_dialog_error( data.msg );
                        }
                    }, "json");
                });
            });
        });
        </script>
    </body>
</html>