@extends('wap.common.wapBase')

@section('title','我的优惠券')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/index-w3.css')}}">
    <style>
        body{overflow: auto;}
    </style>
@endsection

@section('content')
    <article>
        <section class="w-box-show hidden">
            <div class="w-yh-mlr36px" id="bonus_tab">
                <a class="blue-title-bj gray-title-bj2  fl w-28" type="unused" page="1" href="/bonus/index">未使用</a>
                <a class="blue-title-bj  fr w-28" type="used" page="1" href="/bonus/unused">已使用</a>
            </div>
        </section>
        <section id="Bonus_lists">
            @if(!empty($unableBonus))
                @foreach($unableBonus as $bonus)
                    <section class="wap2-coupon-box wap2-coupon-done">
                        <div class="wap2-coupon-wave"></div>
                        <div class="wap2-coupon-title">
                            @if($bonus['bonus_info']['type'] == \App\Http\Dbs\Bonus\BonusDb::TYPE_CASH)
                                <p><strong>{{ $bonus['bonus_info']['name'] }}</strong><span>{{ $bonus['bonus_info']['money'] }}元红包</span></p>
                            @else
                                <p><strong>{{ $bonus['bonus_info']['name'] }}</strong><span>{{ $bonus['bonus_info']['rate'] }}％加息券</span></p>
                            @endif

                            <p>有效期至:{{ $bonus['end_time'] }} </p>
                        </div>
                        <div class="wap2-coupon-txt">
                            <p>{{ $bonus['bonus_info']['using_desc'] }}</p>
                            <p>
                                @if($bonus['bonus_info']['project_name'] != '')
                                    {{ $bonus['bonus_info']['project_name'] }}
                                @endif
                            </p>
                        </div>
                        @if($bonus['bonus_info']['type'] == \App\Http\Dbs\Bonus\BonusDb::TYPE_CASH)
                            <div class="wap2-coupon-num">
                                <span>¥</span>
                                <strong>{{ $bonus['bonus_info']['money'] }}</strong>
                            </div>
                        @else
                            <div class="wap2-coupon-num">
                                <strong>{{ $bonus['bonus_info']['rate'] }}</strong>
                                <span>%</span>
                            </div>
                        @endif

                        @if($bonus['bonus_info']['type'] == \App\Http\Dbs\Bonus\BonusDb::TYPE_COUPON_CURRENT)
                            @if($bonus['rate_used_time'] > 0)
                                <span class="wap2-coupon-icon2"></span>
                            @endif
                            @if($bonus['rate_used_time'] == '0000-00-00')
                                <span class="wap2-coupon-icon1"></span>
                            @endif
                        @else
                            @if($bonus['used_time'] > 0)
                                <span class="wap2-coupon-icon2"></span>
                            @endif
                            @if($bonus['used_time'] == '0000-00-00 00:00:00')
                                <span class="wap2-coupon-icon1"></span>
                            @endif
                        @endif
                    </section>
                @endforeach
            @endif
        </section>
@endsection