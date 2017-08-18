@extends('wap.common.wapBase')

@section('title', '提现说明')

@section('keywords', "{{env('META_KEYWORD')}}")

@section('description', "{{env('META_DESCRIPTION')}}")

@section('css')
<style type="text/css">
    body{ background: #f2f2f2;}
    .w-intro{ background: #fff; padding: 0.8rem 0.6rem 0.9rem; font-size: 0.75rem; color: #58bffc; margin-bottom: 0.5rem;}
    .w-intro h4{ padding-bottom: 0.5rem;}
    .w-intro span{ display: inline-block; width: 0.95rem; height: 0.95rem; background: #54bff6;font-size: 0.75rem; text-align: center;line-height: 0.98rem;font-weight: bold; color: #fff; border-radius: 100%; font-family: "Arial"; margin-right: 0.7rem;vertical-align: top; }
    .w-intro p{ font-size: 0.6rem; color: #999999; padding-left: 1.65rem; line-height: 0.9rem;}
    .w-time{ display: table; background: #fff; min-height: 3.2rem; margin-top: 0.25rem;}
    .w-time dt{ width: 5.675rem;background: #a5daff; color: #fff; font-size: 0.6rem; display: table-cell; vertical-align: middle;}
    .w-time dt p{ text-align: center;}
    .w-time dd{display: table-cell; vertical-align: middle; padding: 0.75rem 0 0.75rem 0;}
    .w-time dd p{ padding-left:0.6rem; padding-right: 0.6rem; color: #999999; font-size: 0.6rem; line-height: 0.85rem;}
    .w-mg{ margin-top: 0.5rem; }
</style>
@endsection

@section('content')
    <div class="w-intro">
        <h4><span>1</span>最低可以提现多少金额？</h4>
        <p>单笔提现金额 100元起</p>
    </div>
    <div class="w-intro">
        <h4><span>2</span>提现有手续费吗？</h4>
        <p>每位用户每自然月有4次免费提现机会，超过4次以后的每笔提现将收5元手续费</p>
    </div>
     <div class="w-intro">
        <h4><span>3</span>提现金额有上限吗？</h4>
        <p>九斗鱼提现全部由第三方支付公司“网银在线”代付，单笔代付不超过5万，当提现金额超过5万，将分为多笔到账，请知晓。</p>
    </div>
    <div class="w-intro">
        <h4><span>4</span>提现什么时候可以到账？</h4>
        <!--<p>工作日T+0到账，法定节日和周末顺延至工作日 具体如下：</p>-->
        <p>工作日T+1到账，法定节日和周末顺延至工作日 具体如下：</p>

    </div>
    <!--<dl class="w-time">
        <dt>
            <p>工作日</p>
        </dt>
        <dd>
            <p>下午3点之前的提现申请,预计可在当天到账。</p>
            <p>下午3点之后的提现申请，预计可在次日到账。</p>
        </dd>
    </dl>-->
    <dl class="w-time">
        <dt>
            <p>周五及节假日</p>
        </dt>
        <dd>
            <!--<p>周五下午3点后、法定节假日期间，用户申请的提现，九斗鱼将在假期后的第一个工作日进行处理，不便之处，敬请谅解！</p>-->
            <p>周五、法定节假日期间，用户申请的提现，九斗鱼将在假期后的第一个工作日进行处理，不便之处，敬请谅解！</p>

        </dd>
    </dl>
     <div class="w-intro w-mg">
        <h4><span>5</span>可以绑定信用卡进行提现吗？</h4>
        <p>提现时，只支持提现到借记卡，不能提现到信用卡。</p>
    </div>
@endsection