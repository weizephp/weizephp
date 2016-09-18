<div class="modal fade" id="w-b-modal-error" tabindex="-1" role="dialog" aria-labelledby="w-b-modal-label-error">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close w-b-modal-error-close" aria-label="关闭"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-danger" id="w-b-modal-label-error">错误提示</h4>
			</div>
			<div class="modal-body" id="w-b-modal-body-error">
				<p></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger w-b-modal-error-close">确认</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
var _w_b_modal_error_callback = '';

function w_b_modal_error(message, callback) {
	$('#w-b-modal-body-error p').html(message);
	$('#w-b-modal-error').modal('show');
	if(typeof(callback) == 'function') {
		_w_b_modal_error_callback = callback;
    }
}

$('.w-b-modal-error-close').click(function() {
	$('#w-b-modal-error').modal('hide');
	if(typeof(_w_b_modal_error_callback) == 'function') {
		_w_b_modal_error_callback();
		_w_b_modal_error_callback = '';
	}
});
</script>