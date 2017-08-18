jQuery.fn.extend({
    /*
     * 滚动到指定位置
     */
    scrollTo: function(speed) {
        var $ = jQuery;
        speed = speed || 100;
        $("html,body").animate({scrollTop:$(this).offset().top},speed);
        return this;
    }
});