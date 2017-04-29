<?php
/**
 * 通用应用
 */

// 时间记录
// $w_start_time = microtime(true);

// 是否启用权限控制（1开启，0关闭）
define('W_PERMISSION', 1);
// 定义应用
define('W_APPNAME', 'general');
// 引入框架
include 'weize/framework.php';

// 计算运行时间
// $w_end_time = microtime(true);
// $w_run_diff = $w_end_time - $w_start_time;
// if( $isajax == 0 ) {
	// echo "<!-- {$w_run_diff} -->";
// }
?>
