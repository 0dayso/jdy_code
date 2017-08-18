 (function($){
    
     $(function(){
        // 弹窗调用
        $(document).on('click', 'a[data-target]',function(event){
            event.stopPropagation();
            var target = $(this).attr("data-target");
                $("div[data-modul="+target+"]").mask({"layerWidth":"680"});
        });

    	var $div_li =$(".js-footer-tab li");
    	$div_li.hover(function(){
    		$(this).addClass("selected")            //当前<li>元素高亮
    			   .siblings().removeClass("selected");  //去掉其它同辈<li>元素的高亮
    		//alert($div_li.children().html())
    		var index =  $div_li.index(this);  // 获取当前点击的<li>元素 在 全部li元素中的索引。
            if(index==1){
                $(".footer-blue").animate({left:"113px"},300);
            }else{
                $(".footer-blue").animate({left:"0px"},300);
            }
    		$(".js-footer-tabbox > div")       //选取子节点。不选取子节点的话，会引起错误。如果里面还有div
    				.eq(index).show()   //显示 <li>元素对应的<div>元素
    				.siblings().hide(); //隐藏其它几个同辈的<div>元素
    	});
    			
    	$('.t-top').click(function(){$('html,body').animate({scrollTop: '0px'},800);return false;});	
    	
    	//login
    	$(".js_login-input").focus(function(){
    		$(this).next(".icon-login").addClass("on");
    	}).blur(function(){
    		$(this).next(".icon-login").removeClass("on");
    	});
    	
    	//菜单根据url高亮
        $(".t-header-7 li a").each(function(){
            var href = location.href.replace(/^.+\.com[^\/]*?\//i, '/');
            var end  = href.indexOf('?');
            if(end != -1) {
                var href = href.substr(0, end);
            }
            var pattern = new RegExp('^'+$(this).attr("href").replace(/\./, '\\\.') + '(?:\\\.html)?$');     //把路径中的.替换成正则的\.
            if(href.match(pattern)) {
                $(this).addClass("on");
            }
        });
    	
    	//分支地图
    	hover(".branch-logo","#branch-promp0");
    	hover(".branch-logo1","#branch-promp1");
    	hover(".branch-logo2","#branch-promp2");
    	hover(".branch-logo3","#branch-promp3");
    	hover(".branch-logo4","#branch-promp4");
    	hover(".branch-logo5","#branch-promp5");
    	hover(".branch-logo6","#branch-promp6");
      	hover(".branch-logo7","#branch-promp7");
      	hover(".branch-logo8","#branch-promp8");
    	function hover(a,b){
    		$(a).hover(function(){/*$(b).fadeIn()},function(){$(b).fadeOut()*/
    			$(".branch-promp").hide();
    	   		$(b).show();
    		});	
    	};

    	tab1("#branch-button li","#notice_box > div");
      	tab1("#branch-button1 li","#notice_box1 > div");
    	tab1(".branch-map a",".box1");
    	function tab1(a,b){
    		 var $div_li =$(a);
            	$div_li.click(function(event){
    			event.preventDefault(); 
                $(this).addClass("active")            
                       .siblings().removeClass("active"); 
                var index =  $div_li.index(this);  
    			$(b) /*.eq(index).show()  
                        .siblings().hide();  */    
                        .eq(index).fadeIn(1000)  
                        .siblings().fadeOut(0); 
            });
       };
       scrollTop("#branch-logo","#0","#branch-promp0");
       scrollTop("#branch-logo1","#1","#branch-promp1");
       scrollTop("#branch-logo2","#2","#branch-promp2");
       scrollTop("#branch-logo3","#3","#branch-promp3");
       scrollTop("#branch-logo4","#4","#branch-promp4");
       scrollTop("#branch-logo5","#5","#branch-promp5");
       scrollTop("#branch-logo6","#6","#branch-promp6");
       scrollTop("#branch-logo7","#7","#branch-promp7");
       scrollTop("#branch-logo8","#8","#branch-promp8");
       function scrollTop(a,b,c){
    	   $(a).click(function(){
    	   $("html,body").animate({scrollTop: $(b).offset().top},1000);
    	});
      };


      // 活动状态弹层关闭
      $('.pop-a-wrap .pop-close-a,.pop-a-wrap>.pop-mask').click(function() {
            $('.pop-a-wrap').fadeOut();
        });

    
    });
 })(jQuery);



 //清除浏览器自动填充问题
 $('.js-autocomplete-off').on('change keyup', function(){
     if($(this).data('autocomplete')) {
         return true;
     };
     $(this).data('autocomplete', true);
     //首次输入值，长度大于1则认为自动填充
     if($(this).val().length > 1) {
         $(this).val('');
     };
 });

function clickCounter(area){
        $.ajax({
            url:'/api/click/clickCount',
            type:'POST',
            data:{area:area},
            dataType:'json',
            async:true,
            success:function(result) {
            }
        });
    };
// tab切换
;(function($, window, document,undefined) {
    $.fn.tabs = function (options) {
        var settings = {
            tabList: ".Js_tab li",//tab list
            tabContent: ".js_tab_content .Js_tab_main",//内容box
            tabOn:"cur",//当前tab类名
            action: "mouseover"//事件，mouseover或者click
        };
        var _this = $(this);
        if (options) $.extend(settings, options);
        _this.find(settings.tabContent).eq(0).show(); //第一栏目显示
        _this.find(settings.tabList).eq(0).addClass(settings.tabOn);
        if (settings.action == "mouseover") {
            _this.find(settings.tabList).each(function (i) {
                $(this).mouseover(function () {
                    $(this).addClass(settings.tabOn).siblings().removeClass(settings.tabOn);
                    var _tCon = _this.find(settings.tabContent).eq(i);
                    _tCon.show().siblings().hide();
                }); //滑过切换
            });
        }
        else if (settings.action == "click") {
            _this.find(settings.tabList).each(function (i) {
                $(this).click(function () {
                    $(this).addClass(settings.tabOn).siblings().removeClass(settings.tabOn);
                    var _tCon = _this.find(settings.tabContent).eq(i);
                    _tCon.show().siblings().hide();
                }); //点击切换
            });
        };
    };
})(jQuery, window, document);

//调用方式：
    $(".Js_tab_box").tabs();// 事件，默认mouseover
    $(".Js_tab_box1").tabs({action: "click" });// 事件，action mouseover或者click

// 点击按钮弹窗
;(function($, window, document,undefined) {
    //定义Layer的构造函数
    var Layer = function(ele, opt) {
        this.$element = ele,
            this.defaults = {
                layerWrap:"layer_wrap",
                layerMask:"layer_mask",
                layer:"layer",
                layerWidth:"layerWidth",
                layerTitle:"layer_title",
                layerClose:"layer_close",
                layerCon:"layer_con"
            },
            this.options = $.extend({}, this.defaults, opt);

    }
    //定义Layer的方法
    Layer.prototype = {

        show:function(){
            $(this.$element).show();
        },

        addClass:function(){
            var element = $(this.$element);
            var jsLayer = element.find('.Js_layer');
            element.show();
            jsLayer.css({"margin-left":-(this.options.layerWidth/2)+"px","margin-top":(jsLayer.height())== 0 ? -240+"px" : -(jsLayer.height()/2)-14+"px","width":this.options.layerWidth+"px"});
            element.find(".Js_layer_mask").addClass(this.options.layerMask);
            element.find(".Js_layer_close").addClass(this.options.layerClose);
            element.find(".layer_title").addClass(this.options.layerTitle);
            element.find(".layer_con").addClass(this.options.layerCon);
        }
    }


    //在插件中使用Layer对象
    $.fn.mask = function(options) {
        return this.each(function () {
            var layer = new Layer(this, options);
            //调用其方法
            layer.addClass();
            return layer.show();
        })


    }
    $(document).on('click', '[data-toggle="mask"]', function (event) {
        event.stopPropagation();
        var target = $(this).attr("data-target");
        $("."+target).hide();

    })
     //首页导航菜单点击高亮显示

     $(".header-nav-item li a").each(function(){
         var href = location.href.replace(/^.+\.com[^\/]*?\//i, '/');
         var end  = href.indexOf('?');
         if(end != -1) {
             var href = href.substr(0, end);
         }
         var pattern = new RegExp('^'+$(this).attr("href").replace(/\./, '\\\.') + '(?:\\\.html)?$');     //把路径中的.替换成正则的\.
         if(href.match(pattern)) {
             $(this).addClass("on");
         }
     });

     Date.prototype.pattern=function(fmt) {
         var o = {
             "M+" : this.getMonth()+1, //月份
             "d+" : this.getDate(), //日
             "h+" : this.getHours()%12 == 0 ? 12 : this.getHours()%12, //小时
             "H+" : this.getHours(), //小时
             "m+" : this.getMinutes(), //分
             "s+" : this.getSeconds(), //秒
             "q+" : Math.floor((this.getMonth()+3)/3), //季度
             "S" : this.getMilliseconds() //毫秒
         };
         var week = {
             "0" : "/u65e5",
             "1" : "/u4e00",
             "2" : "/u4e8c",
             "3" : "/u4e09",
             "4" : "/u56db",
             "5" : "/u4e94",
             "6" : "/u516d"
         };
         if(/(y+)/.test(fmt)){
             fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
         }
         if(/(E+)/.test(fmt)){
             fmt=fmt.replace(RegExp.$1, ((RegExp.$1.length>1) ? (RegExp.$1.length>2 ? "/u661f/u671f" : "/u5468") : "")+week[this.getDay()+""]);
         }
         for(var k in o){
             if(new RegExp("("+ k +")").test(fmt)){
                 fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
             }
         }
         return fmt;
     }
})(jQuery, window, document);

 

