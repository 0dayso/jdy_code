@extends('wap.common.activity')

@section('title', '陪伴是最长情的告白')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/activity/motherday/css/index.css')}}">
@endsection

@section('content')
    <article class="page-bg">
    	<!-- banner -->
    	<section class="page-center page-time">
            <p>活动时间：{{date("Y年m月d日",$activityTime['start'])}}－{{date("m月d日",$activityTime['end'])}}</p>
    	</section>
    	<!-- End banner -->
        <div class="page-title1"></div>
        <p class="page-center page-font1">每个用户id每日限领取一张优惠券</p>

        <ul class="page-center page-coupons" id="coupon-status" attr-receive-lock = 'opened'>
            <li class="cash coupon-btn-bonus" data-layer="page-layer" data-touch="false" attr-bonus-value="ten">
                <p><span>10</span>元</p>
                <p>投资满3000元可用</p>
            </li>
            <li class="cash coupon-btn-bonus" data-layer="page-layer" data-touch="false" attr-bonus-value="thirty">
                <p><span>30</span>元</p>
                <p>投资满5000元可用</p>
            </li>
            <li class="cash coupon-btn-bonus" data-layer="page-layer" data-touch="false" attr-bonus-value="fifty">
                <p><span>50</span>元</p>
                <p>投资满8000元可用</p>
            </li>
            <li class="interest coupon-btn-bonus" data-layer="page-layer" data-touch="false" attr-bonus-value="point5">
                <p><span>1</span>%定期</p>
                <p>起投金额30000元</p>
            </li>
            <li class="interest coupon-btn-bonus" data-layer="page-layer" data-touch="false" attr-bonus-value="per1">
                <p><span>2</span>%定期</p>
                <p>起投金额50000元</p>
            </li>
        </ul>
        <div class="page-wrap">
@if( !empty($projectList) )
    @foreach( $projectList as $key => $project)
            <div class="page-project">
                <p class="title">{{$project['product_line_note']}}  <span>•  {{$project['format_invest_time']}}{{$project['invest_time_unit']}}</span></p>
                <table>
                    <tr>
                        <td width="28%"><span>{{(float)$project['profit_percentage']}}</span><em>％</em></td>
                        <td width="30%">{{$project['left_amount']}}元</td>
                        <td rowspan="2">
                            @if( $userStatus == true)
                                @if($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING && \App\Tools\ToolTime::getUnixTime($project['publish_at'],'default') >= time())
                                    <a href="javascript:;" class="btn disable investProject" attr-project-id="{{$project['id']}}">敬请期待</a>
                                @elseif($project['status'] == \App\Http\Dbs\Project\ProjectDb::STATUS_INVESTING)
                                    <a href="javascript:;" class="btn investProject" attr-project-id="{{$project['id']}}">立即出借</a>
                                @else
                                    <a href="javascript:;" class="btn disable investProject" attr-project-id="{{$project['id']}}">已售罄</a>
                                @endif
                            @else
                                <a href="javascript:;" class="btn user-login-alert">立即出借</a>
                            @endif
                        </td>
                    </tr>
                    <tr><td>借款利率</td><td>剩余可投</td> </tr>
                </table>
            </div>
    @endforeach
@endif
        </div>

        <div class="page-title2">每日惊喜</div>
        <p class="page-center page-font2">每日投资定期项目即有机会获得惊喜奖</p>
        <div class="page-title3">今日礼品</div>

        <div class="page-center page-surprise">
            @if(!empty($lotteryInfo['lottery']))

            <img src="{{ assetUrlByCdn("/static/weixin/activity/motherday/images/prize".$lotteryInfo['lottery']['order_num'].".png") }}">
             <p>{{$lotteryInfo['lottery']['name']}}</p>
            @else

            <img src="{{ assetUrlByCdn("/static/weixin/activity/motherday/images/prize1.png") }}">
            <p>jbl蓝牙音箱</p>
            @endif
        </div>


        <div class="page-title4">获奖名单</div>
        <div class="page-winner-list">
            @if($lotteryInfo['record']['lotteryNum'] >0)
            @foreach( $lotteryInfo['record']['list'] as $key => $record )
            <p><span>{{date("m月d日",strtotime($record['created_at']))}}</span>{{\App\Tools\ToolStr::hidePhone($record['phone'],3,4)}}</p>
            @endforeach
            @else
            <p><span>{{date("m月d日",time())}}</span>暂无中奖数据</p>
            @endif
        </div>
        <!-- rule -->
         <div class="page-rule">
              <h6>活动规则</h6>
              <p><span>1.  </span>活动时间：{{date("Y年m月d日",$activityTime['start'])}}－{{date("m月d日",$activityTime['end'])}}；</p>
              <p><span>2.  </span>活动期间内，每个用户ID每日仅限领取一张优惠券，而非每个不同优惠券各领取一张；</p>
              <p><span>3.  </span>活动期间内，每日在投资定期项目的投资者中，随机抽取一名获奖者，获得当日对应的实物奖品；中奖信息将于下一个工作日11点开奖；</p>
              <p><span>4.  </span>活动期间内，获得实物奖品者如提现金额≥10000元，则取消其领奖资格；</p>
              <p><span>5.  </span>活动所得奖品以实物形式发放，将在2017年6月30日之前，与您沟通联系确定发放奖品。如联系用户无回应，视为自动放弃活动奖励；</p>
              <p><span>6.  </span>活动期间如有任何疑问请致电九斗鱼官方客服：400-6686-568，或登录九斗鱼咨询在线客服；</p>
              <p><span>7.  </span>网贷有风险 投资需谨慎。</p>
        </div>
</article>
        <!-- End rule -->
        <!-- pop  领取1%定期加息券-->
        <section class="pop-wrap coupon-alert">
            <div class="pop-mask"></div>
            <div class="pop">
                <span class="pop-close"></span>
                <p class="pop-text">确定领取 <br>30元现金券？</p>
                <a href="javascript:;" class="pop-btn receive">确定</a>
                <p class="pop-text-desc">满3000元可用</p>
            </div>
        </section>
        <!-- End pop -->

        <!-- pop  登 录-->
        <section class="pop-wrap cash6">
            <div class="pop-mask"></div>
            <div class="pop">
                <span class="pop-close"></span>
                <p class="pop-text">客官，别急<br>还没登录呢 </p>
                <a href="javascript:;" class="pop-btn userDoLogin">登 录</a>
            </div>
        </section>
        <!-- End pop -->

        <!-- pop  领取成功,失败-->
        <section class="pop-wrap cash7 receive-coupon-result">
            <div class="pop-mask"></div>
            <div class="pop">
                <span class="pop-close"></span>
                <span class="receive-result">
                    <div class="c-fail"></div>
                    <p class="pop-text">请刷新页面重新领取</p>
                    <a href="javascript:;" class="pop-btn">我知道了</a>
                </span>
            </div>
        </section>
@if( !empty($couponBonus) )
        <span class="set-bonus-message" style="display: none;">
@foreach($couponBonus as $key => $bonus)
@if($bonus['type'] ==App\Http\Dbs\Bonus\BonusDb::TYPE_CASH)
        <strong class="bonus-{{$bonus['custom_value']}}" attr-used-desc ='{{$bonus['using_desc']}}' attr-value-desc ='{{(float)$bonus['money']}}现金券'></strong>
@elseif($bonus['type'] ==App\Http\Dbs\Bonus\BonusDb::TYPE_COUPON_INTEREST)
        <strong class="bonus-{{$bonus['custom_value']}}" attr-used-desc ='{{$bonus['using_desc']}}' attr-value-desc ='{{(float)$bonus['rate']}}%加息券'></strong>
@else
        <strong class="bonus-{{$bonus['custom_value']}}" attr-used-desc ='{{$bonus['using_desc']}}' attr-value-desc ='{{(float)$bonus['rate']}}%加息券'></strong>
@endif
@endforeach
        </span>
@endif
        <!-- End pop -->
@endsection

@section('jsScript')
    <script>
    $(function(){
var client = getCookie('JDY_CLIENT_COOKIES');
        if( client == '' || !client ){
        var client  =   '{{$client}}';
        }
        function alertpop(element,pop){
            $(element).click(function(e){
                e.preventDefault();
                var $this       =   $(this);
                var userStatus  =   "{{$userStatus}}";
                if( userStatus == true ){
                    alertCouponPop($this,pop) ;
                }else{
                    $('.cash6').show();
                }
            })
        }
        $(document).on("click", '.receive',function(event){
            event.stopPropagation();
            var $target =   $('.coupon-alert');
            var value   =   $(this).attr('attr-bonus-value');
            $target.hide()
            receiveBonusControl(value);
        })
        $(document).on("click", '.user-login-alert',function(event){
            event.stopPropagation();
            $(".cash6").show();
        })
        $(document).on('click','.pop-close,.pop-btn',function() {
            $('.pop-wrap').hide();
            $('#coupon-status').attr('attr-receive-lock','opened');
        })
        var alertCouponPop = function (obj,pop) {
            var target      =   obj.attr('attr-bonus-value');
            var $couponLock =   $('#coupon-status')
            var couponLock  =   $couponLock.attr('attr-receive-lock');
            if( couponLock  != 'opened'){
                return false
            }
            var couponCss   =   "c-"+target;
            var $target     =   $(pop);
            $(".receive").attr('attr-bonus-value',target);
            $target.find('.pop div').removeClass().addClass(couponCss);
            var desc = $(".bonus-"+target).attr('attr-used-desc');
            var value = $(".bonus-"+target).attr('attr-value-desc');
            $target.find(".pop-text").empty().html('确定领取</br>' + value)
            $target.find(".pop-text-desc").empty().html(desc)
            $couponLock.attr('attr-receive-lock','closed');
            $target.show();
        }
        var receiveBonusControl = function (value) {
            var userStatus = '{{$userStatus}}';
            if( userStatus == false ) {
                $('.cash6').show();
                return false
            }
            var $receiveBtn =  $(".receive");
            var lock        =   $receiveBtn.attr("lock-status");
            if( lock == 'closed'){
                return false;
            }
            $receiveBtn.attr("lock-status",'closed');
            $.ajax({
                url      :"/activity/receive",
                dataType :'json',
                data: {from:'app',custom_value:value,_token:'{{csrf_token()}}'},
                type     :'post',
                success : function(json){
                    var $targetLayer=$(".receive-coupon-result");

                    if( json.status==true || json.code==200){
                        var returnHtml = '<div class="c-success"></div>'+
                                        '<p class="pop-text">请在<span>[资产－我的优惠券] </span>中查看</p>';
                    } else if( json.status == false || json.code ==500 ){
                        var returnHtml  =   '<div class="c-fail"></div>'+
                                        '<p class="pop-text">'+json.msg+'</p>'
                    }
                    returnHtml  =   returnHtml + '<a href="javascript:;" class="pop-btn">我知道了</a>';
                    $targetLayer.find('.receive-result').html(returnHtml)
                    $targetLayer.show();
                    $receiveBtn.attr("lock-status",'opened');
                    $('#coupon-status').attr('attr-receive-lock','opened');
                    return false;
                },
                error : function(msg) {
                    //$(".layer-fail").show();
                    $receiveBtn.attr("lock-status",'opened');
                }
            })
        }

        alertpop(".coupon-btn-bonus",'.coupon-alert');
    	// 弹层关闭按钮
        $('.pop-close').click(function(){
            $('.pop-wrap').hide();
            $('#coupon-status').attr('attr-receive-lock','opened');
        })
        $('.investProject').click(function () {
            var  projectId  =   $(this).attr("attr-project-id");

            if( !projectId ||projectId==0 ){
                return false;
            }
            if( client =='ios'){
                window.location.href="objc:toProjectDetail("+projectId+",1)";
                return false;
            }
            if (client =='android'){
                window.jiudouyu.fromNoviceActivity(projectId,1);
                return false;
            }
            window.location.href='/project/detail/'+projectId;
            return false
        })
        $(".userDoLogin").click(function () {
            if( client =='ios'){
                window.location.href = "objc:gotoLogin";
                return false;
            }
            if (client =='android'){
                window.jiudouyu.login();
                return false;
            }
            window.location.href='/login';
        })
    })
    </script>
@endsection
