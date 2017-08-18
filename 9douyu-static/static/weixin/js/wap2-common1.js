// JavaScript Document

$(function () {
    // 柱状图
    $(".data-graph-bar").each(function () {
        var index = $(".data-graph-bar").index(this);
        var num = 300;
        var time = num * index;
        var height = $(this).css("height");
        $(this).hide();
        $(this).css({height: 0}).show().delay(time).animate({height: height}, num);
        $(this).find('span').delay(time).animate({opacity: '1'});
        $(this).find('.data-point').delay(time).animate({opacity: '1'});
    });


    txy_alert("w-alert", "box_alert", "close_box")
    txy_alert("w-alert3", "box_alert3", "close_box3")


// 弹出框
    $(".w-alert1").click(function () {
        $(".w-alert1").hide();
        $(".box_alert1").show();
        ishidden('.box_alert1');


        $(".close_box1").click(function () {
            $(".box_alert1").hide();
            $(".w-alert1").show();

            ishidden('.box_alert1');

        });

        $(".mask1").click(function () {
            $(".box_alert1").hide();
            $(".w-alert1").show();


            ishidden('.box_alert1');

        });
    });



    //推广页验证手机号码
    $("#family-input-phone-box").delegate(".family-btn",'click',function(){

        var phoneFormat = checkPhone("family-phone-text")
        if( phoneFormat ==false) return false;
        var phoneStatus = checkPhoneUnique("family-phone-text")
        if( phoneStatus == false ){
            alert("手机号码："+phoneFormat+"已经被使用");
            return false;
        }
    });


    // 活动状态弹层
    $('.pop-a-wrap .pop-close-a,.pop-a-wrap>.pop-mask').on('touchend',function(){
        $('.pop-a-wrap').hide();
    })

});
function investProjectByClient( client , projectId , version ,act_token ) {
        if( version ) {
            if( client =='ios') {
                window.location.href = "objc:certificationOrInvestment(" + projectId + ",1)";
                return false;
            }
            if (client =='android'){
                window.jiudouyu.fromNoviceActivity(projectId,1);
                return false;
            }
        }
        if( !version || version == '' ) {
            if( client =='ios') {
                window.location.href = "objc:toProjectDetail(" + projectId + ",1 , "+act_token+")";
                return false;
            }
            if (client =='android'){
                window.jiudouyu.fromNoviceActivity(projectId,1,act_token);
                return false;
            }
        }
        var wap_url = '/project/detail/'+projectId;
        if( act_token ) {
            var _token = $("input[name='_token']").val();
            $.ajax({
                url      :"/activity/setActToken",
                data     :{act_token:act_token,_token:_token},
                dataType :'json',
                type     :'post',
                success : function() {
                    window.location.href='/project/detail/' + projectId;
                }, error : function() {
                    window.location.href='/project/detail/' + projectId;
                }
             });
        }
        window.location.href='/project/detail/' + projectId;
        return false
}
function userLoginByClient( client ) {

        if( client =='ios'){
            window.location.href = "objc:gotoLogin";;
            return false;
        }
        if (client =='android'){
            window.jiudouyu.login()
            return false;
        }
        window.location.href='/login';
}

function txy_alert(id, name, name1) {
    $("#" + id).click(function (event) {
        event.preventDefault();
        $("." + name).fadeIn();
    });
    $("." + name1).click(function () {
        $("." + name).fadeOut();
    });
}

//菜单弹层显示的时候，页面隐藏滚动条，菜单弹层隐藏的时候，页面可以滚动
function ishidden(id){
    if($(id).size() && $(id).is(':visible')){
        $("body").css({"overflow":"hidden"});
    }else {
        $("body").css({"overflow":"auto"});
    }
};
    //推广页
    function checkPhone (phone_obj){
        var phone = $.trim($("#"+phone_obj).val());

        var pattern = PHONE_PATTERN;
        if(phone.length == 0) {
            $.trim($("#"+phone_obj).attr('placeholder','手机号码不能为空'));
            $.trim($("#"+phone_obj).css("border","1px solid red"));
            return false;
        }
        if(!phone.match(pattern)) {
            $.trim($("#"+phone_obj).attr('placeholder','请输入正确的手机号码'));
            $.trim($("#"+phone_obj).css("border","1px solid red"));

            return false;
        }
        return phone;

    }
    //检测手机唯一性
    function checkPhoneUnique (phone_obj) {

        var phone       = $.trim($("#"+phone_obj).val());
        var phoneflag   = true;
        $.ajax({
            url:'/register/checkUnique',
            type:'POST',
            data:{phone:phone,type:'phone'},
            dataType:'json',
            async: false,  //同步发送请求
            success:function(result){
                if(result.status) {
                    phoneflag = true;
                } else {
                    phoneflag = false;
                }
            },
            error:function(msg){
                console.log(msg);
                phoneflag = false;
            }
        });
        return phoneflag;
    }

// tab切换
(function($, window, document,undefined) {
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



