@extends('wap.common.wapBase')

@section('title', '九斗鱼合伙人计划')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn ('/static/weixin/activity/partner3/css/partner.css') }}">
	<style>
		body{
			background: -webkit-linear-gradient(#ffb132, #ff7521); /* Safari 5.1 - 6.0 */
			background: -o-linear-gradient(#ffb132, #ff7521); /* Opera 11.1 - 12.0 */
			background: -moz-linear-gradient(#ffb132, #ff7521); /* Firefox 3.6 - 15 */
			background: linear-gradient(#ffb132, #ff7521); /* 标准的语法 */
		}
	</style>
@endsection

@section('content')
    <article class="page-auto">
		<section class="partner-rule-banner">
			<time>活动时间：{{ date('Y年m月d日', strtotime($shareConfig['START_DATE'])) }}－{{ date('Y年m月d日', strtotime($shareConfig['END_DATE'])) }}</time>
		</section>
		<section class="partner-rule-bt1">
			<p class="partner-rule-txt txt1">邀请的好友，在九斗鱼注册并投资，您最高能获得3%的佣金年化收益。</p>
			<p class="partner-rule-txt txt2">简单说：自己邀请的好友在九斗鱼一年投资100万，自己最高可额外获得3万元的佣金。躺着也能赚钱。</p>
		</section>

		<section class="partner-rule-bt2">
			<p class="partner-rule-txt txt1">老王邀请了4位好朋友来九斗鱼注册其中有3位好友投资了3月期项目170万元</p>
			<p class="partner-rule-txt txt2">这样，老王的佣金收益率就是：基础利率1%+好友加息利率0.3%=1.3%</p>
			<div class="partner-rule-total">
				<p>佣金总额：170万元 X 1.3% X 3/12 = 5525元</p>
				<p>每日佣金：170万元 X 1.3% ÷ 365 = 60.54元</p>
			</div>
		</section>

		<section class="partner-rule-bt3">
			<p class="partner-rule-txt txt1">疑问？为什么老王邀请了4名好友，加息只计算3位好友？</p>
			<p class="partner-rule-txt txt2">答：虽然邀请了4位好友，但只有3位好友投资，所以加息按3位好友计算，加息0.3%</p>
		</section>
		{{--规则说明--}}
		<section class="partner-rule-bt4">

		</section>
		<section class="partner-rule-bottom">
		<div class="partner-rule-details">
			<p><span>1</span>活动时间&奖励时间：{{ date('Y年m月d日', strtotime($shareConfig['START_DATE'])) }} 至 {{ date('Y年m月d日', strtotime($shareConfig['END_DATE'])) }}。</p>

			<p><span>2</span>参与用户：九斗鱼已注册的用户都可以进行邀请好友，当好友投资后，即可获得佣金收益。</p>

			<p><span>3</span>佣金利率：佣金基础收益率{{ $shareConfig['BASE_RATE'] }}%，每邀请1名投资好友，佣金收益率在{{ $shareConfig['BASE_RATE'] }}%的基础上增加{{ $shareConfig['ADD_RATE'] }}%。佣金利率最高{{ $shareConfig['MAX_RATE'] }}%封顶。</p>
			<p><span class="spantop">4</span>佣金收益只能将整数金额转到零钱计划。</p>
			<p><span>5</span>待收本金是指用户使用自有资金正在投资九省心、九安心、零钱计划、变现宝项目的在投本金。</p>
			<p><span>6</span>分享给好友：登录【手机客户端】-【资产】-【邀请好友】将页面或二维码分享给好友即可参与本活动。</p>
			<p><span>7</span>登录九斗鱼后，点击【我的资产】-【邀请好友】即可查看前一天邀请的好友人数、好友待收本金、昨日佣金收益等。</p>
			<p><span class="spantop">8</span>本次活动的最终解释权归九斗鱼平台所有。</p>

		</div>


		</section>
    </article>
@include('wap.common.sharejs')
@endsection
@section('jsScript')
<script>
</script>
@endsection



