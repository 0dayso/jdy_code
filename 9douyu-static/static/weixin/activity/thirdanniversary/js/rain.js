var time3   =   "";
    var number  =   8;
    var startTouch= 0;
    // 点击捞金
    $(document).delegate("#user_can_lottery",'click',function (e) {
        e.preventDefault();
        // 红包雨倒计时
        $(".kill-pop-wrap1").show();
        var time2   =   3;
        var time_2  =   setInterval( function () {
            time2--;
            var src = $(".kill-time img").data("src").split("?")[0];
            $(".kill-time img").attr("src",src+time2+".png");
            if( time2 <= 0 ) {
                $(".kill-pop-wrap1").hide();
                $(".page_rain").show();
                $(".kill-time img").attr("src",src+3+".png");
                time3=setInterval(count,1000);
                clearInterval(time_2);
            }
        },1000);
    });

    function count() {
        number--;
        $(".db-time").html(number);

        $(document).on('touchstart', '.bonus_id', function(){
            $(this).css("background-position","0 -100px");
            startTouch++;
        });

        if( number<=1 ) {
            clearInterval(Timerr,20);
        }
        if( number<=0 ) {
            $(".div").removeClass("bg_1");
        }

        if( number<0 ) {
            clearInterval(time3);
            $(".db-time").html("");
            bonusRain.isDoLotteryThing(startTouch);
        }
    }
    var Timerr = setInterval(function () {
        for(var i=0;i<3;i++){
            var j=parseInt(Math.random()*600+000); //红包起始右侧位置
            var j1=parseInt(Math.random()*100+300); //
            var n=parseInt(Math.random()*20+(-100)); //红包起始顶部位置
            $('.div').prepend('<div class="bonus_id"></div>');
            $('.div').children('div').eq(0).css({'right':j,'top':n});
            $('.div').children('div').eq(0).animate({'right':j-j1,'top':$(window).height()+200},3000);
        }
    },450); //数量速度

    var removepackage = setInterval(function(){
        for(var jj=0;jj<$('.div>div').size()/4;jj++){
            $('.div>div').eq($('.div>div').size()-jj).remove();
        }
    },1200)

    var bonusRain =   {
        isDoLotteryThing:function (touchNumber) {
          if( touchNumber <= 0 || !touchNumber ){
              bonusRain.showError("一阵红雨飘过<br>客官却无动于衷!大气!");
return false;
              $("#not-lottery-thing").show();
              $("#not-lottery-thing p").html("一阵红雨飘过<br>客官却无动于衷!大气!")
              return false
          }else{
                bonusRain.doLottery()
          }
        },
        doLottery:function () {
            var $lockObj =   $("#user_can_lottery");

            if( $lockObj.attr("lottery-status") == 'closed'){
                return false;
            }
            $lockObj.attr("lottery-status",'opened');
            var _token  = $(".page-bg").attr("attr-cs_token");
            $.ajax({
                url      :"/thirdAnniversary/doLottery",
                dataType :'json',
                data     : { from:'app',_token:_token},
                type     :'post',
                success : function(json){
                    if( json.status==true){
                        bonusRain.showSuccess(json);
                    }
                    if( json.status == false || json.code ==500 ){
                        bonusRain.showError(json.msg);
                    }
                },
                error : function() {
                    bonusRain.showError('红包雨领取失败,请稍后再试');
                }
            })
        },
        showSuccess:function (lottery) {
            var $lockObj =   $("#user_can_lottery");
            if(lottery.data.type == 1 ){
                var bonus_value =   '<dt>￥<span>' + lottery.data.money + '</span><em class="db-line3"></em></dt>';
                var bonus_desc  =   '<dd><p>' + lottery.data.using_desc + '</p></dd>';
            }
            if(lottery.data.type == 2 ||lottery.data.type == 4){
                var bonus_value =   '<dt><span>' + lottery.data.rate + '</span>%<em class="db-line3"></em></dt>';
                var bonus_desc  =   '<dd><p>' + lottery.data.using_desc + '</p></dd>';
            }
            if(lottery.data.type == 3 ){
              var cndLocal=$('.page_rai').attr('attr-static-local');
              if( !cndLocal ) {
                cndLocal  = 'https://img1.9douyu.com';
              }
              var bonus_value = '<img src="' + cndLocal + '/static/weixin/activity/thirdanniversary/images/three/prize-'+ lottery.data.order_num +'.png">';
             var bonus_desc  = '<dd><p>' + lottery.data.name + '</p></dd>';
            }
            $("#lottery-thing-bonus dl").html(bonus_value + bonus_desc)
            $("#lottery-thing-bonus").show();
            $lockObj.attr("lock-status",'opened');
            return false;
        },
        showError:function (msg) {
            var error_msg   =   '<h4 class="mt">' + msg + '</h4>';
            var error_btn   =   '<a href="javascript:;" class="db-btn-1">知道了</a>'
            $(".kill-pop-wrap-1 .kill-pop1").html(error_msg + error_btn)
            $(".page_rain").hide();
            $(".kill-pop-wrap-1").show();
            $("#user_can_lottery").attr("lock-status",'opened');
            return false;
        }
    };
$(".kill-pop i,.mask3,.cannot-lottery").click(function(){
       $(".kill-pop-wrap").hide();
   });
    $(document).delegate(".db-btn-1,.kill-pop i,.mask3,.cannot-lottery",'click',function () {
        $(".kill-pop-wrap,.kill-pop-wrap-1").hide();
       window.location.reload();
    });
    //确定按钮,初始化红包雨的活动
    $(".db-btn-2").click(function(){
        $(".page_rain").hide();
        var src = $(".kill-time img").data("src").split("?")[0];
        $(".kill-time img").attr("src",src+"3.png");

    });
    /**
     * 无法抢红包雨的状态
     */
    $(document).delegate("#user_lottery_none",'click',function () {

        var error_type  =   $(this).attr('attr-error-type');
        var error_msg   =   $(this).attr('attr-error-msg');

        if( error_msg == '' || !error_msg ){
            return false;
        }
        var error_msg_alert =    '<h4 class="mt">' + error_msg + '</h4>'
        var error_msg_btn   =    '<a href="javascript:;" class="db-btn-1" >知道了</a>'
        if( error_type == 'notLogged' ) {
            error_msg_alert =    '<h4 class="mt">' + error_msg + '</h4>'
            error_msg_btn   =    '<a href="javascript:;" class="db-btn-1" id="userLogin">登录</a>'
        }
        $(".kill-pop-wrap-1").find('.kill-pop1').html(error_msg_alert+error_msg_btn).show();
        $(".kill-pop-wrap-1").show();
    })
    $(document).delegate("#userLogin",'click',function () {

        userLoginByClient( client )
    })
