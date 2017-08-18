@extends('pc.common.layout')
@section('title','提现成功-九斗鱼,心安而有余')
@section('csspage')

@endsection
@section('content')
  <div class="m-myuser">
    @include('pc.common.leftMenu')
    <div class="m-content grayborder">
        <div class="m-pagetitle"><p class="fl">提现</p></div>
        <div class="t-r-showbox hidden">
            <div class="t-v-bj">
                <p>您申请的提现已成功!</p>
            </div>
            <table class="t-v-table">
                <thread>
                <tr>
                    <td>姓名</td>
                    <td>银行卡号</td>
                    <td>到账金额</td>
                    <td>预计到账时间</td>
                </tr>
                </thread>
                <tbody>
                <tr>
                    <td>@if(!empty($orderInfo['real_name'])) {{$orderInfo['real_name']}}@endif</td>
                    <td>@if(!empty($orderInfo['card_number'])){{$orderInfo['card_number']}}@endif</td>
                    <td>@if(!empty($orderInfo['cash'])){{$orderInfo['cash']}}元@else 0.00元@endif</td>
                    <td>下一个工作日24点前</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
        <div class="clearfix"></div>
  </div>
@endsection