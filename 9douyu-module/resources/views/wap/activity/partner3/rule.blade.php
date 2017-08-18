@extends('wap.common.wapBase')

@section('title', '九斗鱼合伙人计划')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn ('/static/weixin/activity/partner3/css/partner.css') }}">
@endsection

@section('content')
    <article>
		<section class="partner-share-rule">
			<h4>举例说明</h4>
			<p>以您邀请了3个好友为例，如果全部好友的当前在投本金为100万元，每天您可获得佣金收益35.62元。如果好友连续投资1年，您可获得1.3万元的佣金收益！</p>
			<h5>计算公式：</h5>
			<p>佣金收益率=1%+投资好友人数×0.1%=1%+3×0.1%=1.3%<br>
			   每日佣金收益=100万元×1.3%÷365=35.62元<br>
			   1年总计收益=35.62×365=13001.3元
			</p>
			<h4>规则详情</h4>
			<p>一、活动时间<br>
                {{ date('Y年m月d日', strtotime($shareConfig['START_DATE'])) }} ~ {{ date('Y年m月d日', strtotime($shareConfig['END_DATE'])) }}
			</p>
			<p class="rule2">二、活动规则</p>
			<h6><span>1</span>如何邀请好友（以下任意一种）</h6>
			<p>1.好友通过分享链接完成注册；</p>
			<p>2.好友注册时，填写您的邀请码（即手机号）。<br>
				注意：您新邀请的好友，可在好友注册完成1小时后，<br>
				通过App查看好友相关信息。
			</p>
			<h6><span>2</span>佣金计算与发放</h6>
			<p>1.每日23点前，发放前一日的佣金收益；</p>
			<p>2.今日返佣金额=昨日好友在投本金×昨日佣金收益率÷365；</p>
			<p>3.好友在投本金：包括好友当前投资零钱计划和优选项目的本金；</p>
			<p>4.佣金收益率=1%+投资好友人数×0.1%，最高上限为3%；</p>
			<p>5.每日获得的佣金，将发放至您的佣金余额中，并可转出至零钱计划。</p>
			<h6><span>3</span>不能获得奖励的情况（以下任意一种）</h6>
			<p>1.好友未通过分享链接完成注册；</p>
			<p>2.好友注册时未填写您的邀请码（即手机号）。</p>
		</section>
		<footer class="partner-rule-footer">
			<p>
				※本活动最终解释权归九斗鱼所有<br>
			如有疑问请咨询客服电话：400-6686-568（9:00~18:00）
			</p>
		</footer>
    </article>
@include('wap.common.sharejs')
@endsection
@section('jsScript')
<script>
</script>
@endsection



