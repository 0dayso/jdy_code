<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>充值支付－九斗鱼</title>
    <link href="{{ assetUrlByCdn('/static/css/recharge.css')}}" rel="stylesheet">
</head>
<body>
<div class="wrap">
    <div class="logo">
        <a href="http://www.9douyu.com"><img src="{{ assetUrlByCdn('/static/images/new/x-logo-small.png') }}"></a>
    </div>
    <div class="box">
        <div class="box-title">
            <div class="box-info">
                <p>收款人： <span>耀盛汇融投资管理有限公司</span></p>
            </div>
            <div class="box-sum">
                <p>充值金额：<span>{{ $cash }} 元</p>
            </div>
        </div>
        <div class="main-title">借记卡付款</div>
        <div class="main">
            <form action="{{ URL('/pay/qdbSubmit') }}" method="post" id="rechargeSub">
                <div class="main-group">
                    <label>银行卡</label><input type="text" class="main-group-input" name="card_no" value="{{ $cardNo }}" readonly>
                </div>
                <div class="main-group">
                    <label>姓名</label><input type="text" class="main-group-input" name="name" value="{{ $realName }}" readonly>
                </div>
                <div class="main-group">
                    <label>身份证号码</label><input type="text" class="main-group-input" name="id_card" value="{{ $identityCard }}" readonly>
                </div>
                <div class="main-group">
                    <label>手机号</label><input type="text" class="main-group-input" placeholder="请输入银行预留手机号" value="{{ $phone }}" name="phone">
                </div>
                <div class="main-group">
                    <label>短信验证码</label>
                    <input type="text" class="main-group-input input-small" placeholder="请输入短信验证码" value="" name="code">
                    <input type="button" value="点击获取" class="main-code" id="code">
                </div>
                <div class="main-btn-box"><input id="agree" checked="checked"  type="checkbox" value="1"><a id="view">同意九斗鱼充值服务协议</a></div>
                <div class="main-btn-box">
                    <input type="hidden" name="from" value="pc">
                    <input name="cash" type="hidden" value="{{ $cash }}"/>
                    <input type="hidden" name="order_id" value="{{ $orderId }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="system-message form-tips main-msg" id="form-tips"></div>
                    <input id="paySub" type="button" value="确认付款" class="main-btn">
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{ assetUrlByCdn('/static/js/jquery-1.9.1.min.js') }}"></script>
<script src="{{ assetUrlByCdn('/static/js/jquery.cookie.js') }}"></script>
<script src="{{ assetUrlByCdn('/static/js/recharge.js') }}"></script>
<script>
    $.sendCode('qdb');
    $.paySub('qdb');
</script>
</body>
</html>
