@extends('pc.common.layout')

@section('title','确认出借')

@section('content')

    <div class="web-invest-confirm">
    <form action="/invest/term/submit" method="post" id="investConfirm">
        <div class="web-invest-confirm-title">
            <i></i>
            <span>确认出借</span>
        </div>
        <ul class="web-invest-confirm-info">
            <li class="web-invest-confirm-box1">
                <i class="web-invest-confirm-icon1"></i><span>出借金额 :</span><strong>{{ number_format($total_cash) }}</strong><ins> 元</ins>
            </li>

            @if($bonusValue > 0)
            <li class="web-invest-confirm-box2">
                <i class="web-invest-confirm-icon2"></i>
                <span>使用优惠 :</span><strong>{{ $bonusValue }}</strong>
                <ins>
                    @if($bonusType == \App\Http\Dbs\Bonus\BonusDb::TYPE_COUPON_INTEREST)
                        加息券
                    @elseif( $bonusType == \App\Http\Dbs\Bonus\BonusDb::TYPE_CASH )
                        红包
                    @endif
                </ins>
            </li>
            @endif
            <li class="web-invest-confirm-box3">
                <i class="web-invest-confirm-icon3"></i>
                @if($bonusValue > 0 && $bonusType == \App\Http\Dbs\Bonus\BonusDb::TYPE_COUPON_INTEREST)
                <span>预期总收益 :</span>
                @else
                <span>预期收益 :</span>
                @endif
                <strong>{{ $fee }}</strong><ins>元</ins>
            </li>
        </ul>
        <div class="web-invest-confirm-funds">
            <p><span>账户余额 :</span><strong>{{ number_format($balance,2) }}</strong>元</p>
            <p><span>余额支付 :</span><strong>{{ number_format($cash,2) }}</strong>元</p>
        </div>
        <div class="web-invest-confirm-wrap">
            <p style="display: none;"><label><input type="checkbox" id="aggreement" checked="checked">我已阅读并同意  </label>
                @if($project['product_line'] == \App\Http\Dbs\Project\ProjectDb::PROJECT_PRODUCT_LINE_JSX && $project['type'] == \App\Http\Dbs\Project\ProjectDb::INVEST_TIME_DAY_ONE)
                    <a class="blue">《九斗鱼九省心投资协议》</a>  {{--onclick="window.open('/agreement/free.pdf')"  style="cursor:pointer"--}}
                @elseif($project['product_line'] == \App\Http\Dbs\Project\ProjectDb::PROJECT_PRODUCT_LINE_JAX)
                    <a class="blue">《应收账款转让及回购协议》</a>  {{--onclick="window.open('/agreement/factor.pdf')"  style="cursor:pointer"--}}
                @else
                    <a class="blue">《投资咨询与管理服务协议》</a>   {{--onclick="window.open('/agreement/argument.pdf')"   style="cursor:pointer"--}}
                    <a class="blue">《债权转让协议》</a>   {{--onclick="window.open('/agreement/credit.pdf')"  style="cursor:pointer"--}}
                @endif

            </p>
            <p style="font-size: 12px; color: #999;text-indent: 23px;">九斗鱼提示您：网贷有风险，投资需谨慎。</p>
            <dl class="web-invest-confirm-group clearfix">
                <dt>交易密码 :</dt>
                <dd>
                    <p>
                        <input type="password" name="trading_password" class="form-input w210px" placeholder="6到16位的字母及数字组合">
                        <input type="hidden" name="cash" value="{{ $cash }}">
                        <input type="hidden" name="project_id" value="{{ $project['id'] }}">
                        <input type="hidden" name="bonus_id" value="{{ $userBonusId }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    </p>
                    <p class="web-tips error">
                    </p>
                </dd>
                <dt>&nbsp;</dt>
                <dd>
                    <input type="button" value="同意并确认出借" class="btn btn-red btn-large t-w236px" id="submitBtn">
                    <a href="/project/detail/{{ $project['id'] }}" class="blue">返回</a>
                </dd>
            </dl>
        </div>

        </form>
</div>
    <script src="{{assetUrlByCdn('/static/js/pc2.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        (function($){
            $(document).ready(function(){
                $(".poshytip").poshytip({showTimeout: 1});
                $("#aggreement").click(function() {
                    if($(this).is(":checked")) {
                        $("#submitBtn").prop("disabled", null).removeClass("disabled");
                    } else {
                        $("#submitBtn").prop("disabled", true).addClass("disabled");
                    }
                });

            });
        })(jQuery);

    </script>
    <script type="text/javascript">

        (function($){

            $(document).ready(function(){
                $("#submitBtn").removeAttr('disabled').removeClass('disabled');
                $("#submitBtn").click(function(){
                    
                    var pass = $("input[name=trading_password]").val();
                    var cash = $("input[name=cash]").val();
                    var pid  = $("input[name=project_id]").val();
                    var bid  = $("input[name=bonus_id]").val();
                    var token = $("input[name=_token]").val();

                    if(pass.length < 1){

                        $(".error").html('请输入交易密码');

                        return false;
                    }else if(pass.length < 6 || pass.length > 16){

                        $(".error").html('请输入正确的交易密码');

                        return false;
                    }
                    $("#submitBtn").attr('disabled',true).addClass('disabled');

                    $.ajax({
                        url      : '/invest/project/confirm',
                        type     : 'POST',
                        dataType : 'json',
                        data     : {project_id:pid,cash:cash,bonus_id:bid,trade_password:pass,_token:token},
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

            });
        })(jQuery);
    </script>
@endsection