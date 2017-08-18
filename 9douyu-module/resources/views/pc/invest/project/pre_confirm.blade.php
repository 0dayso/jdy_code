@extends('pc.common.layout')

@section('title','确认出借')

@section('content')
    <div class="web-invest-confirm">
        <form action="/invest/project/confirm" method="post" id="investConfirmPre" >
            <div class="web-invest-confirm-title">
                <i></i>
                <span>确认出借</span>
            </div>
            <ul class="web-invest-confirm-info">
                <li class="t-w">
                    <span>投资项目 :</span>
                    <em class="t-w-1">{{ $project['product_line_note'] }}{{ $project["invest_time_note"] }}</em>
                </li>

                <li class="t-w1">
                    <span>可投金额 :</span>
                    <em class="t-w-2"><em class="t-w-3">{{ number_format($project["left_amount"],0) }}</em> 元</em>
                </li>
            </ul>
            <div class="web-invest-confirm-funds">
                <p><span>账户余额 :</span><strong>{{ number_format($balance,2) }}</strong>元 <a href="/recharge/index" class="t-chong">充值</a></p>
                <!--<p><span>账户余额 :</span> <a href="" class="t-chong1">登录</a><em class="t-chong2">后可查看</em></p>-->

                <dl class="web-invest-confirm-group clearfix t-pnone">
                    <dt class="t-bno">出借金额 :</dt>
                    <dd class="t-w" style="width: 600px;">
                        <p class="t-bn">
                            <input type="text" class="form-input w190px f14" name="cash" id="cash" autocomplete="off" disableautocomplete />
                            <input type="hidden" name="project_id" value="{{ $project["id"] }}">
                            <input type="hidden" id="leftAmount" name="left_amount" value="{{ $project["left_amount"] }}">
                            <input type="hidden" name="refund_type" id="refund_type" value="{{ $project["refund_type"] }}" />
                            <input type="hidden" id="profit_rate" name="profit_rate" value="{{ $project["profit_percentage"] }}"/>
                            <input type="hidden" name="invest_time" value="{{ $project["format_invest_time"] }}"/>
                            <input type="hidden" id="balance" name="balance" value="{{ $balance }}">
                            <input type="hidden" name="investMin" id="investMin" value="{{ $investMinCash }}" />
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <span class="project-tips" style="color: #ff5353; font-size: 12px; padding-left: 31px;"></span>
                        </p>
                    </dd>
                </dl>
                <p class="tt-invest t-bn">＊投资收益 <span id="profit">0.00</span>元</p>
                <p style="margin-top: 20px;"><span>余额支付 :</span><strong id="payMoney">0.00</strong>元</p>
            </div>
            <div class="web-invest-confirm-wrap">
                <p class="t-prompt tl t-mt-10px">温馨提示：网贷有风险，投资需谨慎。</p>
                <p><label><input type="checkbox" id="aggreement" checked="checked">我已阅读并同意  </label>
                    {{--<a class="blue" onclick="window.open('/agreement/preInterest.pdf')"  style="cursor:pointer">《九斗鱼闪电付息投资协议》</a>--}}
                </p>
                <dl class="web-invest-confirm-group clearfix">
                    <dt>交易密码 :</dt>
                    <dd>
                        <p>
                            <input type="password" name="trade_password" id="tradingPassword" class="form-input w210px" placeholder="6到16位的字母及数字组合" autocomplete="off" disableautocomplete/>
{{--                            <a href="{{ App\Tools\ToolUrl::getUrl('/user/information/forgetTradingPassword') }}" target="_blank" class="blue">忘记密码？</a>--}}
                        </p>
                        <p class="web-tips error">
                            @if(Session::has('message'))
                                {{ Session::get('message') }}
                            @endif
                        </p>
                    </dd>
                    <dt>&nbsp;</dt>
                    <dd>
                        {{--<input type="submit" value="同意并确认出借" class="btn btn-red btn-large t-w236px" id="submitBtn">--}}
                        <input type="button" value="同意并确认出借" class="btn btn-red btn-large t-w236px" id="submitBtn">
                        <a href="{{ App\Tools\ToolUrl::getUrl('/project/sdf') }}" class="blue">返回</a>
                    </dd>
                </dl>
            </div>

        </form>
    </div>
@endsection
@section('jspage')
    <script type="text/javascript">
        (function($){
            $(document).ready(function(){

                $("#submitBtn").click(function(){

                    var pass    = $("input[name=trade_password]").val();
                    var cash    = $("input[name=cash]").val();
                    var pid     = $("input[name=project_id]").val();
                    var token   = $("input[name=_token]").val();

                    $.ajax({
                        url      : '/invest/project/confirm',
                        type     : 'POST',
                        dataType : 'json',
                        data     : {project_id:pid,cash:cash,trade_password:pass,_token:token},
                        success  : function(res){

                            if(res.status){
                                $(".error").html('');
                                window.location.href='/invest/project/success';
                            }else{
                                $(".error").html(res.msg);
                                return false;
                            }
                        }
                    });
                });

                $("input[name=trading_password]").focus(function(){
                    $(".error").html('');
                });



                $("#cash").val('');
                $("#tradingPassword").val('');
                $(".poshytip").poshytip({showTimeout: 1});
                $("#submitBtn").prop("disabled", true).addClass("disabled");
                $("#aggreement,#tradingPassword,#cash").bind("keyup blur click" ,function() {
                    var invest = $("#cash").val();
                    var tradding = $("#tradingPassword").val();
                    if($("#aggreement").is(":checked") && invest>0 && tradding!='') {
                        $("#submitBtn").prop("disabled", null).removeClass("disabled");
                    } else {
                        $("#submitBtn").prop("disabled", true).addClass("disabled");
                    }
                });
            });
        })(jQuery);

    </script>
@endsection
