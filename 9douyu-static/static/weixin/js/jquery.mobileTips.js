(function($){
    $.fn.mobileTips = function(content){
        $(this).next(".m-input-tips").text(content).show().delay(2000).fadeOut(); 
    };
    $.fn.mobileTip = function(content){
        $(".m-tips").text(content);
        var w = $(".m-tips").width();
        var ml = -(w/2) + "px";
        $(".m-tips").css({"margin-left":ml,"width":"auto"});
        $(".m-tips").show();
        setTimeout("$('.m-tips').fadeOut()",2000);
    };
    
    $.fn.formatInput = function(pattern) {
        if(typeof pattern == "undefined") return true;

        if($.trim(pattern) == "") return true;
        
        if($(this).val().trim() == "") return true;
        
        if($(this).val().trim().match(pattern)) {
            $(this).data("preValue", $(this).val());
        } else {
            $(this).val($(this).data("preValue"));
        }

        return this; //保持连贯操作性，如：$("#cash").formatInput(/^\d*$/).addClass('error');
    };

    $.formatMoney = function(money, decimal) {
        if(typeof decimal == "undefined") decimal = 2;
        var decStr = '';
        //拼接小数点位数个数字正则
        for(var i = 0; i < decimal; i++) {
            decStr += '(\\d)?';
        }
        var pattern = new RegExp('^(\\d+)(\\.)?' + decStr + '(\\d+)?$');
        money       = money + "";     //强制转换数值为字符类型
        var match   = money.match(pattern);

        if(match) {
            var str = '';
            str += typeof match[1] == "undefined" ? 0 : match[1];   //小数点前面数字
            str += typeof match[2] == "undefined" ? '.' : match[2]; //小数点
            for(var i = 1; i <= decimal; i++) {     //取小数点位数个数值，不存在则用0替代
                var index = 2+i;
                str += typeof match[index] == "undefined" ? 0 : match[index];
            }

            return str;
        } else {
            return 0;
        }
    };
})(jQuery);