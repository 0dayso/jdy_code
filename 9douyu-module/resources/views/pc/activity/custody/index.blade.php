@extends('pc.common.layout')

@section('title', '九斗鱼携手江西银行达成资金存管合作')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('csspage')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/activity/custody/css/index.css')}}">
@endsection
@section('content')
<div class="page-banner"></div>
<div class="page-wrap clearfix">
    <div class="page-left">
        <h4>合规升级 <br>九斗鱼携手江西银行达成资金存管合作</h4>
        <p>2017年6月28日，国内领先的互联网金融平台九斗鱼正式与江西银行达成资金存管合作，并开始启动双方系统的技术对接工作。存管系统上线后，投资人的资金将全部迁移至银行存管系统开设的对应独立账户，由江西银行对资金进行全面监督管理，交易流程更加透明，投资人资金更加安全。</p>
    </div>
    <div class="page-right">
        <img src="{{assetUrlByCdn('/static/activity/custody/images/page-photo-min.png')}}" width="395">
    </div>
</div>
<div class="page-middle">
    <div class="page-wrap">
        <h4>实力铸就专业  江西银行助力九斗鱼加筑资金安全防火墙</h4>
        <p>江西银行总部位于江西省南昌市，注册资本23.82亿元。在英国《银行家》（The Banker）杂志2016年全球1000家大银行榜单中，江西银行一级资本规模居全球308位，一级资本增速在全球银行排名第六。</p>
        <p>江西银行的资金存管为银行直接存管模式，会为投资人、借款人开设独立存管账户，就充值、提现等支付结算和资金流向进行监管。在直接存管模式下，因为资金并不流向平台，可将平台资金和投资人资金有效隔离，能阻断平台触碰资金的可能性，最大程度地保障交易流程的真实性和投资人的资金安全。</p>
        <p>经过对多家银行的深度考察，九斗鱼最终选择江西银行作为合作伙伴，源于其资金存管系统在技术性、安全性、稳定性等方面均处于行业领先水平。同时，江西银行也对九斗鱼的产业布局、业务模式、风控手段、资产安全性等颇为认可，双方将在合规整改期内，加紧完成技术对接和系统调试。</p>
    </div>
</div>
<div class="page-bottom">
    <div class="page-wrap">
        <h4>拥抱监管  九斗鱼不断提升交易安全等级</h4>
        <p>九斗鱼自成立以来一直拥抱监管、坚持合规经营，致力于为用户提供安全、透明的信息中介服务。存管系统上线后，将在以下三个方面为用户提供全方位的资金安全升级服务：</p>
        <div class="page-list clearfix">
            <dl class="dl1">
                <dt>资金全面隔离</dt>
                <dd>银行为用户设立独立账户，并与平台运营账户分离，实现平台自有资金和用户资金全面隔离。</dd>
            </dl>
            <dl class="dl2">
                <dt>信息更加透明</dt>
                <dd>银行存管系统根据用户指令进行交易操作，并对交易信息存档，用户可通过存管账户进行查询。</dd>
            </dl>
            <dl class="dl3">
                <dt>用户授权交易</dt>
                <dd>用户发起的所有资金变动操作，均需输入其在存管银行设置的交易密码，银行依据密码授权完成操作</dd>
            </dl>
        </div>
    </div>
</div>
@endsection


