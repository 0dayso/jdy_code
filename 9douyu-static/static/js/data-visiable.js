
 $.fn.visiable = function(){
    // 当页面滚动到元素位置上，该元素初始化
    var me = $(this),
        win = $(window),
        width = me.width(),
        height = me.height();

    if(win.scrollLeft()>me.offset().left+width) {//left
        return false;
    } else if(win.scrollLeft() + win.width() < me.offset().left) {//right
        return false;
    } else if(win.scrollTop() > me.offset().top + height) {//top
        return false;
    } else if(win.scrollTop() + win.height() < me.offset().top) {//bottom
        return false;
    } else {
        return true;
    }
};

        
        ele1 = $("#bar"),
        ele3 = $("#datamove");

        var visiable = function(){
            var value1 = ele1.attr("data-visiable"),
                value3 = ele3.attr("data-visiable");
                

            
            if(ele1.visiable() && value1 != "true"){//柱状图
                $(".data-graph-bar").each(function(){
                    var index = $(".data-graph-bar").index(this);
                    var num = 300;
                    var time = num*index;
                    var height = $(this).css("height");
                    $(this).hide();
                    $(this).css({height:0}).show().delay(time).animate({height: height},num);
                });

                ele1.attr("data-visiable","true");

            }
           
            
          
          if(ele3.visiable() && value3 != "true"){//饼状图
                $.numberMove();
                ele3.attr("data-visiable","true");
            }
          
           
        };
        visiable();
        $(window).scroll(function(){
            visiable();
        });


       