$(document).bind("mobileinit", function(){
    $.mobile.ajaxEnabled = false;
    $.mobile.pushStateEnabled = false;
    $.mobile.hashListeningEnabled = false;
    $.mobile.page.prototype.options.keepNative = "select, input, textarea.bar";
});
