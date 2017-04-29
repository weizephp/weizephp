<?php
if( !defined('IN_WEIZEPHP') ) {
	exit('Access Denied');
}
?>
        </div>
            </div>
        </div>
        
        <script>
        $(document).ready(function() {
            // 弹出菜单
            $("#w-navbar-toggle").on("click", function() {
                if( $("#w-navbar").css("display") == "none" ) {
                    $("#w-navbar").css("border-bottom", "1px solid #cbe6bd").slideDown("slow");
                } else {
                    $("#w-navbar").css("border-bottom", "0px solid #cbe6bd").slideUp("slow");
                }
            });
        });
        </script>
