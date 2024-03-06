$(document).ready(function () {
    var ua = window.navigator.userAgent;
    var isIe = ua.indexOf('MSIE ') > 0 || ua.indexOf('Trident/') > 0 || ua.indexOf('Edge/') > 0;
    if (isIe) {
        $.ajaxSetup({
            headers: {'X-Ie-Redirect-Compatibility': 'true'}
        });
    }
});
