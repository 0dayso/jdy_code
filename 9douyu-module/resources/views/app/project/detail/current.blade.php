@extends('Weixin.Common.app_base')
@section('title')<title>零钱计划详情</title>@show
@section('content')
<article>
     <div class="t-detail-17">
           <img src="__PUBLIC2__/app/images/t-app-img12.png">
     </div>
    <div class="t-coupon">
      	<h3 class="t-coupon-1"><span class="t-icon1"></span>什么是零钱计划</h3>
            <div class="t-coupon-2">
                  <p class="t-detail-11">零钱计划是九斗鱼根据投资者的委托，在满足本协议相关规则的前提下平台为出借人推荐优质债权进行分散投资并自动转让的零钱计划，出借人可随时申请转入转出。加入零钱计划的资金将投资到九斗鱼精心筛选的商业保理、中小企业债权、个人消费信贷等项目，部分资产类型在一段时间无法配置属于正常情况。 </p>
                  <h4 class="t-detail-18">温馨提示：</h4>
                  <dl class="t-detail-20">
                        <dt>●</dt>
                        <dd>出借人在九斗鱼加入、转出零钱计划均不需支付任何费用。</dd>
                  </dl>
            </div> 
    </div>

     <div class="t-coupon">
            <h3 class="t-coupon-1"><span class="t-icon1"></span>转入零钱计划</h3>
            <div class="t-coupon-2">
                  <p class="t-detail-11">登录九斗鱼账户，选择零钱计划并输入加入金额，可将账户余额转入到零钱计划账户中，资金不站岗天天拿收益。我们的零钱计划现处于试运行阶段，每位用户加入零钱计划的金额上限为10万元，每次用户加入零钱计划时系统都会检测已加入零钱计划的资金并计算剩余可加入额度。</p>
            </div> 
    </div>

    <div class="t-coupon">
        <h3 class="t-coupon-1"><span class="t-icon1"></span>转出零钱计划</h3>
            <div class="t-coupon-2">
                  <p class="t-detail-11">登录九斗鱼，进入我的账户中零钱计划详情页，选择转出后输入转出金额确定即可，转出金额实时到达账户余额，不收取任何费用。 </p>
                  <h4 class="t-detail-18">温馨提示：</h4>
                  <dl class="t-detail-20">
                        <dt>●</dt>
                        <dd>单人单日转出限额为5万元。</dd>
                        <dt>●</dt>
                        <dd>为避免平台发生流动性风险，系统设置每日转出额度为全部用户持有的零钱计划总额的20%，一旦当日转出额度用尽，用户无法申请转出，在次日开放新的转出额度后可重新申请转出零钱计划。</dd>
                  </dl>
            </div> 
    </div>
      <div class="t-coupon t-mb20px">
                  <h3 class="t-coupon-1"><span class="t-icon1"></span>零钱计划收益</h3>
                  <div class="t-coupon-2">
                  <p class="t-detail-14"><span>●</span>资金转入零钱计划当日计息，转出当日不计息。用户每日的收益在次日凌晨00:00计算并发放至零钱计划总额，随后会显示在昨日收益位置并可查看零钱计划金额投资的债权，只要零钱计划总额大于0.01元时都可以申请转出，不受时间限制。</p>
                  <p class="t-detail-14"><span>●</span>收益每日计算，次日0点返还至零钱计划总额，当日收益＝当日零钱计划总额（每日24点结算）＊借款利率／365 ，因此加入当日显示的利率为加入当日零钱计划计算收益的借款利率。</p>
                  <p class="t-detail-21"><span>注意：</span>每日收益四舍五入后不足0.01元不计入零钱计划账户</p>
            </div>
      </div>
      
</article>
@show
@section('jsPage')
<script type="text/javascript">
$(document.body).css("background","#f4f4f4");
</script> 

@show
