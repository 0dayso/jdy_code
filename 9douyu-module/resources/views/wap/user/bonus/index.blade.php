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
                <a class="blue-title-bj  fl w-28" type="unused" page="1" href="/bonus/index">未使用</a>
                <a class="blue-title-bj gray-title-bj2 fr w-28" type="used" page="1" href="/bonus/unused">已使用</a>
            </div>
        </section>
        <section id="Bonus_lists">
            @if($ableBonus)
                @foreach($ableBonus as $bonus)
                    @if($bonus['bonus_info']['type'] == \App\Http\Dbs\Bonus\BonusDb::TYPE_CASH)
                        <div class="wap2-coupon-box wap2-coupon-bonus" utype="unused" >
                            <div class="wap2-coupon-wave"></div>
                            <div class="wap2-coupon-title">
                                <p><strong>{{ $bonus['bonus_info']['name'] }}</strong><span>{{ $bonus['bonus_info']['money'] }}元红包</span></p>
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
                            <div class="wap2-coupon-num">
                                <span>¥</span>
                                <strong>{{ $bonus['bonus_info']['money'] }}</strong>
                            </div>
                        </div>
                    @else
                        <section class="wap2-coupon-box wap2-coupon-add" utype="unused" >
                            <div class="wap2-coupon-wave"></div>
                            <div class="wap2-coupon-title">
                                <p><strong>{{ $bonus['bonus_info']['name'] }}</strong><span>{{ $bonus['bonus_info']['rate'] }}％加息券</span></p>
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
                            <div class="wap2-coupon-num">
                                <strong>{{ $bonus['bonus_info']['rate'] }}</strong>
                                <span>%</span>
                            </div>
                        </section>
                    @endif
                @endforeach
            @endif
        </section>
    </article>
@endsection