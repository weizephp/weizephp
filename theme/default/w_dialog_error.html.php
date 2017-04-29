<?php
if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}
?><div id="w-dialog-error" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="关闭"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-danger">错误提示</h4>
			</div>
			<div id="w-dialog-error-body" class="modal-body">
				<p></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">确认</button>
			</div>
		</div>
	</div>
</div>
<script>
function w_dialog_error(message) {
    $("#w-dialog-error-body p").html(message);
    $("#w-dialog-error").modal({backdrop: "static"}).off("hidden.bs.modal").on("hidden.bs.modal", function(e) {
        $('body').css("padding-right", "0px");
    });
}
</script>