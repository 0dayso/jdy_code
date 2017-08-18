@extends('wap.common.wapBase')

@section('title', '用户提现预览')

@section('keywords', "{{env('META_KEYWORD')}}")

@section('description', "{{env('META_DESCRIPTION')}}")

@section('css')

@endsection
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <article class="mt1">

        <section class="wap2-box box-pad">
            <table class="wap2-withdraw-info">
                <tr>
                    <th colspan="3">提现申请</th>
                </tr>
                <tr>
                    <td width="4%">•</td>
                    <td width="48%">姓名</td>
                    <td>{{$real_name}}</td>
                </tr>
                <tr>
                    <td>•</td>
                    <td>银行卡号</td>
                    <td>{{$card_no}}</td>
                </tr>
                <tr>
                    <td>•</td>
                    <td>提现金额</td>
                    <td>{{$cash}}元</td>
                </tr>
                <tr>
                    <td>•</td>
                    <td>剩余免费提现次数</td>
                    <td>{{$last_withdraw_num}}次</td>
                </tr>
                <tr>
                    <td>•</td>
                    <td>提现手续费</td>
                    <td>{{$commission}}元</td>
                </tr>
                <tr>
                    <td>•</td>
                    <td>实际到账金额</td>
                    <td>{{$real_cash}}元</td>
                </tr>

            </table>
        </section>


        <section class="wap2-btn-wrap">
            <input type="submit" value="立即提现" class="wap2-btn" id="withdraw">
        </section>


        <!-- 交易密码弹层开始 -->
        <section class="wap2-pop" style="display:none;">
            <div class="wap2-pop-mask"></div>
            <div class="wap2-pop-main">
                <div class="wap2-pop-tpw-title">
                    <ins>提现金额</ins>
                    <span>¥ {{$cash}}</span>
                </div>
                <div class="wap2-pop-tpw-box clearfix">
                    <form action="{{ URL('/withdraw/submit') }}" method="post" id="doWithdraw">
                        <input name="real_name" type="hidden" value="{{$real_name}}" class="form-control" readonly>
                        <input type="hidden" name="card_no" value="{{$card_no}}">
                        <input type="hidden" name="bank_id" value="{{$bank_id}}">
                        <input name="balance" type="hidden" value="{{ $balance }}" class="form-control" readonly>
                        <input type="hidden" name="cash" value="{{$cash}}">
                        <input name="commission" type="hidden" value="{{ $commission }}" class="form-control" readonly>
                        <input name="real_cash" type="hidden" value="{{ $real_cash }}" class="form-control" readonly>
                        <input type="hidden" name="from" value="wap">
                        <input name="version" type="hidden" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="password" placeholder="请输入交易密码" class="wap2-input-2 mb1" name="trading_password">
                        {{--<if condition="$checktrapwd eq 'off'">
                            <p style="text-align: center;">6-16位数字及字母组合,与登录密码不同</p>
                        </if>--}}
                        <input type="reset" value="取消" class="wap2-btn wap2-btn-half fl wap2-btn-blue" id="resetWithdraw">
                        <input type="submit" value="确定" class="wap2-btn wap2-btn-half fr">
                    </form>
                </div>
                <section class="wap2-tip error">
                    <p>
                        @if(Session::has('errors'))
                            {{  Session::get('errors') }}
                        @endif
                    </p>
                </section>
            </div>
        </section>

    </article>
@endsection
@section('jsScript')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        (function($){
            $(document).ready(function(){
                @if(Session::has('errors'))
                $('.wap2-pop').show();
                @endif
                $('#withdraw').click(function(){
                    $('.wap2-pop').show();
                });

                $('#resetWithdraw').click(function(){
                    $('.wap2-pop').hide();
                });

                $("#doWithdraw").submit(function() {

                    if(!$(this).data("formSubmitLock")) {
                        var trading_password = $.trim($("input[name=trading_password]").val());
                        var cash = $.trim($("input[name=cash]").val());
                        var wap2_tip = $('.wap2-tip p');
                        if($(this).data("clickLock")) return false;

                        $.ajax({
                            url: '/user/checkTradePassword',
                            dataType: 'json',
                            type: 'post',
                            data: {'trading_password': trading_password, 'cash': cash},
                            success: function (data) {
                                if (data.code != 200) {
                                    wap2_tip.html(data.msg);
                                } else {
                                    $("#doWithdraw").data("formSubmitLock", true).submit();
                                }
                                $(this).data("clickLock", null);
                            },
                            error: function (msg) {
                                $(this).data("clickLock", null);
                            }
                        });
                        $(this).data("clickLock", null);

                        return false;
                    }
                });
            });
        })(jQuery);
    </script>
@endsection