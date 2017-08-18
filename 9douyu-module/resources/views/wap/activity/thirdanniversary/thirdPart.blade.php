@extends('wap.common.activity')

@section('title', '鱼你前行 耀我新生')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/activity/thirdanniversary/css/three/index.css') }}">
<link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/activity/redpacketrain/css/rain.css') }}">
<script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular.min.js')}}"></script>
<script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular-route.js')}}"></script>
<script type="text/javascript" src="{{ assetUrlByCdn('/static/angular/angular-animate.min.js')}}"></script>
<script type="text/javascript" src="{{ assetUrlByCdn('/static/weixin/activity/thirdanniversary/js/anniversary.three.js')}}"></script>

@endsection

@section('content')
<div class="page-bg" attr-cs_token="{{csrf_token()}}">
    <input type="hidden" name="_token"  value="{{csrf_token()}}">
    @include('wap.activity.thirdanniversary.template.summation')
    <div class="page-center page-time">
        <p>{{date("Y年m月d日",$activityTime['start'])}}-{{date("m月d日",$activityTime['end'])}}</p>
    </div>
    <div class="page-red-envelope" ng-controller="RainCtrl" >
      <a href="javascript:;" class="btn" id="user_can_lottery" lottery-status="opened" ng-if="rain.status== true"></a>
      <a href="javascript:;" class="btn disable" id="user_lottery_none" ng-if="rain.status== false" attr-error-msg="<%rain.error%>" attr-error-type="<%rain.type%>"></a>
      <!-- <a href="javascript:;" class="disable" id="user_can_lottery"></a> -->
    </div>
    <!--加载项目-->
    @include('wap.activity.thirdanniversary.template.thirdproject')
    <!-- 嘉年华惊喜 -->
    @include('wap.activity.thirdanniversary.template.Jnh')
    <!-- random -->
    <section class="page-wrap">
      <div class="page-corner-mark"><span>奖品</span></div>
      <div class="page-surprise">
        <h6 class="page-center">天天嗨购  惊喜不断</h6>
        <p class="page-center">每日在优选项目中，随机抽选3名投资者<br>获得九斗鱼三周年伴手礼一份</p>
        <img src="{{ assetUrlByCdn('/static/weixin/activity/thirdanniversary/images/three/page-surprise.png') }}" class="page-surprise-gift" alt="伴手礼">
      </div>
        @include('wap.activity.thirdanniversary.template.thirdrecord')
    </section>
    <!-- rule -->
    <div class="page-auto page-rule">
        <h6>< 活动规则 ></h6>
        <p><span>1.</span>活动时间：{{date("Y年m月d日",$activityTime['start'])}}-{{date("m月d日",$activityTime['end'])}} </p>
        <p><span>2.</span>活动期间内，每日会在当日投资优选项目为3万的整数倍的投资者中随机抽选一名，获得当日的惊喜奖；于次日11点公布昨日中奖信息，节假日顺延；</p>
        <p><span>3.</span>活动期间内，获奖者提现金额≥10000元，取消其领奖资格；</p>
        <p><span>4.</span>活动所得奖品以实物形式发放，客服将在2017年7月30日之前，与您沟通联系确定发放奖品；如再次期间联系未果，则视为自动放弃奖励；</p>
        <p><span>5.</span>活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服；</p>
        <p><span>6.</span>网贷有风险、投资需谨慎。</p>
    </div>
    <!-- rain  -->
    <div class="page_rain" style="display: none;" attr-static-local="{{env('STATIC_URL_HTTPS')}}">
      <div class="db-time"></div>
      <div class="div bg_1"></div>
        <!-- 红包 -->
        <div class="kill-pop1 kill-pop1-3" id="lottery-thing-bonus" style="display: none;">
            <h4>恭喜你获得了</h4>
            <div class="db-coupon">
                 <dl>
                    <dt>￥<span>10</span><em class="db-line3"></em></dt>
                    <dd><p>满3000元可用</p><p>投资九省心</p><p>及九安心项目</p></dd>

                </dl>
            </div>
            <a href="javascript:;" class="db-btn-2 db-btn-1 btn-default-1">确定</a>
        </div>
    </div>

    <!-- 弹窗红包奖励1,未登录-->
    <div class="kill-pop-wrap-1" style="display: none;">
        <div class="mask3"></div>
        <div class="kill-pop1">
            <h4 class="mt">您还没有登录哦</h4>
            <a href="javascript:;" class="db-btn-1" id="userLogin">登录</a>
        </div>
    </div>

    <!-- 图片倒计时 -->
    <div class="kill-pop-wrap1" style="display: none;">
        <div class="mask3"></div>
        <!-- 登录后状态 -->
        <div class="kill-time">
            <img src="{{assetUrlByCdn('/static/weixin/activity/redpacketrain/images/time3.png')}}" data-src="{{assetUrlByCdn('/static/weixin/activity/redpacketrain/images/time')}}">
        </div>
    </div>
</div>

@endsection

@section('footer')

@endsection

@section('jsScript')

<script type="text/javascript">
    //文字滚动
    function AutoScroll(obj) {
        $(obj).find("ul:first").animate({
            marginTop: "-1.275rem"
        }, 500, function() {
            $(this).css({ marginTop: "0px" }).find("li:first").appendTo(this);
        });
    }

    $(document).ready(function() {
        var myar = setInterval('AutoScroll("#scrollDiv")', 2000);
         $("#scrollDiv").hover(function() { clearInterval(myar); }, function() { myar = setInterval('AutoScroll("#scrollDiv")', 2000) });
    });
    $(document).delegate(".investClick",'click',function () {

        var  projectId  =   $(this).attr("attr-data-id");
        if( !projectId ||projectId==0){
            return false;
        }
        var version     =    "{{$version or ''}}";
        var act_token   =   '{{$actToken}}_' + projectId;

        if( client =='ios'){
            if( version && version <'4.1.0'){
                window.location.href="objc:certificationOrInvestment("+projectId+",1)";
                return false;
            }
            if(!version || version >='4.1.0') {
                window.location.href="objc:toProjectDetail("+projectId+",1,"+act_token+")";
                return false;
            }
        }
        if (client =='android') {
            if( version <'4.1.0' ) {
                window.jiudouyu.fromNoviceActivity(projectId,1);
                return false;
            }
            if( version >='4.1.0' ) {
                window.jiudouyu.fromNoviceActivity(projectId,1,act_token);
                return false;
            }
        }
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
    })
</script>
<script type="text/javascript" src="{{ assetUrlByCdn('/static/weixin/activity/thirdanniversary/js/rain.js')}}"></script>
@endsection
