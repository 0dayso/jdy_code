(function($){
    $.extend({
        scrollFix: function(fixElement, scrollElement) {
            $(window).scroll(function(){
                if(!$(scrollElement).size() || !$(fixElement).size()) return false;
                var scrollerTop	            = $(this).scrollTop();    //滚动条当前高度
                var scrollerBottom          = scrollerTop + $(fixElement).height();
                var scrollElementBottom     = $(scrollElement).offset().top + $(scrollElement).height();
                if(!$(fixElement).data('top')) {            //存储固定元素初始顶部高度
                    $(fixElement).data('top', $(fixElement).offset().top);
                }
                
                if($(fixElement).data('top') <= scrollerTop) {    //滚动条超过固定元素顶部，则固定它
                    if(scrollerBottom <= scrollElementBottom) {   //滚动元素没到底部之前
                        $(fixElement).css({position: "fixed", left: $(fixElement).offset().left, top: "0px"});
                    } else {    //滚动元素到达底部之后，通过top负值逐步上升固定元素
                        $(fixElement).css({position: "fixed", left: $(fixElement).offset().left, top: -(scrollerBottom - scrollElementBottom) + "px"});
                    }
                } else {    //滚动条低于固定元素顶部，恢复非固定状态
                    if($(fixElement).css("position") != "static") {
                        $(fixElement).css({position: "static", top: $(fixElement).data('top') + 'px'});
                    }
                }
            });
        }
    });
})(jQuery);
