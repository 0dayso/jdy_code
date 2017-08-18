@extends('wap.common.wapBase')

@section('title', '九斗鱼')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/familyAccount.css') }}">
@endsection

@section('content')
    @if(!empty($familyRole))
        <dl class="family-finish">
            <dt></dt>
            <dd>
                <p>家庭账户 <span id="current">{{ $familyRole }}</span> 授权成功！</p>
                <p>开始赚钱吧~</p>
            </dd>
        </dl>
    @endif
    @foreach($family as $vo)
        <section class="family-into">
            <a href="{{ $vo['url'] }}" class="family-into1">
                <div class="family-into1-23">{{ $vo['call_name'] }}</div>
                <div class="family-into2">
                    <p>零钱计划 {{ $vo['currentInvest'] }} 元</p>
                    <p>定期资产 {{ $vo['invest'] }} 元</p>
                </div>
                <span class="family-into-icon"></span>
            </a>
        </section>
    @endforeach
    <p class="family-into3">
        <a href="#" onclick="back();" class="family-into-btn purple">返回客户端</a>
        <a href="/family/forWho" class="family-into-btn"><span>＋</span> 继续添加</a>
    </p>
@endsection

@section('jsScript')
    <script>
        var client = '{{ $client }}';
        function back(){
            if(client=='ios'){
                window.location.href="objc:gotoAccount";
            }else{
                window.jiudouyu.gotoAccount();
            }
        }
    </script>
@endsection

