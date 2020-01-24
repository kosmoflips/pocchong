$(window).bind('load', function() {
$('img').each(function() {
    if((typeof this.naturalWidth != "undefined" &&
        this.naturalWidth == 0 ) 
        || this.readyState == 'uninitialized' ) {
        $(this).attr('src', '/deco/img/img_placeholder_h.png');
    }
}); })
//requires jQuery. too slow on load, not intended to actually use