<?php
if( !defined('IN_WEIZEPHP') ) {
    exit('Access Denied');
}
?><!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
        <title><?php echo $wlang['fw_msg_prompt'];?></title>
        <link rel="stylesheet" href="<?php echo $wconfig['theme_skin'];?>/css/msg.css"/>
    </head>
    <body>
        <div class="w-msg">
            <div class="w-icon-area"><i class="w-icon-error">&times;</i></div>
            <div class="w-text-area">
                <h2><?php echo $wlang['fw_msg_warn'];?></h2>
                <p><?php echo $message;?></p>
            </div>
            <div class="w-opr-area">
                <p class="weui_btn_area"><?php echo '<a href="'. $url .'" class="w-btn">'. $wlang['fw_btn_ok'] .'</a>';?></p>
            </div>
        </div>
    </body>
</html>