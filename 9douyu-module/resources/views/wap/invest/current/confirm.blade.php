@extends('wap.common.wapBase')
@section('title', '加入零钱计划')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <form action="/invest/current/doInvest" method="post" id="investHForm">
        <input type="hidden" name="currentRate" value="{{$rateInfo['rate']}}" />
        <input type="hidden" name="leftAmount" value="{{$freeAmount}}" />
        <input type="hidden" name="userBalance" value="{{$balance}}" />
        <input type="hidden" name="addRate" value="{{ $addRate }}"/>
        <input type="hidden" name="investMax" id="investMax" value="{{ $investMax }}"/>
        <input type="hidden" name="investMin" id="investMin" value="1"/>
        <article>
            @if($addRate > 0)
            <div class="t-current1-3 w-q-mt">加息券生效中：享{{ $rateInfo['rate'] }}%＋<span id="rate">{{ $addRate }}</span>%零钱计划利率 </div>
            @endif
            <section class="wap2-input-group ">
                <div class="wap2-input-box2 bbd3">
                    <p class="fr" ><span>{{ number_format($freeAmount,2) }}</span> 元</p>
                    <p>可投金额</p>
                </div>
                <div class="wap2-input-box2">
                    <p class="fr"><span class="blue">{{ $balance }}</span> 元</p>
                    <p>账户余额</p>
                </div>
            </section>
            <section class="wap2-input-group">

                @if($bonus_list)
                    <div class="wap2-input-box2 bbd3">
                        <p class="fr">
                            <select class="t-current1-5" name="bonus_id" id="bonus_id">
                                <option value="0" data-value="0">暂不使用</option>
                                @foreach( $bonus_list as $r )
                                    <option value="{{$r['id']}}" data-value="{{$r['rate']}}">{{$r['rate']}}%-连续加息{{$r['current_day']}}天&nbsp;({{$r["use_end_time"]}}前可用)</option>
                                    <!--<option value="{$r.id}" data-value="{$r.rate}">{$r.rate}%-加息{$r.period}天-{$r.use_end_time}前</option>-->
                                 @endforeach
                            </select>
                        </p>
                        <p>优惠券</p>
                    </div>
                @endif
                <div class="wap2-input-box2">
                    <p class="fr pr"><input type="text" placeholder="请输入出借金额" name="cash" class="wap2-input-cash" id="wap2-input-cash" autocomplete="off">元</p>
                    <p>出借金额</p>
                </div>
            </section>
            <p class="t-current1-6 mb8px"><span>●</span>1元起投，余额不够？<a href="/pay/index" class="blue">立即充值</a></p>
            <p class="t-current1-6 mb1"><span>●</span>加息期间，零钱计划账号的全部资金均享受加息后收益</p>
            <section class="wap2-tip error">
                <p class="project-tips"> @if(Session::has('errors')) {{ Session::get('errors') }} @endif</p>
                <p class="m-tips">
                </p>
            </section>
            <section class="wap2-btn-wrap">
                <input type="button" class="wap2-btn next" value="下一步">
            </section>
        </article>
        <!-- 交易密码弹层开始 -->
        <section class="wap2-pop" style="display:none">
            <div class="wap2-pop-mask"></div>
            <div class="wap2-pop-main">
                <div class="wap2-pop-tpw-title">
                    <ins>支付金额</ins>
                    ¥ <span></span>
                </div>
                <div class="wap2-pop-tpw-box clearfix">

                    @if($showStatus['password_checked'] == 'on')
                        <input type="password" name="trading_password"  placeholder="请输入交易密码" class="wap2-input-2 mb1">
                    @else
                        <input type="password" name="trading_password"  placeholder="请设置交易密码" class="wap2-input-2 mb1">
                        <p style="text-align: center;">6-16位数字及字母组合、与登录密码不同</p>
                    @endif

                    <input type="reset" value="取消" class="wap2-btn wap2-btn-half fl wap2-btn-blue cancel">
                    <input type="button" id="sub" value="确定" class="wap2-btn wap2-btn-half fr">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                </div>
            </div>
        </section>
        <!-- 交易密码弹层结束 -->
    </form>
@endsection

@section('jsScript')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script src="{{ assetUrlByCdn('/static/weixin/js/investCurrent.js') }}"></script>
    @include('wap.common.js')
@endsection