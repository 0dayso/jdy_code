@extends('wap.common.wapBase')

@section('title','出借确认')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/expercash.css')}}">
@endsection

@section('content')
<article>
    <div class="ln-invest">
        <p>借款利率：{{ $project['profit_percentage'] }}%</p>
        <p>账户余额：{{ number_format($balance,2) }}元</p>
        <p>剩余可投：{{ number_format($project['left_amount'],2) }}元</p>
    </div>
    <form action="/invest/project/doInvest" method="post" id="investConfirm">
        <input type="hidden" name="project_id" value="{{ $project['id'] }}" />

        <section class="wap2-input-group ln-mb20px">
            <div class="wap2-input-box2">
                <dl class="s8-input-group">
                    <dt>出借金额</dt>
                    <dd>
                        <input type="text" placeholder="93.7%的人转入超过千元" name="cash" autocomplete="off" class="wap2-input-1 s8-input-cash m-input">
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
                        <input type="hidden" id="invest_time" value="{{ $project['invest_time'] }}">

                    </dd>
                </dl>
                <span class="s8-input-unit">元</span>
            </div>
        </section>
        <div class="s8-invest-num" id="invest-num-s8" ></div>

        <section class="wap2-input-group">
            @if(!empty($bonus))
            <div class="wap2-input-box2">
                <dl class="s8-input-group">
                    <dt>优惠券</dt>
                    <dd>
                        <select name="bonus_id" id="bonusId" class="wap2-select">
                            <option value="0">有{{ $bonusNum }}个优惠券可用</option>
                            @foreach($bonus as $v)
                            <option value="{{ $v['user_bonus_id'] }}" data-money="{{ $v['cash'] }}" data-rate="{{ $v['rate'] }}" data-minAmount="{{ $v['min'] }}">{{ $v['name'] }} {{ $v['using_range'] }} 有效期至{{ $v['end_time'] }}</option>
                            @endforeach
                        </select>
                    </dd>
                </dl>
            </div>
            @endif
        </section>
        <div class="s8-invest-num" id="s8-invest-num"></div>
        <section class="wap2-tip error">
            <p id="invest-sum">{{ $msg }}</p>
        </section>
        <section class="wap2-btn-wrap">
            <input type="button" class="wap2-btn wap2-btn-blue next" id="subInvestProject" value="立即出借">
        </section>
        <p class="ln-invest-1">100元起投，余额不够？<a href="/pay/index">立即充值</a></p>

        <!-- 交易密码弹层开始 -->
        <section class="wap2-pop" style="display:none">
            <div class="wap2-pop-mask"></div>
            <div class="wap2-pop-main">
                <div class="wap2-pop-tpw-title">
                    <ins>支付金额</ins>
                    ¥ <span></span>
                </div>
                <div class="wap2-pop-tpw-box clearfix">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
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
<script src=""></script>
<script src="{{assetUrlByCdn('/static/js/interest/interest.js')}}"></script>

<script src="{{assetUrlByCdn('/static/js/jquery.plugin.js')}}"></script>
{{--<script src="/static/js/principalInterest.js"></script>--}}
<script src="{{assetUrlByCdn('/static/js/dateDiff.js')}}"></script>
<script src="{{assetUrlByCdn('/static/weixin/js/mobileForm.js')}}"></script>
<script type="text/javascript">
    (function($){
        $(document).ready(function(){
            $('.next').click(function(){

                checkCash();

                var cashObj = $("input[name=cash]");
                var cash = $.trim(cashObj.val());

                if(cashObj.attr('error')=='false'){
                    $('.wap2-pop').show();
                    $('.wap2-pop-tpw-title span').html(cash);
                }


            });

//            $("input[name='cash']").keyup(function(){
//
//                checkCash();
//            });

            $('.cancel').click(function(){
                $("#subInvestProject").addClass('disabled').val('立即出借');
                $('.wap2-pop').hide();
            });

            $("#sub").click(function() {
                var password = $("input[name=trade_password]");
                var passwordV = $.trim(password.val());
                if(passwordV==''){
                    password.attr("placeholder", "请输入交易密码");
                    return false;
                }
                $('#investConfirm').submit();
            });

            function checkCash(){

                var cashObj = $("input[name=cash]");
                var cash = $.trim(cashObj.val());

                cashObj.attr("error", false);

                if(cash == '') {
                    cashObj.attr("error", true);
                    $("#subInvestProject").addClass('disabled').val('请输入出借金额');
                    return false;
                }
                if(cashObj.val()<parseInt($('#bonusId').find("option:selected").attr('data-minAmount'))){
                    cashObj.attr("error", true);
                    $("#subInvestProject").addClass('disabled').val('红包使用条件：出借金额必须'+$('#bonusId').find("option:selected").attr('data-minAmount')+'元');
                    return false;
                }

                /*if(cashObj.attr('error') == 'false'){
                    $('.wap2-pop').show();
                    $('.wap2-pop-tpw-title span').html(cash);
                }*/
                $("#subInvestProject").removeClass('disabled').val('立即出借');
            }
        });
    })(jQuery);

</script>

@endsection
