@extends('wap.common.wapBase')

@section('title','买入')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/progress.css')}}">
@endsection

@section('content')
    <article>
        <div class="ln-invest">
            <p>借款利率：{{ $project["profit_percentage"] }}%</p>
            <p>剩余可投：{{ number_format($project["left_amount"],0) }}元</p>
            <p>账户余额：{{ number_format($balance,2) }}元</p>
        </div>
        <form action="/invest/project/doInvest" method="post" id="investConfirm">
            <input type="hidden" name="project_id" value="{{ $project["id"] }}">
            <input type="hidden" id="leftAmount" name="left_amount" value="{{ $project["left_amount"] }}">
            <input type="hidden" id="refund_type" name="refund_type" value="{{ $project["refund_type"] }}" />
            <input type="hidden" id="profit_rate" name="profit_rate" value="{{ $project["profit_percentage"] }}"/>
            <input type="hidden" id="invest_time" name="invest_time"  value="{{ $project["format_invest_time"] }}"/>
            <input type="hidden" id="balance" name="balance" value="{{ $balance }}">
            <input type="hidden" id="investMin" name="investMin" value="{{ $investMinCash }}" />
            <input type="hidden" id="publish_at" value="{{ $project["publish_at"] }}" />
            <input type="hidden" id="product_line" value="{{ $project["product_line"] }}" />
            <input type="hidden" id="project_type" value="{{ $project["type"] }}" />
            <input type="hidden" id="end_at" value="{{ $project["end_at"] }}" />
            <input type="hidden" name="_token" value="{{ csrf_token() }}"  >
            <section class="wap2-input-group ln-mb20px">
                <div class="wap2-input-box2">
                    <p class="fr"><input type="text" placeholder="请输入出借金额" name="cash" class="wap2-input-1 mr3 m-input"> 元</p>
                    <p>出借金额</p>
                </div>
            </section>
            <p class="ln2-2" id="invest-gains">收益 <span>0.00元</span>（今日到账）</p>
            <p class="ln-invest-1">{{ $investMinCash }}元起投，整数倍增加，余额不足？<a href="/pay/index">立即充值</a></p>

            @if ( Session::has('msg') )
                <section class="wap2-tip error">
                    <p id="invest-sum">{{ Session::get('msg') }}</p>
                </section>
            @endif

            <section class="wap2-btn-wrap">
                <input type="button" class="wap2-btn wap2-btn-blue disabled" id="subInvestProject" value="请输入买入金额">
            </section>
            <!--/<p class="ln-invest-2">投资视为同意<a href="{:C('WEB_URL_HTTPS')}/agreement/preInterest.pdf">《九斗鱼闪电付息投资协议》</a></p>-->
            <!-- 交易密码弹层开始 -->
            <section class="wap2-pop" style="display:none">
                <div class="wap2-pop-mask"></div>
                <div class="wap2-pop-main">
                    <div class="wap2-pop-tpw-title">
                        <ins>支付金额</ins>
                        ¥ <span></span>
                    </div>
                    <div class="wap2-pop-tpw-box clearfix">
                        <input type="password" name="trade_password" placeholder="请输入交易密码" class="wap2-input-2 mb1">
                        <input type="reset" value="取消" class="wap2-btn wap2-btn-half fl wap2-btn-blue cancel">
                        <input type="button" id="sub" value="确定" class="wap2-btn wap2-btn-half fr">
                    </div>
                </div>
            </section>
            <!-- 交易密码弹层结束 -->
        </form>
    </article>

@endsection

@section('jsScript')
    <script src="{{assetUrlByCdn('/static/js/jquery.plugin.js')}}"></script>
    <script src="{{assetUrlByCdn('/static/js/principalInterest.js')}}"></script>
    <script src="{{assetUrlByCdn('/static/js/dateDiff.js')}}"></script>
    <script src="{{assetUrlByCdn('/static/weixin/js/mobileForm.js')}}"></script>
    <script type="text/javascript">
        (function($){
            $(document).ready(function(){
                $('#subInvestProject').click(function(){
                    var recharge = $(this).val();
                    if( recharge == '账户余额不足，请充值' ){
                        window.location.href='/pay/index';
                    }
                    var cashObj = $("input[name=cash]");
                    var cash = $.trim(cashObj.val());

                    if(cash == '') {
                        cashObj.attr("error", true);
                        $("#subInvestProject").val('请输入出借金额');
                        return false;
                    }

                    if(cashObj.attr('error')=='false'){
                        //减红包金额
                        $('.wap2-pop').show();
                        $('.wap2-pop-tpw-title span').html(cash);
                    }
                });
                $('.cancel').click(function(){
                    $('.wap2-pop').hide();
                });
                $("#sub").click(function() {
                    var password = $("input[name=trade_password]");
                    var passwordV = $.trim(password.val());
                    if(passwordV==''){
                        password.attr("placeholder", "请输入交易密码");
                        return false;
                    }
                    $.ajax({
                        url:'/password/ajaxCheckTradePassword',
                        type:'POST',
                        data:{trading_password:passwordV},
                        dataType:'json',
                        async: false,  //同步发送请求
                        success:function(result){
                            if(result.status == false) {
                                password.val('');
                                password.attr("placeholder", result.msg);
                                return false;
                            }else{
                                $('#investConfirm').submit();
                            }
                        }
                    });
                });
            });
        })(jQuery);
    </script>
    @if ( Session::has('message') )
        <script>
            $(document).ready(function(){
                $(this).mobileTip("{{ Session::get('message') }}");
            });
        </script>
    @endif
@endsection
