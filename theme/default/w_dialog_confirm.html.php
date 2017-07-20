<?php
if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}
?><div id="w-dialog-confirm" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="关闭"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-danger">确认提示</h4>
            </div>
            <div id="w-dialog-confirm-body" class="modal-body">
                <p></p>
            </div>
            <div class="modal-footer">
                <button type="button" id="w-dialog-confirm-ok" class="btn btn-danger">确认</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<script>
function w_dialog_confirm(message, ok) {
    $("#w-dialog-confirm-body p").html(message);
    if( ok ) {
        $("#w-dialog-confirm-ok").off("click").on("click", function() {
            $("#w-dialog-confirm").modal("hide");
            ok();
        });
    }
    $("#w-dialog-confirm").modal({backdrop: "static"}).off("hidden.bs.modal").on("hidden.bs.modal", function(e) {
        $('body').css("padding-right", "0px");
    });
}
</script>