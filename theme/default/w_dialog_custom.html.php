<?php
if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}
?><div id="w-dialog-custom" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="关闭"><span aria-hidden="true">&times;</span></button>
                <h4 id="w-dialog-custom-title" class="modal-title"></h4>
            </div>
            <div id="w-dialog-custom-body" class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" id="w-dialog-custom-ok" class="btn btn-primary">确认</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<script>
function w_dialog_custom(title, message, ok) {
    $("#w-dialog-custom-title").html(title);
    $("#w-dialog-custom-body").html(message);
    if( ok ) {
        $("#w-dialog-custom-ok").off("click").on("click", function() {
            $("#w-dialog-custom").modal("hide");
            ok();
        });
    }
    $("#w-dialog-custom").modal({backdrop: "static"}).off("hidden.bs.modal").on("hidden.bs.modal", function(e) {
        $('body').css("padding-right", "0px");
    });
}
</script>