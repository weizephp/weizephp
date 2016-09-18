<div class="modal fade" id="w-b-modal-customize" tabindex="-1" role="dialog" aria-labelledby="w-b-modal-label-customize">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="关闭"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="w-b-modal-label-customize"></h4>
			</div>
			<div class="modal-body" id="w-b-modal-body-customize">
				<p></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
				<button type="button" class="btn btn-primary" id="w-b-modal-customize-ok">确认</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
var _w_b_modal_customize_callback = '';

function w_b_modal_customize(title, message, callback) {
	$('#w-b-modal-label-customize').html(title);
	$('#w-b-modal-body-customize').html(message);
	$('#w-b-modal-customize').modal('show');
	if(typeof(callback) == 'function') {
		_w_b_modal_customize_callback = callback;
    }
}

$('#w-b-modal-customize-ok').click(function() {
	$('#w-b-modal-customize').modal('hide');
	if(typeof(_w_b_modal_customize_callback) == 'function') {
		_w_b_modal_customize_callback();
		_w_b_modal_customize_callback = '';
	}
});
</script>