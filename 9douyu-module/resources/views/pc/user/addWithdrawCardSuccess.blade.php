@extends('pc.common.layout')

@section('title', '添加提现银行卡成功')

@section('content')

    <div class="m-myuser">
        <!-- account begins -->

        @include('pc.common.leftMenu')

        <div class="m-content  grayborder">
            <div class="m-pagetitle"><p class="fl">验证银行卡</p></div>
            <div class="t-r-showbox hidden">
                <div class="m-process pr">
                    <p class="m-addinfor"><i class="m-addbefore m-grayst"></i><span class="m-grayco">填写银行卡信息</span></p>
                    <p class="m-line"></p>
                    <p class="m-success"><i class="m-addbefore m-bluest"></i><span class="m-blueco">成功添加银行卡</span></p>
                </div>


                <div class="t-bank-1">
                    <p class="t-bank-2">银行卡验证成功～</p>
                </div>

                <a href="/pay/withdraw" class="btn btn-blue btn-large t-bank-blue">立即提现</a>
            </div>
        </div>

        <!-- account ends -->
        <div class="clearfix"></div>
    </div>

@endsection