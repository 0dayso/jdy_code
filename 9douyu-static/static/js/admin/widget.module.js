(function($){
$(document).ready(function(){
    /* Widget close */
    $('.wclose').click(function(e){
        e.preventDefault();
        var $wbox = $(this).parent().parent().parent();
        $wbox.hide(100);
    });

    /* Widget minimize */

    $('.wminimize').click(function(e){
        e.preventDefault();
        var $wcontent = $(this).parent().parent().next('.widget-content');
        if($wcontent.is(':visible'))  {
            $(this).children('i').removeClass('icon-chevron-up');
            $(this).children('i').addClass('icon-chevron-down');
        } else  {
            $(this).children('i').removeClass('icon-chevron-down');
            $(this).children('i').addClass('icon-chevron-up');
        }            
        $wcontent.toggle(500);
    });
});
})(jQuery);