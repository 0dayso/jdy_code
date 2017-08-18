 $(function(){

	function setFixed(){
        var $window = $(window);
        var $body = $('body');
        var $header = $('.m-navlist');
        var height1 = $('.t-header').height();
        var height2 = $('.t-header-1').height();
        var MARGIN = 10;

        $window.on('scroll', function(e) {
            var scrollTop = $(this).scrollTop();

            if (scrollTop > height1 + height2 + MARGIN) {
                $header.addClass('m-navlist-fixed')
            } else {
                $header.removeClass('m-navlist-fixed')
            };
        }).trigger('scroll');
    }

    setFixed();

});