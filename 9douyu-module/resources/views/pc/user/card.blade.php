@extends('pc.common.layout')

@section('title', '银行卡管理')

@section('content')

    <div class="m-myuser">
        <!-- account begins -->

        @include('pc.common.leftMenu')

        <div class="m-content mb30">
            <!--选项卡1导航-->
            <ul class="m-tabnav1">
                <li class="ml-1"><a href="/user/fundhistory">资金明细</a></li>
                <li class="m-addstyle"><a href="/user/bankcard">银行卡管理</a></li>
            </ul>
            <div class="m-showbox pt40">
                <!--银行卡管理-->
                <div>
                    <!--
                    <div class="system-message form-tips text-" style="color: red;"></div>
                    -->
                    <ul class="m-tocashbox clearfix">
                        @if(!empty($cards['list'][0]))
                            @foreach($cards['list'] as $card)
                                <li>
                                    <p class="m-tobank">@if( isset($card['bank_id']) )<img src="{{assetUrlByCdn('/static/images/bank-img/'. $card['bank_id'].'.gif')}}">@endif 提现银行卡</p>
                                    <div class="m-tocashcon pr">
                                        <p><label>卡号</label>@if( isset($card['crad_number_web']) ){{ $card['crad_number_web'] }}@endif</p>
                                        <p><label>户名</label>@if( isset($cards['user_name']) ){{ $cards['user_name'] }}@endif</p>
                                        <p><label>开户行</label>-</p>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <li class="pr m-blueborder">
                                <a href="/user/bankcard/add"><img src="{{assetUrlByCdn('/static/images/new/m-addcard.png')}}" class="addpic"/></a>
                                <p class="pa m-bindcard">绑定提现银行卡</p>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>

        <!-- account ends -->
        <div class="clearfix"></div>
    </div>

@endsection
