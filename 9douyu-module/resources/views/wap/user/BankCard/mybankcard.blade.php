@extends('wap.common.wapBase')

@section('title','我的银行卡')

@section('content')
<block name="content">
<article>
    <?php if(empty($cards)): ?>
    <div class="mybankcard mybankcard-unbind">
        <a href="/user/verify">未添加快捷充值卡</a>
    </div>
    <?php else: ?>
    <div class="mybankcard mybankcard-bind">
        @foreach($cards as $k => $bankInfo)
        <dl>
            <dt><img src="{{ $bankInfo['image'] }}" alt="icon" class="bank-img">{{ $bankInfo['name'] }}</dt>
            <dd>{{ $bankInfo['crad_number_web'] }}</dd>
            <dd>{{ $real_name }}</dd>
        </dl>
        @endforeach
    </div>
    <?php endif; ?>
    <dl class="mybankcard-tip">
        <dt>温馨提示</dt>
        <dd>•为了您的资金安全，仅支持使用一张快捷充值卡进行充值，绑定后只能通过快捷充值卡进行提现。</dd>
        <dd>•首次充值不限金额</dd>
        <dd>•登录九斗鱼官网（www.9douyu.com）可使用更多银行卡进行充值。</dd>
        <dd>•更换绑定银行卡请联系九斗鱼客服。</dd>
    </dl>

</article>
</block>
@endsection



