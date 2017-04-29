<?php
if( !defined('IN_WEIZEPHP') ) {
	exit('Access Denied');
}
function admin_head($title) {
    global $wconfig;
    $head = <<<EOT
<meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
        <title>{$title}</title>
        <link rel="stylesheet" href="{$wconfig['public_path']}/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="{$wconfig['theme_skin']}/css/global.css"/>
        <link rel="stylesheet" href="{$wconfig['theme_skin']}/css/admin.css"/>
        <script>
        // 当前页不允许在框架(iframe)下显示
        if(window.top != window.self) {
            window.top.location = window.self.location;
        }
        </script>
        <script src="{$wconfig['public_path']}/js/jquery-1.12.4.min.js"></script>
        <script src="{$wconfig['public_path']}/js/bootstrap.min.js"></script>

EOT;
    return $head;
}