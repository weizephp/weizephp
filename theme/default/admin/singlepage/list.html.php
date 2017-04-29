<?php
if( !defined('IN_WEIZEPHP') ) {
    exit('Access Denied');
}
?><!DOCTYPE HTML>
<html>
    <head>
        <?php include $wconfig['theme_path'] . '/admin/head.inc.php';echo admin_head('单页列表');?>
    </head>
    <body>
        <?php include $wconfig['theme_path'] . '/admin/header.html.php';?>
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
                        foreach($singlepages as $v) {
                            $status_str = $v['status'] == 1 ? '是' : '<span class="text-danger">否</span>';
                            echo '<tr>' .
                                '<td>'. $v['spid'] .'</td>' .
                                '<td>'. htmlspecialchars($v['title']) .'</td>' .
                                '<td>'. $status_str .'</td>' .
                                '<td>' .
                                    '<a class="btn btn-info btn-xs w-a-btn" href="index.php?m=singlepage&a=view&spid='. $v['spid'] .'" target="_blank">查看</a>' .
                                    ' - <a class="btn btn-success btn-xs w-a-btn" href="?m=singlepage&a=update&spid='. $v['spid'] .'">更新</a>' .
                                    ' - <a class="btn btn-danger btn-xs w-a-btn w-delete" href="?m=singlepage&a=delete&spid='. $v['spid'] .'&formtoken='. $wuser->formtoken .'">删除</a>' .
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