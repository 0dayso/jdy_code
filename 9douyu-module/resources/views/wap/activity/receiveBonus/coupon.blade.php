@extends('wap.common.wapBase')

@section('title', '春风十里 不如礼')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/activity/cashCoupon/css/index.css')}}">
@endsection

@section('content')
    <article>
    	<!-- banner -->
    	<section class="banner">
            <p>活动时间：3月1日－3月12日</p>
    	</section>
    	<!-- End banner -->

        <div class="c-cash">
            <p>每个用户 ID每日仅限领取一张优惠券</p>
            <a href="#" class="cash-1"></a> 
            <a href="#" class="cash-2"></a> 
            <a href="#" class="cash-3"></a> 
            <a href="#" class="cash-4"></a> 
            <a href="#" class="cash-5"></a> 

        </div>


        <div class="c-box">
            <table class="c-table c-0" >
                <tr>
                    <th colspan="4">九省心  <span>29天</span></th>
                </tr>
                 <tr>
                    <td>借款利率</td>
                    <td>期限</td>
                    <td>剩余可投</td>
                    <td rowspan="2">
                        <a href="#" class="c-btn">立即购买</a>
                    </td>
                </tr>
                <tr>
                    <td class="yellow" ><strong>9</strong><em>％</em></td>
                    <td ><big>29天</big></td>
                    <td><big>344,555元</big></td>
                </tr>
               
            </table>

             <table class="c-table c-1">
                <tr>
                    <th colspan="4">九省心  <span>12月期</span></th>
                </tr>
                 <tr>
                    <td>借款利率</td>
                    <td>期限</td>
                    <td>剩余可投</td>
                    <td rowspan="2">
                        <a href="#" class="c-btn disable">已售罄</a>
                    </td>
                </tr>
                <tr>
                    <td class="yellow" ><strong>12</strong><em>％</em></td>
                    <td ><big>12月期</big></td>
                    <td><big>4,344,555元</big></td>
                </tr>
               
            </table>
        </div>

        <div class="c-box1">
            <table class="c-table c-3" >
                <tr>
                    <th colspan="4">九省心  <span>29天</span></th>
                </tr>
                 <tr>
                    <td>借款利率</td>
                    <td>期限</td>
                    <td>剩余可投</td>
                    <td rowspan="2">
                        <a href="#" class="c-btn">立即购买</a>
                    </td>
                </tr>
                <tr>
                    <td class="yellow" ><strong>9</strong><em>％</em></td>
                    <td ><big>29天</big></td>
                    <td><big>344,555元</big></td>
                </tr>
               
            </table>

             <table class="c-table c-1">
                <tr>
                    <th colspan="4">九省心  <span>12月期</span></th>
                </tr>
                 <tr>
                    <td>借款利率</td>
                    <td>期限</td>
                    <td>剩余可投</td>
                    <td rowspan="2">
                        <a href="#" class="c-btn disable">已售罄</a>
                    </td>
                </tr>
                <tr>
                    <td class="yellow" ><strong>12</strong><em>％</em></td>
                    <td ><big>12月期</big></td>
                    <td><big>344,555元</big></td>
                </tr>
               
            </table>

        </div>
        <div class="c-prize">
           <p class="c-title">每日投资定期项目既有机会获得惊喜奖</p>
           <p class="c-prize1">PHILIPS 电动剃须刀</p>
           <img src="{{ assetUrlByCdn('/static/weixin/activity/cashCoupon/images/prize0.png') }}" class="img c-prize2">
           <!-- <img src="{{ assetUrlByCdn('/static/weixin/activity/cashCoupon/images/prize1.png') }}" class="img c-prize2">
           <img src="{{ assetUrlByCdn('/static/weixin/activity/cashCoupon/images/prize2.png') }}" class="img c-prize2">
           <img src="{{ assetUrlByCdn('/static/weixin/activity/cashCoupon/images/prize3.png') }}" class="img c-prize2">
           <img src="{{ assetUrlByCdn('/static/weixin/activity/cashCoupon/images/prize4.png') }}" class="img c-prize2">
           <img src="{{ assetUrlByCdn('/static/weixin/activity/cashCoupon/images/prize5.png') }}" class="img c-prize2">
           <img src="{{ assetUrlByCdn('/static/weixin/activity/cashCoupon/images/prize6.png') }}" class="img c-prize2">
           <img src="{{ assetUrlByCdn('/static/weixin/activity/cashCoupon/images/prize7.png') }}" class="img c-prize2">
           <img src="{{ assetUrlByCdn('/static/weixin/activity/cashCoupon/images/prize8.png') }}" class="img c-prize2">
           <img src="{{ assetUrlByCdn('/static/weixin/activity/cashCoupon/images/prize9.png') }}" class="img c-prize2">
           <img src="{{ assetUrlByCdn('/static/weixin/activity/cashCoupon/images/prize10.png') }}" class="img c-prize2">
           <img src="{{ assetUrlByCdn('/static/weixin/activity/cashCoupon/images/prize11.png') }}" class="img c-prize2"> -->
        </div>

        <div class="prize-list">
            <h5>获奖名单</h5>
            <p><span>3月1日</span> <span>138＊＊＊＊9988</span></p>
            <p><span>3月1日</span> <span>138＊＊＊＊9988</span></p>
            <p><span>3月1日</span> <span>138＊＊＊＊9988</span></p>
        </div>

        <!-- rule -->
        <section class="rule">
            <h3>活动规则：</h3>
            <p>1.活动时间：2017年3月1日——3月12日；</p>
            <p>2.活动期间内，每个用户ID每日仅限领 取一张优惠券，而非每个不同优惠券各领取一张；</p>
            <p>3.活动期间内，每日在投资定期项目的投资者中，随机抽取一名获奖者，获得当日对应的实物奖品；中奖信息将于下一个工作日11点开奖；</p>
            <p>4.活动期间内，获得实物奖品者如提现金额≥10000元，则取消其领奖资格；</p>
            <p>5.活动所得奖品以实物形式发放，将在2017年3月31日之前，与您沟通联系确定发放奖品。如联系用户无回应，视为自动放弃活动奖励;</p>
            <p>6.活动期间如有任何疑问请致电九斗鱼官方客服：</p>
            <p>400-6686-568，或登录九斗鱼咨询在线客服。</p>
        </section>
        <!-- End rule -->


        <!-- pop  领取1%定期加息券-->
        <section class="pop-wrap cash5">
            <div class="pop-mask"></div>
            <div class="pop">
                <span class="pop-close"></span>
                <img src="{{ assetUrlByCdn('/static/weixin/activity/cashCoupon/images/text.png') }}" class="img text">
                <a href="javascript:;" class="pop-btn">确定</a>
            </div>
        </section>
        <!-- End pop -->


         <!-- pop  领取0.5%定期加息券-->
        <section class="pop-wrap cash4">
            <div class="pop-mask"></div>
            <div class="pop">
                <span class="pop-close"></span>
                <img src="{{ assetUrlByCdn('/static/weixin/activity/cashCoupon/images/text1.png') }}" class="img text">
                <a href="javascript:;" class="pop-btn">确定</a>
            </div>
        </section>
        <!-- End pop -->

         <!-- pop  领取50元代金券-->
        <section class="pop-wrap cash3">
            <div class="pop-mask"></div>
            <div class="pop">
                <span class="pop-close"></span>
                <img src="{{ assetUrlByCdn('/static/weixin/activity/cashCoupon/images/text2.png') }}" class="img text">
                <a href="javascript:;" class="pop-btn">确定</a>
                <p class="pop-text">投资满10000元可用</p>
            </div>
        </section>
        <!-- End pop -->

        <!-- pop  领取10元代金券-->
        <section class="pop-wrap cash1">
            <div class="pop-mask"></div>
            <div class="pop">
                <span class="pop-close"></span>
                <img src="{{ assetUrlByCdn('/static/weixin/activity/cashCoupon/images/text3.png') }}" class="img text">
                <a href="javascript:;" class="pop-btn">确定</a>
                <p class="pop-text">投资满3000元可用</p>
            </div>
        </section>
        <!-- End pop -->

        <!-- pop  领取30元代金券-->
        <section class="pop-wrap cash2">
            <div class="pop-mask"></div>
            <div class="pop">
                <span class="pop-close"></span>
                <img src="{{ assetUrlByCdn('/static/weixin/activity/cashCoupon/images/text4.png') }}" class="img text">
                <a href="javascript:;" class="pop-btn">确定</a>
                <p class="pop-text">投资满8000元可用</p>
            </div>
        </section>
        <!-- End pop -->

        <!-- pop  登 录-->
        <section class="pop-wrap cash6">
            <div class="pop-mask"></div>
            <div class="pop">
                <span class="pop-close"></span>
                <img src="{{ assetUrlByCdn('/static/weixin/activity/cashCoupon/images/text5.png') }}" class="img text">
                <a href="javascript:;" class="pop-btn">登 录</a>
            </div>
        </section>
        <!-- End pop -->



@endsection
    <!-- End prize pop -->
@section('jsScript')
    <script>
    $(function(){

       function alertpop(a,b){
         $(a).click(function(e){
            e.preventDefault();
            $(b).show();
         })
       }
        alertpop(".cash-1",".cash1");
        alertpop(".cash-2",".cash2");
        alertpop(".cash-3",".cash3");
        alertpop(".cash-4",".cash4");
        alertpop(".cash-5",".cash5");
        // 未登录弹窗
        // alertpop(".cash-6",".cash6");

    	// 弹层关闭按钮
        $('.pop-close').click(function(){
            $('.pop-wrap').hide();
        })
    })
    </script>
@endsection