<?php
if( !defined('IN_WEIZEPHP') ) {
    exit('Access Denied');
}
?><!DOCTYPE HTML>
<html>
    <head>
        <?php include $wconfig['theme_path'] . '/admin/head.inc.php';echo admin_head('错误日志');?>
    </head>
    <body>
        <?php include $wconfig['theme_path'] . '/admin/header.html.php';?>
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
                                         '<a href="?m=system&a=errorlogread&file='. $v .'&formtoken='. $wuser->formtoken .'">[查 看]</a>' .
                                         '- <a class="w-delete" href="?m=system&a=errorlogdelete&file='. $v .'&formtoken='. $wuser->formtoken .'">[删 除]</a>' .
                                     '</td>' .
                                 '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php include $wconfig['theme_path'] . '/admin/footer.html.php';?>
        <?php include $wconfig['theme_path'] . '/w_dialog_error.html.php';?>
        <?php include $wconfig['theme_path'] . '/w_dialog_confirm.html.php';?>
        <script type="text/javascript">
        $(document).ready(function() {
            $('.w-delete').click(function() {
                var deleteUrl = $(this).attr('href');
                w_dialog_confirm("一旦删除将无法恢复，确定要删除吗？", function() {
                    $.getJSON(deleteUrl, function(data) {
                        if( data.status == 1 ) {
                            window.location.href = self.location.href;
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