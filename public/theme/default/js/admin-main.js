/* 只允许同域下存在iframe
----------------------------------*/
if(top.location.host != window.location.host) {
    top.location.href = window.location.href;
}
