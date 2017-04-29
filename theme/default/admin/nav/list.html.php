<?php
if( !defined('IN_WEIZEPHP') ) {
    exit('Access Denied');
}
?><!DOCTYPE HTML>
<html>
    <head>
        <?php include $wconfig['theme_path'] . '/admin/head.inc.php';echo admin_head('导航列表');?>
    </head>
    <body>
        <?php include $wconfig['theme_path'] . '/admin/header.html.php';?>
            <h4 class="w-h4">导航列表</h4>
            <div class="table-responsive">
                <table class="table w-table">
                    <thead class="w-thead-1">
                        <tr>
                            <th>ID</th>
                            <th>名称</th>
                            <th>启用</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($nav_list as $val) {
                            $status_str = $val['status'] == 1 ? '是' : '<span class="text-danger">否</span>';
                            echo '<tr>' .
                                '<td>'. $val['nid'] .'</td>' .
                                '<td>'. $val['name'] .'</td>' .
                                '<td>'. $status_str .'</td>' .
                                '<td>' .
                                    '<a class="btn btn-success btn-xs w-a-btn" href="?m=nav&a=update&nid='. $val['nid'] .'">更新</a>' .
                                    ' - <a class="btn btn-danger btn-xs w-a-btn w-delete" href="?m=nav&a=delete&nid='. $val['nid'] .'&formtoken='. $wuser->formtoken .'">删除</a>' .
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
                var href = $(this).attr('href');
                w_dialog_confirm("确认删除吗？", function() {
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