(function($){
    $.fn.extend({
        /**
         * jQuery hover 的改进版
         * 1. $(hoverObj).hoverShow(showSelector);
         * 2. $(hoverObj).hoverShow(function(){});
         * 3. $(hoverObj).hoverShow(function(){}, function(){});
         */
        hoverShow: function() {
            var args = arguments;
            switch($(args).size()) {
                case 1:
                    if($.isFunction(typeof args[0])) {
                        $(this).hover(args[0]);
                    } else {
                        $(this).hover(function(){
                            $(args[0]).show();
                        }, function(){
                            $(args[0]).hide();
                        });
                    }
                    break;
                case 2:
                    $(this).hover(args[0], args[1]);
                    break;
                case 0:
                default:
                    break;
            }
        },
        
        /*
         *充值须知、提现须知等新弹层
         */
        popDiv:function(width){
            var w = width;
            $(this).parent(".pop-wrap").show();
            var h = $(this).height();
            var mt = -(h/2) + "px";
            var ml = -(w/2) + "px";
            $(this).css({"width":w + "px","margin-top":mt,"margin-left":ml});
            $(this).siblings(".pop-mask").click(function(){
                $(this).parent(".pop-wrap").hide();
            });
            $(this).find(".pop-close").click(function(){
                $(this).parent().parent(".pop-wrap").hide();
            });
        },
        
        formatInput:function(pattern) {
            if(typeof pattern == "undefined") return true;

            if($.trim(pattern) == "") return true;
            
            if($.trim($(this).val()) == "") return true;
            
            if($.trim($(this).val()).match(pattern)) {
                $(this).data("preValue", $(this).val());
            } else {
                $(this).val($(this).data("preValue"));
            }

            return this; //保持连贯操作性，如：$("#cash").formatInput(/^\d*$/).addClass('error');
        },
        
        //register login页表单判断
        btnShowTips: function(msg, type) {
            if(typeof type == "undefined") {
                type = "error";
            }
            if(type == "success") {
                $(this).data("error",null).parent().siblings(".tips-msg").hide();
            } else {
                if(msg == '') {
                    msg = $(this).parents().find(".tips-msg").html();
                }
                $(this).data("error",true).parent().siblings(".tips-msg").addClass("tips-error").removeClass("tips-success").html(msg).show();

            }
        },
        
        //table结构的表单判断
        tableShowTips: function(msg, type) {
            if(typeof type == "undefined") {
                type = "error";
            }
            if(type == "success") {
                $(this).data("error",null).removeClass("wrong").parents("tr").find(".tips-msg").addClass("tips-success").removeClass("tips-error").show();
            } else {
                if(msg == '') {
                    msg = $(this).parents("tr").find(".tips-msg").html();
                }
                $(this).data("error",true).addClass("wrong").parents("tr").find(".tips-msg").addClass("tips-error").removeClass("tips-success").html(msg).show();

            }
        },
        
        /*
         * 滚动到指定位置
         */
        scrollTo: function(speed) {
            speed = speed || 100;
            $("html,body").animate({scrollTop:$(this).offset().top},speed);
            return this;
        },
        
        /*
         * js 自动跳转
         * @example
         * HTML: <div class="redirect" link="/">3</div>秒后自动跳转
         * JS:   $(".redirect").doRedirect(3);
         */
        doRedirect: function(timeout){
            if(!$(this).hasClass("redirect")) return false;

            var time = $(this).text();
            if(time != '' && !isNaN(time)) {
                timeout = time;
            }
            if(typeof timeout == "undefined") timeout = 3;

            $This   = $(this);
            $.redirectTimer = setInterval(function() {
                timeout--;
                if(timeout == 0) {
                    clearInterval($.redirectTimer);
                    location.href = $This.attr("link");
                } else {
                    $This.text(timeout);
                }
            }, 1000);
        },

        /*
        *  交易密码提示弹层
         */
        popTip:function(width){
            var w = width;
            $(this).parent(".pop-wrap").show();
            var h = $(this).height();
            var mt = -(h/2) + "px";
            var ml = -(w/2) + "px";
            $(this).css({"width":w + "px","margin-top":mt,"margin-left":ml});
            $(this).find(".pop-close").click(function(){
                $(this).parent().parent(".pop-wrap").hide();
            });
        },
        
        //最后一个空方法，最后不需要逗号
        __noop: function(){}
    });
    
    $.extend({
        //截断数值，不进位舍弃，如2.009 => 2.00 , 2.094 => 2.09
        formatMoney: function(money, decimal) {
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
                //第3个开始是小数位
                for(var i = 1; i <= decimal; i++) {     //取小数点位数个数值，不存在则用0替代
                    var index = 2 + i;
                    str += typeof match[index] == "undefined" ? 0 : match[index];
                }

                return str;
            } else {
                return 0;
            }
        },
        
        //四舍五入，引用js的toFixed函数
        toFixed: function(num, length) {
            num = parseFloat(num);
            if(isNaN(num)) num = 0;
            length = length || 2;
            var numStr = String(num);
            if(numStr.indexOf('.') != -1) {
                var parts = numStr.split('.');
                //强制后缀加1，解决2.555.toFixed(2) = 2.55的问题 2.555 => 2.5551
                if(parts[1].length >= length) {
                    num = parseFloat(numStr + "1");
                }
            }
            return parseFloat(num.toFixed(length));
        },
        
        /**
         * 弹窗插件代理处理函数
         * @example
         * $.norymodalProxy(".container img");
         */
        norymodalProxy: function(imgSelector) {
            $(imgSelector).each(function(){
                var bigSrc = $(this).attr('big-src');
                if(typeof bigSrc != 'undefined') {
                    $(this).wrap('<a href="'+bigSrc+'" class="popupImg"></a>');    //a标签的href为图片src
                }
            });
            //响应弹窗
            $(".popupImg").nm();  //nm()是nyroModal的弹窗调用方法
            $("body").on("keydown", function(event){
                if($(".nyroModalBg").size()){
                    event = event || window.event;
                    var keyCode = event.keyCode || event.which;
                    var currIndex = $(".popupImg").index($(".popupImg[href='"+$(".nyroModalImage>img").attr("src")+"']"));
                    if(keyCode == 37){  //Left
                        if(currIndex != 0){
                            $(".popupImg").eq(currIndex-1).click();
                        }
                    }else if(keyCode == 39){    //Right
                        if(currIndex != ($(".popupImg").size()-1)){
                            $(".popupImg").eq(currIndex+1).click();
                        }
                    }


                }
            });


            $("body").delegate($(".nyroModalImage img"),"mouseover",function(){
                if($(".popupImg").size() > 0){
                    var next = '<a href="javascript:;" class="nyroModalNext">下一个</a>';
                    var prev = '<a href="javascript:;" class="nyroModalPrev">上一个</a>';
                    if($(".nyroModalNext").size()==0 || $(".nyroModalPrev").size()==0){
                        $(".nyroModalImage").prepend(next);
                        $(".nyroModalImage").prepend(prev);
                    }
                    var currIndex = $(".popupImg").index($(".popupImg[href='"+$(".nyroModalImage>img").attr("src")+"']"));
                    if(currIndex == 0){
                        $(".nyroModalPrev").hide()
                    };
                    if(currIndex == ($(".popupImg").size()-1)){
                        $(".nyroModalNext").hide()
                    };
                    $(".nyroModalPrev").click(function(){
                        if(currIndex != 0){
                            $(".popupImg").eq(currIndex-1).click();
                        }
                    });
                    $(".nyroModalNext").click(function(){
                        if(currIndex != ($(".popupImg").size()-1)){
                            $(".popupImg").eq(currIndex+1).click();
                        }
                    });
                }
            })
        },
        //函数$.norymodalProxy别名
        nmProxy: function(imgSelector) {
            $.norymodalProxy(imgSelector);
        },
        
        //字体闪烁
        'shake': function (ele, className, times) {
            var i = 0,
            t = false,
            o = ele.attr('class') + ' ',
            c = '',
            times = times || 2;
            if (t) return;
            t = setInterval(function () {
              i++;
              c = i % 2 ? o + className : o;
              ele.attr('class', c);
              if (i == 2 * times) {
                clearInterval(t);
                ele.removeClass(className);
              }
            }, 200);
        },
        
        //最后一个空方法，最后不需要逗号
        __noop: function(){}
    });
})(jQuery);