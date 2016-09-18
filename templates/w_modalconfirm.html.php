<div class="modal fade" id="w-b-modal-confirm" tabindex="-1" role="dialog" aria-labelledby="w-b-modal-label-confirm">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close w-b-modal-confirm-cancel" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-danger" id="w-b-modal-label-confirm">确认提示</h4>
			</div>
			<div class="modal-body" id="w-b-modal-body-confirm">
				<p></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default w-b-modal-confirm-cancel">取消</button>
				<button type="button" class="btn btn-danger" id="w-b-modal-confirm-ok">确认</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
var _w_b_modal_confirm_ok_callback     = '';
var _w_b_modal_confirm_cancel_callback = '';

function w_b_modal_confirm(message, okCallback, cancelCallback) {
	$('#w-b-modal-body-confirm p').html(message);
	$('#w-b-modal-confirm').modal('show');
	if(typeof(okCallback) == 'function') {
		_w_b_modal_confirm_ok_callback = okCallback;
    }
	if(typeof(cancelCallback) == 'function') {
		_w_b_modal_confirm_cancel_callback = cancelCallback;
    }
}

$('#w-b-modal-confirm-ok').click(function() {
	$('#w-b-modal-confirm').modal('hide');
	if(typeof(_w_b_modal_confirm_ok_callback) == 'function') {
		_w_b_modal_confirm_ok_callback();
		_w_b_modal_confirm_ok_callback = '';
	}
});

$('.w-b-modal-confirm-cancel').click(function() {
	$('#w-b-modal-confirm').modal('hide');
	if(typeof(_w_b_modal_confirm_cancel_callback) == 'function') {
		_w_b_modal_confirm_cancel_callback();
		_w_b_modal_confirm_cancel_callback = '';
	}
});
</script>