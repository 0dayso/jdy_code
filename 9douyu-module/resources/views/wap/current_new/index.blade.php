@extends('wap.common.wapBase')
@section('title', '加入零钱计划')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <form action="/current/new/invest" method="post" name="form1" id="investHForm">
        <article>
            @if(!empty($detail))
                <div class="t-current1-3 w-q-mt">{{ $detail['name'] }}</div>
                <section class="wap2-input-group ">
                    <div class="wap2-input-box2 ">
                        <p class="fr" ><span>{{ number_format($detail['rate'],2) }}</span> %</p>
                        <p>当日利率</p>
                    </div>
                    <div class="wap2-input-box2 ">
                        <p class="fr" ><span>{{ number_format($detail['left_amount'],2) }}</span> 元</p>
                        <p>剩余可投</p>
                    </div>
                    <div class="wap2-input-box2">
                        <p class="fr"><span class="blue">{{ $user_balance }}</span> 元</p>
                        <p>账户余额</p>
                    </div>
                    <div class="wap2-input-box2 bbd3">
                        <p class="fr"><span class="blue">{{ number_format($user_current_new_amount,2) }}</span> 元</p>
                        <p>新版活期账户@if($user_current_new_amount > 0)<a href="/current/creditList">(点击查看持有债权详情)</a>@endif</p>
                    </div>
                    <div class="wap2-input-box2 ">
                        <p class="fr pr"><input type="text" placeholder="请输入出借金额" name="cash" class="wap2-input-cash" id="wap2-input-cash" autocomplete="off">元</p>
                        <p>出借金额</p>
                    </div>
                    <div class="wap2-input-box2 ">
                        <p class="fr pr red">此账户最高可持有金额为{{ $max_invest }}元</p>
                        <p class="red">温馨提示:</p>
                    </div>
                </section>
                <section class="wap2-tip error">
                    <p class="project-tips"> @if(Session::has('errors')) {{ Session::get('errors') }} @endif</p>
                    <p class="m-tips">
                    </p>
                </section>
                <section  class="w-bottom">
                    <div class="w-bottom-btn">
                        <input type="hidden" name="type" id="type" value="">
                        {{--<input type="hidden" name="project_id" value="{{ $detail['id'] }}">--}}
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <a href="javascript:void(0);" id="investOut" class="wap2-btn wap2-btn-half fl wap2-btn-blue" data-value="1">申请转出</a>
                        <a href="javascript:void(0);" id="invest"  class="wap2-btn wap2-btn-half fr" data-value="2">立即转入</a>
                    </div>
                </section>
            @endif
        </article>
    </form>
@endsection

@section('jsScript')
    <script>


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function(){

            $('#invest').click(function(){
                var type = $(this).attr('data-value');
                $('#type').val(type);
                $('#investHForm').submit();
            });

            $('#investOut').click(function(){
                var type = $(this).attr('data-value');
                $('#type').val(type);
                $('#investHForm').submit();
            });
        });

    </script>
    @include('wap.common.js')
@endsection