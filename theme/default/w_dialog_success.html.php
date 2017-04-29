<?php
if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}
?><div id="w-dialog-success" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="关闭"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-success">成功提示</h4>
			</div>
			<div id="w-dialog-success-body" class="modal-body">
				<p></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">确认</button>
			</div>
		</div>
	</div>
</div>
<script>
function w_dialog_success(message, url) {
    $("#w-dialog-success-body p").html(message);
    $("#w-dialog-success").modal({backdrop: "static"}).off("hidden.bs.modal").on("hidden.bs.modal", function(e) {
        $('body').css("padding-right", "0px");
        if( url ) {
            window.location.href = url;
        }
    });
}
</script>