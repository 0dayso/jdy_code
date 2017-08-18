@extends('wap.common.wapBase')

@section('title', '春风送礼 1月期1％加息')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/currentAdd.css') }}">
@endsection

@section('content')
    <section class="cur-bg">
       <!--  <p class="cur-num">3%</p> -->
    @if($userStatus == false)
        <a href="javascript:;" class="cur-btn " id="user-need-login"></a>
    @else
        <a href="javascript:;" class="cur-btn" id="receive-bonus-control"></a>
    @endif
        {{--<p class="cur-num1">已有 <span>{{array_sum($receiveTotal)}}</span>人参与加息</p>--}}
        <p class="cur-text">1月期限时1%加息</p>
        <p class="cur-time">{{date("Y年m月d日",$activityTime['start'])}}-{{date("Y年m月d日",$activityTime['end'])}}</p>

    @if($userStatus == false)
        <div class="pop-layer">
            <div class="pop-mask"></div>
            <div class="pop">
                <!-- 没登录 -->
                <p class="pop-text">登录之后才可以查看奖励</p>
                <a href="javascript:;" class="cur-btn1" id="userLogin">马上登录</a>
            </div>
        </div>
    @else
        <div class="pop-layer" id="receive-bonus-layer">
            <div class="pop-mask"></div>
            <div class="pop">
                <!-- 登录后 -->
                <p class="pop-text1">零钱计划加息券已经领取</p>
                <p class="pop-text2">请到[零钱计划页面中点击使用]</p>
                <a href="javascript:;" class="cur-btn1" >朕知道啦</a>
            </div>
        </div>
    @endif
    </section>
@endsection

@section('jsScript')
    <script type="text/javascript">
@if($userStatus == false)
        $("#user-need-login").click(function(){
            $(".pop-layer").show();
        });

        $("#userLogin").click(function () {
            var client = getCookie('JDY_CLIENT_COOKIES');
            if( client == '' || !client ){
                 var client  =   '{{$client}}';
             }

            if( client =='ios'){

                window.location.href = "objc:gotoLogin";;
                return false;
            }
            if (client =='android'){
                window.jiudouyu.login()
                return false;
            }
            window.location.href='/login';
        })
@endif
@if($userStatus == true)
        $("#receive-bonus-control").click(function () {

            var client = getCookie('JDY_CLIENT_COOKIES');
            if( client == '' || !client ){
                 var client  =   '{{$client}}';
             }

            if( client =='ios'){
                window.location.href = "objc:goToDiscountCoupon";
                return false;
            }
            if (client =='android'){
                window.jiudouyu.gotoMyAllBonus()
                return false;
            }
            window.location.href='/bonus/index';
//            var receive_status  =   $(this).attr('attr-bonus-status');
//
//            var receive_bonus_id=   $(this).attr('attr-bonus-id')
//
//            if(receive_status =='is_cannot'){
//                var text1   =   "十分抱歉!";
//                var text2   =   '该红包、加息券不可以领取';
//                showReceiveLayer(text1,text2,receive_bonus_id,0)
//                return false;
//            }
//
//            if(receive_bonus_id =='' || receive_bonus_id ==null) {
//                var text1   =   "十分抱歉!";
//                var text2   =   '红包信息错误,请确认后领取!';
//                showReceiveLayer(text1,text2,receive_bonus_id,0);
//                return false;
//            }
//            receiveBonusControl(receive_bonus_id);
//            return false;

        })

        var receiveBonusControl = function (id) {
            var userStatus = '{{$userStatus}}';
            if(userStatus == false) {
                $(".pop-layer").show();
                return false
            }
            var lock    =   $("#receive-bonus-"+id).attr("lock-status");
            if( lock == 'closed'){
                return false;
            }
            var token   =   "{{$token}}";
            var client  =   "{{$client}}";

            $("#receive-bonus-"+id).attr("lock-status",'closed');

            $.ajax({
                url      :"/activity/receiveBonus",
                dataType :'json',
                data: { from:'app',token: token,client:client,bonus_id:id,_token:'{{csrf_token()}}' },
                type     :'post',
                success : function(json){
                    var html    =   '';
                    var title   =   '';
                    if( json.status==true || json.code==200){
                        html    =   "请到[零钱计划页面中点击使用]"
                        title   =   "零钱计划加息券领取成功";
                        number  =   1;
                    } else if( json.status == false || json.code ==500 ){
                        html    =   json.msg
                        title   =   "很抱歉";
                        number  =   0;
                    }
                    showReceiveLayer(title,html,id,number);
                    return false;
                },
                error : function(msg) {
                    alert('领取失败，请稍候再试');
                    $("#receive-bonus-"+id).attr("lock-status",'opened');
                }
            })
        }

        function showReceiveLayer(text1,text2,id,number) {
            $('.pop-text1').html(text1);
            $('.pop-text2').html(text2);
            $("#receive-bonus-layer").show();
            var total   =   parseInt($('.cur-num1 span').html())+number;
            $('.cur-num1 span').html(total);
            $("#receive-bonus-"+id).attr("lock-status",'opened');
            return false;
        }

@endif
        $(".cur-btn1,.pop-mask").click(function(){
            $(".pop-layer").hide();
        });
    </script>
@endsection



