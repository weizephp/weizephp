<?php
if(!defined('IN_WEIZEPHP')){exit('Access Denied');}
include $_W['template_path'].'/header.html.php';
?>              
    
    <h4 class="w-h4">管理日志</h4>
    
    <form class="w-form" method="post" action="?m=system&a=adminlogdelete">
        <input type="hidden" name="formtoken" value="<?php echo $formtoken;?>"/>
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
                    foreach($logs['paged_data'] as $v) {
                        echo '<tr>' .
                                 '<td><label><input type="checkbox" class="check_arr" name="logid[]" value="'. $v['logid'] .'"/></label></td>' .
                                 '<td>'. $v['logid'] .'</td>' .
                                 '<td>'. $v['username'] .'</td>' .
                                 '<td>'. date('Y-m-d H:i:s', $v['logtime']) .'</td>' .
                                 '<td>'. $v['ip'] .'</td>' .
                                 '<td>'. htmlspecialchars($v['loginfo']) .'</td>' .
                             '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </form>
    
    <button type="button" class="btn btn-success w-delete">删 除</button>
    
    <nav><?php echo $logs['paged_html'];?></nav>
    
    <?php include W_ROOT_PATH . '/templates/w_modals.html.php';?>
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
        $('.w-delete').click(function() {
            w_b_modal_confirm("一旦删除将无法恢复，确定要删除吗？", function() {
            	$('.w-form').submit();
            });
        });
    });
    </script>
    
<?php include $_W['template_path'].'/footer.html.php';?>
