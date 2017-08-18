@extends('wap.common.wapBase')

@section('title', '我的信息页')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/partner3.css') }}">
@endsection
@section('content')
<article>
    <section class="w-information">
      <div class="w-information-1">
        <p>昨日收益</p>
        <p><span>{{ $userInfo["yesterday_interest"] }}</span> 元</p>
      </div>
      <div class="w-information-2">
        <p>佣金余额</p>
        <p><span>{{ $userInfo["cash"] }}</span> 元</p>
      </div>
    </section>
   <section class="w-information-bj">
     <ul class="w-information1-3">
         @if(Session::has('message'))
             <li><span style="color: red">x 提示! {{ Session::get('message') }} </span></li>
         @endif
          <li><a href="{{ $url }}" ><span>我的佣金率</span><span class="frb">{{ $rate }}%</span><i></i></a></li>
          <li><span>累计佣金收益</span><span class="fr">{{ $userInfo["interest"] }}元</span></li>
          {{--<li><a href="/ActivityPartner/details"><span >邀请合伙人数</span><span class="frb">{{ $inviteCount }}人</span><i></i></a></li>
          <li><a href="/ActivityPartner/details"><span >邀请的合伙人待收</span><span class="frb">{{ $refundInfo }}元</span><i></i></a></li>--}}
          <li><span >邀请合伙人数</span><span class="fr">{{ $inviteCount }}人</span></li>
          <li><span >邀请的合伙人待收</span><span class="fr">{{ $refundInfo['total_cash'] }}元</span></li>
         @if ( $status == 3 )
            <li><a href="/ActivityPartner/scanCode"><span>面对面邀请</span><i></i></a></li>
         @endif
         <li><a href="javascript:" id="withdraw"><span>转出收益</span><i></i></a></li>

        </ul>


   </section>

    <a href="/activity/y2015partner?rule=1"  class="gray-title-bj mt15px mr15px w-bule-color w-fff-bj plr15px fr">查看活动规则</a>
    <div class="clear"></div>
    <section class="w-line"></section>
    @if ( $status == 3 ) 
    <section class="w-bottom">
      <div class="w-bottom-btn">
          <a href="javascript:;" class="w-btn">邀请好友</a>
      </div>
    </section>
    @endif

    <!-- 交易密码弹层开始 -->
    <section class="wap2-pop hide" id="winDraw">
        <div class="wap2-pop-mask"></div>
        <div class="wap2-pop-main top-60px">
            <div class="wap2-pop-tpw-title">
                <p class="w-information-p">转出佣金收益</p>
            </div>
            <div class="wap2-pop-tpw-box clearfix">
                <form name="doWithdraw" action='{{ env("WAP_URL_HTTPS") }}/ActivityPartner/doWithdraw' method="post" id="doWithdraw">
                <input type="hidden" name="partner_cash" id="partner_cash" value="{{ $userInfo["cash"] }}" />
                <p class="w-information-p1"><span>转出金额</span><input type="text" name="cash" id="cash" placeholder="请输入转出金额"><em>元</em></p>
                <div class="clear"></div>
                <p class="w-information-p2" id="tips">该笔最多可转出{{ $userInfo["cash"] }}元</p>
                <p class="w-information-p1"><span>交易密码</span><input type="password" name="trading_password" placeholder="@if($isTradePassword==true)请输入交易密码 @else 请设置交易密码 @endif"></p>
                    @if($isTradePassword ==  false)
                        <p class="partner-set">还未设置交易密码,请设置</p>
                        @else
                        <p class="partner-set"></p>
                    @endif
                {{--<p class="partner-set"></p>--}}
                    <div class="partner-tips"></div>
                    <input type="hidden" name="untoken" value="{{ $untoken }}" />
                <input type="reset" id="closeWin" value="取消" class="wap2-btn wap2-btn-half fl wap2-btn-blue">
                <input type="button" id="sub" value="确定" class="wap2-btn wap2-btn-half fr">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                </form>
                
            </div>
        </div>
    </section>
    <!-- 交易密码弹层结束 -->
</article>

<div class="partner-pop-wrap">
  <div class="partner-pop-mask"></div>
  <div class="partner-pop"><img src="{{assetUrlByCdn('/static/weixin/images/x-partner2-share-img.png')}}" class="x-partner2-share-img"></div>
</div>
@endsection

@section('jsScript')
<script type="text/javascript">
    (function($){
        $(document).ready(function(){
          // 分享弹层
          $(".w-btn").click(function(){
            $(".partner-pop-wrap").show();
          });
          $(".partner-pop-wrap").click(function(){
            $(this).hide();
          });


            $("#withdraw").click(function(){
                $(document).scrollTop(0);
                $("#winDraw").show();
            });
            $("#closeWin").click(function(){
                $("#winDraw").hide();
            });
            $("#sub").click(function(){
                $(".partner-tips").hide();
                var cash     = new Number($.trim($("input[name=cash]").val()));
                cash         = cash.toFixed(2);
                var balance  = new Number($.trim($("input[name=partner_cash]").val()));
                balance      = balance.toFixed(2);
                console.log("cash :"+cash);
                console.log("balance :"+balance);
                if( cash<=0 || isNaN(cash)){
                    $(".partner-tips").html("请输入正确的金额！");
                    $(".partner-tips").show();
                    return false;
                }
                if( cash>balance ){
                    $(".partner-tips").html("转出金额不能超过佣金总额！");
                    $(".partner-tips").show();
                    return false;
                }
                var password  = $("input[name=trading_password]");
                var passwordV = $.trim($("input[name=trading_password]").val());
                if(passwordV == ''){
                    $(".partner-tips").html("请输入交易密码！");
                    $(".partner-tips").show();
                    return false;
                }
                var isflag = 1;
                $.ajax({
                    url:'/password/ajaxCheckTradePassword',
                    type:'POST',
                    data:{trading_password:passwordV},
                    dataType:'json',
                    async: false,  //同步发送请求
                    success:function(result){
                        console.log(result);
                        if(result.status == false) {
                            password.val('');
                            password.attr("placeholder", result.msg);
                            $(".partner-tips").html(result.msg);
                            $(".partner-tips").show();
                            isflag = 2;
                            return false;
                        }else{
                            $(".partner-tips").html("");
                            $(".partner-tips").hide();
                            //$("#sub").attr('disabled',"disabled");
                            $("#doWithdraw").submit();
                            $("#sub").attr('disabled',"disabled");
                        }
                    }
                });
                if(isflag == 2){
                    return false;
                }
            });
        });
    })(jQuery);

</script>
@endsection
