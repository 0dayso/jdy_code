@extends('wap.common.wapBase')

@section('title', '九斗鱼')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <form action="/pay/submit" method="post" id="rechargeForm">

            @if(!$isBind)
                <section class="wap2-input-group w-q-mt">
                    <div class="wap2-input-box bbd3" id="w-alert">
                        <span class="wap2-input-icon wap2-input-icon7"></span>
                        <p class="f14 blue" id="bname">请选择开户行</p>
                        <span class="wap2-arrow-1"></span>
                    </div>
                    <section>
                        <dl class="wap2-dl">
                            您的充值卡也将作为您的提现卡使用，建议您选择经常使用的银行卡
                            <dt></dt>
                        </dl>
                    </section>
                    <div class="wap2-input-box">
                        <span class="wap2-input-icon wap2-input-icon8"></span>
                        <input type="text" class="m-input mb5" id="card_no" name="card_no" data-validate="card" placeholder="银行卡号" />
                    </div>
                </section>
                <!--  弹出框 -->
                <div class="box_alert">
                    <div class="mask"></div>
                    <div class="bomb_box">
                        <span class="close_box"><img src="{{assetUrlByCdn('/static/weixin/images/wap2/w-icon-close.png')}}"/></span>

                        <div id="m-bank-new-main" class="w-bank">
                            @foreach($authBanks as $authBank)
                            <p class="mt15px"><label class="m-bank-label"><input type="radio" name="bank_card_str" data-value="1" data-bank-code="{{ App\Http\Models\Bank\CardModel::getBankCode($authBank['bank_id']) }}" data-cash="{{ $authBank['cash'] }}" data-validate="bank" value="{{ $authBank['bank_id'] }}" bname="{{ App\Http\Models\Bank\CardModel::getBankName($authBank['bank_id']) }}" class="paylimit">&nbsp;<img src="{{ assetUrlByCdn('/static/images/bank-img/'.$authBank['bank_id'].'.gif') }} class="bank-img2"></label></p>
                            <input type="hidden" name="bankId" value="">
                            <input type="hidden" name="bankCode" value="">
                            @endforeach
                        </div>
                    </div>
                </div>
                <!--  弹出框 -->
                <section class="wap2-input-group w-q-mt">
                    <div class="wap2-input-box2">
                        <p class="fr pr">
                            <input type="text" placeholder="请输入金额" class="wap2-input-cash" id="cash" name="cash" maxlength="8" data-validate="cash" autocomplete="off" role-value=""/>
                            元</p>
                        <p>充值金额</p>
                    </div>
                </section>
            @else
                <span id="maxCash" data-cash="{{ $authCard['limit']['cash'] }}"></span>
                <span id="minCash" data-value="{{ $withholding_recharge_min_money }}"></span>
                <section class="wap2-input-group w-q-mt">
                    <div class="wap2-input-box2 bbd3">
                            <span style="display: none"><input type="radio"  checked="checked" name="bank_card_str" bname="{{ $authCard['info']['bank_name'] }}" value="{{ $authCard['info']['bank_id']  }}" /></span>
                            <p class="fr">
                                 <span>
                                    {{ $authCard['info']['bank_name'] }}
                                    <span class="bank-num-4">(****&nbsp;{{ substr($authCard['info']['card_no'],-4,4) }})</span>
                                 </span>
                            </p>
                        <p>储蓄卡</p>
                    </div>
            
                    <div class="wap2-input-box2">
                        <p class="fr pr">
                            <input type="text" placeholder="请输入金额" class="wap2-input-cash" id="cash" name="cash" maxlength="8" data-validate="cash" autocomplete="off" role-value=""/>
                            元</p>
                        <p>充值金额</p>
                    </div>
                </section>

                <section>
                    <dl class="wap2-dl">
                        <dt></dt>
                        <dd class="color8c" id="rechargeMsg">{{ $withholding_recharge_min_money }}元起充,单笔充值限额{{ $authCard['limit']['cash'] }}元</dd>
                    </dl>
                </section>
            @endif
            <input type="hidden" name="payType" value="2">
            <section class="wap2-tip error">
                <p id="msgtip">@if(Session::has('errors')) {{ Session::get('errors') }} @endif</p>
            </section>

            <section class="wap2-btn-wrap">
                <input type="button" value="返回" class="wap2-btn wap2-btn-half fl wap2-btn-blue backup" onclick="history.go(-1)">
                <input type="submit" value="确认" class="wap2-btn wap2-btn-half fr">
               <!-- <input type="submit" class="wap2-btn" value="下一步">
-->
                <input type="hidden" name="_token" value="{{csrf_token()}}">
            </section>
        </form>

    <!-- 输入交易密码碳层 -->
    @if(isset($_COOKIE['failOrder']))
    <section class="wap2-pop" id="tp_one">
        <div class="wap2-pop-mask"></div>
        <div class="wap2-pop-main">

            <div class="wap2-pop-tpw-box wap2-recharge-pop">

                <form>
                    <p >
                        您确认要放弃本次充值操作吗？
                    </p>

                    <input type="button" value="我不想充值了"  class="wap2-input-link" id="give_up">
                    <input type="button" value="继续充值，赢取更多收益"  class="wap2-input-link" id="continue">
                    <a href="http://www.sobot.com/chat/pc/index.html?sysNum=54037ae382a141c8b7fa69f402a99b7c" ><input type="text" name="" id="" class="wap2-input-link" value="我遇到了问题"  ></a>
                    <input type="hidden" value="{$failOrder}" id="failorder">
                </form>i

            </div>
        </div>
    </section>
    @endif


    <!-- 密码错误弹层开始 -->
    <section class="wap2-pop" style="display:none;">
        <div class="wap2-pop-mask"></div>
        <div class="wap2-pop-main">
            <div class="wap2-pop-tpw-title">
                <i class="red">充值失败！</i>
            </div>
            <div class="wap2-pop-tpw-box clearfix">
                <form>
                    <p class="f18 mt1 mb2 tc">密码错误，请重试</p>
                    <input type="reset" value="取消" class="wap2-btn wap2-btn-half fl wap2-btn-blue">
                    <input type="submit" value="确定" class="wap2-btn wap2-btn-half fr">
                </form>

            </div>
        </div>
    </section>
    <!-- 密码错误弹层结束 -->

    <!-- 密码错误弹层开始 -->
    <section class="wap2-pop" style="display:none;">
        <div class="wap2-pop-mask"></div>
        <div class="wap2-pop-main">
            <div class="wap2-pop-tpw-title">
                <i class="red">充值失败！</i>
            </div>
            <div class="wap2-pop-tpw-box clearfix">
                <div class="ml2 mr2">
                    <p class="f18 mt1 mb2 tc">您已到达今日充值限额</p>
                    <a href="" class="wap2-btn">我知道了</a>
                </div>

            </div>
        </div>
    </section>
    <!-- 密码错误弹层结束 -->
    </form>
@endsection

@section('jsScript')
    @include('wap.common.js')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        (function ($) {

            var minCash = $("#minCash").attr("data-value");
            minCash = parseInt(minCash);
            if(isNaN(minCash)){
                $("#rechargeMsg").html("{{ $withholding_recharge_min_money }}元起充");
            }
            
            
            //当前银行卡的限额-先入到页面中,这个是没有绑卡的
            $(".paylimit").on("click",function(){
                var maxCash = $(this).attr("data-cash");
                $("#maxCash").attr("data-cash",maxCash);
            });

            $(document).ready(function () {
                var orderId = $("#failorder").val();
                //$("#give_up").click(function(){$("#tp_one").hide();location.href="/recharge/giveUpOrder?orderId="+orderId })
                $("#continue").click(function(){$("#tp_one").hide();})


                $('input').focus(function(){
                    tipMsg('');
                });

                currentRechargeMethod = {{ $isBind?1:0 }};   //默认没有选择银行卡
                radioChecked = $("input[name='bank_card_str']:checked");

                isDK = false;        //需要输入密码


                var luhn = function(num){
                            var str='';
                            var numArr = num.split('').reverse();
                            for(var i=0;i<numArr.length;i++){
                                str+= (i % 2 ? numArr[i] * 2 : numArr[i]);
                            }
                            var arr = str.split('');
                            return  eval(arr.join("+")) % 10 == 0;
                        }

                var allWHCard = "";
                var allWHCardArr = allWHCard.split("_");

                function tipMsg(msg) {
                    $("#msgtip").text(msg).show();
                }

                $("input[name=cash]").bind("keyup", function () {
                    $(this).formatInput(/^[1-9][0-9]*$/);
                    var cash = $.trim($(this).val())
                });

                //银行卡只能是数字    
                $("input[name=card_no]").bind("keyup", function () {
                    $(this).formatInput(/^[1-9][0-9]*$/);
                });


                $("input[name=card_no]").bind("keyup", function () {
                    var card_no_value = $(this).val();

                    var bankStrValue = $(this).val().split("_");
                    if (bankStrValue[0] == 2) {
                        return;
                    }

                    for (var i = 0; i < allWHCardArr.length; i++) {
                        if (allWHCardArr[i] == card_no_value) {
                            isDK = true;      //需要输入密码
                            break;
                        }
                    }
                });

                $("input[name=bank_card_str]").click(function () {
                    var name = $(this).attr('bname');

                    $('#bname').text(name);
                    $('.box_alert').hide();
                    //ajaxrechargecash(cash,card_str)

                });

                $('#rechargeForm').submit(function () {

                    if (!$(this).data("canSubmit")) {
                        //最高
                        withholdingMaxCash = $("input[name=bank_card_str]:checked").attr('note_limit');
                        //最低
                        withholdingMinCash = {{ $withholding_recharge_min_money }};
                        //bankId
                        var bankId = $("input[name=bank_card_str]:checked").val();
                        $("input[name=bankId]").val(bankId);
                        //bankCode
                        var bankCode = $("input[name=bank_card_str]:checked").attr('data-bank-code');
                        $("input[name=bankCode]").val(bankCode);
                        //当前充值金额
                        var cash = $.trim($("#cash").val());
                        //选择银行卡-最小金额判断//没有填写充值金额

                        //改卡的最大限额
                        var maxCash = $("#maxCash").attr("data-cash");
                        maxCash = parseInt(maxCash);
                        var radioChecked = $("input[name=bank_card_str]:checked");
    
                        //没绑卡
                        if (!currentRechargeMethod ) {
                            if(radioChecked.length==0){
                                tipMsg('请选择银行卡');
                                return false;
                            }
                            var card_no = $.trim($("#card_no").val());
                            var len = card_no.length;
                            if ((len == 19 || len == 16 || len == 18) && luhn(card_no)) {
                                tipMsg('');
                            } else {
                                tipMsg('请输入正确的银行卡号');
                                return false;
                            }
                        }

                        if (cash < withholdingMinCash) {
                            tipMsg('充值最低限额' + withholdingMinCash);
                            return false;
                        }else if(maxCash == 0){
                            tipMsg('今日已达限额，请明日再来!');
                            return false;
                        }else if(cash > maxCash){
                            tipMsg('充值最大限额' + maxCash);
                            return false;
                        }
                        if(cash == ''){
                            tipMsg('请输入充值金额');
                            return false;
                        }

                        return true;
                    }

                    return false;
                });


            });
        })(jQuery);
        /* cash 输入的充值金额
         * method 充值类型
         */
        function ajaxrechargecash(cash,card_str)
        {
            if( cash < {{ $withholding_recharge_min_money }} ||!card_str || !cash)
            {
                return false;
            }
            $.ajax({
                url:"/recharge/validateRecarge",
                type:"POST",
                data:{cash:cash,card_str:card_str},
                dataType:"json",
                async:false,
                success:function(result) {
                    if(result.status !=true)
                    {
                        $("#msgtip").show().html(result.error);
                        $(".wap2-btn").attr("disabled","disabled");
                        //$("#rechargeForm").
                    }
                    if(result.status ==true )
                    {
                        $("#msgtip").hide().html("");
                        $(".wap2-btn").attr("disabled",false);
                    }
                },
                error:function(msg) {
                    $("#msgtip").show().html("服务器发送错误");
                }
            });

        }
    </script>
@endsection