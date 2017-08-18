@extends('wap.common.wapBase')

@section('title', '九斗鱼合伙人计划')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/partner.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/partner3.css') }}">
@endsection

@section('content')
    <article class="t-partner3-bj">
        <div class="t-partner3-bj1">
            <p>活动时间：{{ $startTimeStr }}至{{ $endTimeStr }}</p>
        </div>
        <div class="t-partner3-bj2">
            <h3>什么是合伙人计划</h3>
            <p>合伙人计划是指您邀请您的新老好友在九斗鱼开始投资成为您的合伙人，您最高能获得年化<span> 2% </span>佣金收益的活动。</p>
        </div>
        <div class="t-partner3-bj3"></div>
        <div class="t-partner3-bj4"></div>
        <div class="t-partner3-bj5"></div>

        <div class="x-partner2-btn-box">
            @if($status == 3)
                @if($isLogin || (!$client))
                    <a href="{{ env('APP_URL_WX') }}/y2015partner/add" class="x-partner2-btn" title="立即领取佣金">立即领取佣金<i></i></a>
                @elseif($client == 'ios')
                    <a href="objc:gotoLogin" class="x-partner2-btn" title="立即领取佣金">立即领取佣金<i></i></a>
                @elseif($client == 'android')
                    <a href="javascript:window.jiudouyu.login()" class="x-partner2-btn" title="立即领取佣金">立即领取佣金<i></i></a>
                @endif
            @endif

            @if ( $status == 2 )<a href="javascript:" class="x-partner2-btn-disable" title="活动已经结束">活动已经结束</a>@endif
            @if ( $status == 1 )<a href="javascript:" class="x-partner2-btn-disable" title="敬请期待">敬请期待</a>@endif
            @if ( $status == 5 )<a href="{{ env('APP_URL_WX') }}/ActivityPartner" class="x-partner2-btn" title="查看佣金">查看佣金<i></i></a>@endif
        </div>

        <div class="t-partner3-bj9"></div>
        <div class="t-partner3-bj6">
            <p class="t-partner3-bj6-1"><span></span>合伙人人数排行榜</p>
            <!-- 合伙人人数排行榜 -->
            <table class="t-partner3-bj6-2">
                <tr>
                    <th width="20%">排名</th>
                    <th width="50%">创始人</th>
                    <th>合伙人数</th>
                </tr>
                @if ( $inviteCountSort != '' )
                    <?php $k=0;?>
                    @foreach ( $inviteCountSort as $data )
                        <tr>
                            <td><div>{{ $k+1 }}</div></td>
                            <td><div>{{ $data["phone"] or '' }}</div></td>
                            <td><div>{{ $data["total"] }}人</div></td>
                        </tr>
                        <?php $k++;?>
                    @endforeach
                @endif
            </table>
        </div>
        <div class="t-partner3-bj6">
            <p class="t-partner3-bj6-1"><span class="t-icon"></span>合伙人投资总额排行榜</p>
            <!-- 合伙人投资总额排行榜 -->
            <table class="t-partner3-bj6-2 t-partner3-bj6-2-1">
                <tr>
                    <th width="16%">排名</th>
                    <th width="45%">创始人</th>
                    <th>合伙人投资额</th>
                </tr>
                @if ( $inviteInvestCountSort != '' )
                    <?php $key=0;?>
                    @foreach ( $inviteInvestCountSort as $data )
                        <tr>
                            <td><div>{{ $key+1 }}</div></td>
                            <td><div>{{ $data["phone"] or '' }}</div></td>
                            <td><div>{{ number_format($data["yesterday_cash"],0) }}元</div></td>
                        </tr>
                        <?php $key++;?>
                    @endforeach
                @endif
            </table>
        </div>
        <p class="t-partner3-bj10">这么多小伙伴都赚钱了，你也赶快行动吧！</p>
        <div class="t-partner3-bj11"></div>


        @if ( $status == 3 )
            @if ( $isLogin || (!$client) )
                <a href="{{ env('APP_URL_WX') }}/y2015partner/add" class="x-partner2-btn" title="立即领取佣金">立即领取佣金<i></i></a>
            @elseif ( $client == 'ios' )
                <a href="objc:gotoLogin" class="x-partner2-btn" title="立即领取佣金">立即领取佣金<i></i></a>
            @elseif ( $client == 'android' )
                <a href="javascript:window.jiudouyu.login()" class="x-partner2-btn" title="立即领取佣金">立即领取佣金<i></i></a>
            @endif
        @endif
        @if ( $status == 2 )<a href="javascript:" class="x-partner2-btn-disable" title="活动已经结束">活动已经结束</a>@endif
        @if ( $status == 1 )<a href="javascript:" class="x-partner2-btn-disable" title="敬请期待">敬请期待</a>@endif
        @if ( $status == 5 )<a href="{{ env('APP_URL_WX') }}/ActivityPartner" class="x-partner2-btn" title="查看佣金">查看佣金<i></i></a>@endif
        <div class="t-partner3-bj8"></div>
        <dl class="t-partner3-bj12">
            <dt>1</dt>
            <dd>活动时间&奖励时间：{{ $startTimeStr }}至{{ $endTimeStr }}；</dd>
            <dt>2</dt>
            <dd>所有九斗鱼注册用户均可加入合伙人计划成为“创始人”，创始人登录后即可查看已邀请用户（即您的合伙人）的数量及投资情况等，活动期间只要您以前所邀请的用户或邀请新用户开始投资，您即可获得佣金收益；</dd>
            <dt>3</dt>
            <dd>您名下所有合伙人的待收本金不同，您享受不同的年化佣金率，最高年化佣金率为2%，具体佣金率规则请点<a href="{{ $url }}">击查看九斗鱼合伙人计划公告；</a></dd>
            <dt>4</dt>
            <dd>创始人需自参加本活动之日起每30天来九斗鱼平台完成相应的任务来激活下一30天领取佣金的资格，每月的激活任务内容可能均不同，以最终展示的内容为准，任务内容如有变动会提前通知；</dd>
            <dt>5</dt>
            <dd>佣金收益无上限，邀请好友越多，投资额越高，佣金收益越大；</dd>
            <dt>6</dt>
            <dd>待收本金是指用户使用自有资金正在投资九省心、九安心、变现宝项目的在投本金，不包含投资零钱计划项目的资金；</dd>
            <dt>7</dt>
            <dd>本次活动的最终解释权归九斗鱼平台所有。</dd>
        </dl>
    </article>
@endsection

@section('jsScript')
    <script>
    </script>
@endsection



