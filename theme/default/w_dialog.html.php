<?php
if(!defined('IN_WEIZEPHP')) {
    exit('Access Denied');
}
include $wconfig['theme_path'] . '/w_dialog_success.html.php';
include $wconfig['theme_path'] . '/w_dialog_error.html.php';
include $wconfig['theme_path'] . '/w_dialog_confirm.html.php';
include $wconfig['theme_path'] . '/w_dialog_custom.html.php';