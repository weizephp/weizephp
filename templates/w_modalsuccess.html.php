<div class="modal fade" id="w-b-modal-success" tabindex="-1" role="dialog" aria-labelledby="w-b-modal-label-success">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close w-b-modal-success-close" aria-label="关闭"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-success" id="w-b-modal-label-success">成功提示</h4>
			</div>
			<div class="modal-body" id="w-b-modal-body-success">
				<p></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success w-b-modal-success-close">确认</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
var _w_b_modal_success_callback = '';

function w_b_modal_success(message, callback) {
	$('#w-b-modal-body-success p').html(message);
	$('#w-b-modal-success').modal('show');
	if(typeof(callback) == 'function') {
		_w_b_modal_success_callback = callback;
    }
}

$('.w-b-modal-success-close').click(function() {
	$('#w-b-modal-success').modal('hide');
	if(typeof(_w_b_modal_success_callback) == 'function') {
		_w_b_modal_success_callback();
		_w_b_modal_success_callback = '';
	}
});
</script>