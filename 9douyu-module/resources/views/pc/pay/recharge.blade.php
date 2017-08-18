@extends('pc.common.layout')

@section('title', '充值支付－九斗鱼')

@section('content')

<div class="m-myuser">
    <!-- account begins -->
    @include('pc.common/leftMenu')

    <div class="m-content grayborder">
        <div class="m-pagetitle hidden"><p class="fl">我要充值</p><p class="fr t-racharge"><span></span><a href="javascript:;" id="t-recharge-notice" data-target="modul1">充值须知</a></p></div>
        <div class="t-r-showbox hidden">
            <form action="/recharge/submit" method="post" id="rechargeForm">
                <div class="fl t-recharge5">
                    <p class="t-recharge2"><span>充值金额</span><input type="text" id="recharge-cash" name="cash" autocomplete="off" maxlength="8" class="form-input t-recharge-input" /> 元</p>
                    <p class="tips mt5 t-recharge-tip" id="cash-tips" style="width:233px; position:static">
                    @if(Session::has('errors')) {{ Session::get('errors') }} @endif</p>
                </div>
                <div class="fr t-recharge3">
                    <p>当前可用余额：<span>{{ $user['balance'] }}</span>元</p>
                    <p id="lastBalance" style="display:none;">充值后余额：<span class="fontorange t-red" balance="{{ $user['balance'] }}">{{ $user['balance'] }}</span>元</p>
                </div>
                <div class="clear"></div>
                <p class="t-recharge4"></p>
                <div class="t-recharge6">
                    <p class="t-recharge6-1">充值方式</p>
                    <ul class="recharge-method t-recharge-nav">
                        <li class="t-selected" data-type="1">网上银行充值<span></span></li>
                        @if (!empty($authBanks))
                            <li data-type="2">快捷支付<span></span></li>
                        @else
                            <li data-type="2" class="recharge-width-nav">快捷支付<em></em><img src="{{assetUrlByCdn('/static/images/bank-img/'.$authCard['info']['bank_id'].'.png')}}" width="25" alt="" /><b>{{$authCard['info']['bank_name']}} **** {{substr($authCard['info']['card_no'],-4)}}</b><span></span></li>
                        @endif
                    </ul>
                </div>
                <div class="clear"></div>
                <!-- 网上银行充值 -->
                <div class="recharge-bank-box t-recharge8">
                    <p class="t-recharge7"><span></span>请确保已选择的银行已开通网上支付功能，不支持信用卡充值。</p>
                    <p id="bank-limit" style="width:580px; color:red"></p>
                    <div class="t-recharge9">
                        <p class="t-recharge6-1 t-recharge6-2">选择银行</p>
                        <div class="t-recharge-bank">
                            <ul class="recharge-bank hidden t-recharge-bank1" data-type="1">
                                @foreach($unionPay as $union)
                                    <li bvalue="{{ $union['bank_id'] }}" data-alias="{{ $union['alias'] }}" ><img src="{{assetUrlByCdn('/static/images/bank-img/'.$union['bank_id'].'.gif')}}" width="136" height="50" /><span></span></li>
                                @endforeach
                            </ul>
                        </div>
                        <p class="bank-tips" style="width:233px"></p>
                        <p id="ie-tips" class="t-recharge7 t-recharge7-1" style="display:none"><span></span>此银行可能暂不支持该浏览器充值，建议您使用绑定银行卡充值或更换浏览器（如IE或360等浏览器）充值。</p>
                        <input name="payType" type="hidden" value="1" />
                        <input name="bankId" type="hidden" />
                        <input name="bankCode" type="hidden" />
                        <input type="submit" class="btn btn-red btn-large t-recharge-btn" value="充  值">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    </div>
                </div>


                <!-- 快捷支付  -->

                <div class="recharge-bank-box t-recharge10 hidden" style="display:none">
                    <p id="t-recharge-method-txt" class="t-recharge7"></p>
                    <p class="t-recharge11">每个用户只能选择一张银行卡作为快捷支付卡，一旦支付成功后，将只能提现到该快捷卡。</p>
                    <p id="bank-limit" style="width:580px; color:red"></p>
                    <div class="t-recharge9">

                        {{--<p class="t-recharge6-1 t-recharge6-2">选择银行</p>--}}
                        {{--<div class="t-recharge-bank">--}}
                            {{--@if (!empty($authBanks))--}}
                                {{--<ul class="recharge-bank hidden t-recharge-bank1" data-type="2">--}}
                                    {{--@foreach($authBanks as $authBank)--}}
                                        {{--<li bvalue="{{ $authBank['bank_id'] }}" data-cash="{{ $authBank['cash'] }}"><img src="{{ URL::asset('/') }}static/images/bank-img/{{ $authBank['bank_id'] }}.gif" width="136" height="50"/><span></span></li>--}}
                                    {{--@endforeach--}}
                                {{--</ul>--}}
                            {{--@else--}}
                                {{--<span id="bindBankUser"></span>--}}
                                {{--<span id="maxCash" data-value="{{ $authCard['limit']['cash'] }}"></span>--}}
                                {{--<ul class="recharge-bank hidden t-recharge-bank1" data-type="2">--}}
                                    {{--<li class="t-selected t-recharge-num" data-value="{{ $authCard['info']['bank_id'] }}" bvalue="{{ $authCard['info']['bank_id'] }}" data-cash="{{ $authCard['limit']['cash'] }}"><img src="{{ URL::asset('/') }}static/images/bank-img/{{ $authCard['info']['bank_id'] }}.gif" width="136" height="50" class="fl" /><i>尾号<em>{{ substr($authCard['info']['card_no'],-4,4) }}</em></i><span class="t-icon"></span></li>--}}
                                {{--</ul>--}}
                                {{--<input type="hidden" name="card_no" value="{{ $authCard['info']['card_no'] }}">--}}
                            {{--@endif--}}
                        {{--</div>--}}

                        @if (!empty($authBanks))
                            <p class="t-recharge6-1 t-recharge6-2">选择银行</p>
                            <div class="t-recharge-bank">
                                <ul class="recharge-bank hidden t-recharge-bank1" data-type="2">
                                    @foreach($authBanks as $authBank)
                                        <li bvalue="{{ $authBank['bank_id'] }}" data-cash="{{ $authBank['cash'] }}"><img src="{{assetUrlByCdn('/static/images/bank-img/'.$authBank['bank_id'].'.gif')}}"  width="136" height="50"/><span></span></li>
                                    @endforeach
                                </ul>
                            </div>
                            <input name="isBind" type="hidden" value="0"/>

                        @else
                            <span id="maxCash" data-value="{{ $authCard['limit']['cash'] }}"></span>
                            <p class="t-recharge6-1 t-recharge6-2">支付渠道</p>
                            <div class="recharge-channel-wrap clearfix">
                                @foreach($authCard['list'] as $val)
                                    <ul class="recharge-channel t-selected" bvalue="{{$val['pay_type']}}" data-cash="{{$val['real_limit']}}">
                                        <li class="recharge-channel-bt"><img src="{{assetUrlByCdn('/static/images/bank-img/'.$val['pay_type'].'.png')}}"  width="72" alt="{{$typeList[$val['pay_type']]['name']}}">{{$typeList[$val['pay_type']]['name']}}<span></span></li>
                                        <li class="recharge-channel-txt clearfix"><span class="fl">单笔限额</span><em class="fr">{{$val['limit']}}元</em></li>
                                        <li class="recharge-channel-txt clearfix"><span class="fl">当日限额剩余</span><em class="fr">{{$val['day_free_limit']}}元</em></li>
                                    </ul>
                                @endforeach
                            </div>
                            <input type="hidden" name="card_no" value="{{ $authCard['info']['card_no'] }}">
                            <input name="channel" type="hidden" />
                            <input name="realLimit" type="hidden" />
                            <input name="isBind" type="hidden" value="1"/>

                        @endif


                        <p class="bank-tips" style="width:233px"></p>
                        <div class="clear"></div>
                        @if (!empty($authBanks))
                        <div class="bank-loupe-box t-recharge12" style="display:block">
                            <p id="bank-card-large" class="t-recharge12-1"></p>
                            <p class="t-recharge2"><span>银行卡号</span><input type="text" name="card_no" class="form-input t-recharge-input1" placeholder="此处输入银行卡号"/></p>
                            <p id="bank-card-tips" style="width:233px"></p>
                        </div>
                        @endif
                        <input name="payType" type="hidden" value="2" />
                        <input name="bankId" type="hidden" />
                        <input type="submit" class="btn btn-red btn-large t-recharge-btn" value="充  值">
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <!--充值确认new -->
    <div class="layer_wrap js-mask" id="t-box-confirm" data-modul="modul2">
    <div class="Js_layer_mask layer_mask" data-toggle="mask" data-target="js-mask"></div>
    <div class="Js_layer layer">
        <div class="layer_title">充值确认<a href="javascript:;" class="layer_close Js_layer_close" data-toggle="mask" data-target="js-mask"></a></div>
        <em class="t-alert-p">请在新打开的网上银行支付页面完成充值操作</em>
            <a href="#" data-toggle="mask" data-target="js-mask" class="btn btn-red btn-large t-alert-btn2">充值成功</a><a href="#" data-toggle="mask" data-target="js-mask" class="btn btn-yellow btn-large t-alert-btn3">我不想充值了</a>
            <div class="t-r-qustion"><em></em><a href="http://www.sobot.com/chat/pc/index.html?sysNum=54037ae382a141c8b7fa69f402a99b7c" target="_blank" class="t-r-qustion1">我遇到了问题</a></div>
        </div>
    </div>

    <!-- 充值须知new-->
    <div class="layer_wrap js-mask" data-modul="modul1">
        <div class="Js_layer_mask layer_mask" data-toggle="mask" data-target="js-mask"></div>
        <div class="Js_layer layer">
             <div class="layer_title">充值须知<a href="javascript:;" class="layer_close Js_layer_close" data-toggle="mask" data-target="js-mask"></a></div>
             <dl class="t-alert-know">
                <dt>1.</dt>
                <dd>在支付页面完成充值后，请点击"返回商户"连接，不要直接关闭支付页面窗口，否则可能会造成充值金额延后到账；若充值金额未及时到账，请联系客服；</dd>
                <dt>2.</dt>
                <dd>单笔充值金额{{ $recharge_min_money }}元起，每日的充值限额依据各银行限额为准；</dd>
                <dt>3.</dt>
                <dd>严禁利用充值功能进行信用卡套现、转账、洗钱等行为，一经发现，资金将退回原卡并封停账号{{ $withdrawConfig['closeAccountDays'] }}天；</dd>
                <dt>4.</dt>
                <dd>账户资金每自然月有{{ $withdrawConfig['maxFreeNum'] }}次免费提现机会，超过{{ $withdrawConfig['maxFreeNum'] }}次以后的每笔提现将收{{ $withdrawConfig['handingFree'] }}元手续费。</dd>
                <dt>5.</dt>
                <dd>点击充值按钮，表示您已经仔细阅读并同意以上资金管理规定条款。</dd>
            </dl>

            <a href="#" data-toggle="mask" data-target="js-mask" class="btn btn-blue btn-large t-alert-btn t-mt30px">我知道了</a>
             
        </div>
    </div>
    <div class="clearfix"></div>
</div>
@endsection

@section('jspage')
<script>
        (function($){
            $(document).ready(function(){

                var maxCash = $("#maxCash").attr("data-value") ? $("#maxCash").attr("data-value") : 0;

                var data_type = $(".recharge-method li").attr("data-type");

                var isBind = $("input[name=isBind]").val();

                if(data_type==1){
                    $(".t-recharge4").html("<span></span>温馨提示：{{ $withholding_recharge_min_money }}元起充,单笔充值限额视开户行定");
                }else if(maxCash>0){
                    $(".t-recharge4").html("<span></span>温馨提示：{{ $withholding_recharge_min_money }}元起充,单笔充值限额"+maxCash+"元");
                }else{
                    $(".t-recharge4").html("<span></span>温馨提示：{{ $withholding_recharge_min_money }}元起充");
                }

                var currentRechargeMethod = 1;

                //LUHN算法，主要用来计算信用卡等证件号码的合法性
                var luhn = function(num){
                    var str='';
                    var numArr = num.split('').reverse();
                    for(var i=0;i<numArr.length;i++){
                        str+= (i % 2 ? numArr[i] * 2 : numArr[i]);
                    }
                    var arr = str.split('');

                    var sum = 0;
                    for(var i = 0; i < arr.length; i++) {
                        val = parseInt(arr[i]);
                        if(isNaN(val)) return false;
                        sum += val;
                    }

                    return  sum % 10 == 0;
                }

                //充值须知
                $("#recharge-info,#recharge-info-more").click(function(){
                    $("#recharge-info-main").popDiv(720);
                });
                $(".new-btn").click(function(){
                    $(this).parent().parent().parent(".pop-wrap").hide();
                });

                $("#recharge-cash").keyup(function(){
                    $(this).formatInput(/^[1-9][0-9]*$/);

                    $("#rechargeForm").data("canSubmit", false);
                    $(".tips").hide().html('');
                    $("#lastBalance").show();
                    var rechargeCash = $.trim($(this).val());
                    if(rechargeCash){
                        var lastBalance = parseFloat(rechargeCash) + parseFloat($(".fontorange").attr("balance"));
                        $(".fontorange").html(lastBalance.toFixed(2));
                    }else{
                        $("#lastBalance").hide();
                    }
                    $(this).val(rechargeCash);
                });

                $("input[name=card_no]").keyup(function(){
                    $(this).formatInput(/^[1-9][0-9\\s]*$/);
                    $("#rechargeForm").data("canSubmit", false);
                    $(".tips").hide().html('');
                });

                $("#rechargeForm").submit(function() {

                    if(!$(this).data("canSubmit")) {


                        var flag = true;
                        var minCash = {{ $withholding_recharge_min_money }};
                        var withholdingMinCash = {{ $withholding_recharge_min_money }};
                        var cash    = $.trim($("#recharge-cash").val());
                        var maxCash = $("#maxCash").attr("data-value");
                        //var reg = /[1-9][0-9]*/g;
                        //maxCash = parseInt(maxCash.match(reg));

                        maxCash = parseInt(maxCash);
                        cash = parseInt(cash);

                        //今日到达限额
                        if(isNaN(maxCash) && currentRechargeMethod != 1 && isBind == 1){

                            $("#cash-tips").addClass("t-recharge-tip").show().html('该银行今日已达充值限额，请联系客服');
                            return false;
                        }
                        if(isNaN(cash) || cash < 0){
                            $("#cash-tips").addClass("t-recharge-tip").show().html("请填写充值金额");
                            return false;
                        }

                        if(currentRechargeMethod != 1 && cash < withholdingMinCash){
                            $("#cash-tips").addClass("t-recharge-tip").show().html("绑定银行卡充值单笔最小充值金额为{{ $withholding_recharge_min_money }}元");
                            return false;
                        }else if(currentRechargeMethod == 1 && cash < minCash) {
                            $("#cash-tips").addClass("t-recharge-tip").show().html('最小充值金额为'+minCash+'元');
                            return false;
                        }else if(currentRechargeMethod != 1 && maxCash == 0){
                            //超过最大限额判断
                            $("#cash-tips").addClass("t-recharge-tip").show().html('今日已达限额，请明日再来!');
                            return false;
                        }else if(currentRechargeMethod != 1 && cash > maxCash){
                            //快捷支付最大限额判断
                            $("#cash-tips").addClass("t-recharge-tip").show().html('最大充值金额为'+maxCash+'元');
                            return false;
                        }

                        if( currentRechargeMethod != 1 ){

                            if(isBind != 1){
                                if(!$(".recharge-bank[data-type="+ currentRechargeMethod +"] li").hasClass("t-selected")) {
                                    $(".bank-tips").addClass("t-recharge-tip").show().html('请选择银行');
                                    return false;
                                }
                            }else{
                                //绑卡情况
                                if(!$(".recharge-channel").hasClass('channel-select')){
                                    $(".bank-tips").addClass("t-recharge-tip").show().html('请选择支付渠道');
                                    return false;
                                }

                                var realLimit = $("input[name=realLimit]").val();

                                realLimit = parseInt(realLimit);

                                if(cash > realLimit){

                                    $(".bank-tips").addClass("t-recharge-tip").show().html('该通道最多可充值'+realLimit+'元');
                                    return false;
                                }

                            }

                        }else{

                            if(!$(".recharge-bank[data-type="+ currentRechargeMethod +"] li").hasClass("t-selected")) {
                                $(".bank-tips").addClass("t-recharge-tip").show().html('请选择银行');
                                return false;
                            }


                            $(".recharge-bank[data-type="+ currentRechargeMethod +"] li").each(function(){
                                if($(this).hasClass("t-selected")){
                                    var bankCode = $(this).attr("data-alias");
                                    $("input[name=bankCode]").val(bankCode);
                                }
                            });

                        }

                        if($("input[name=card_no]").is(":visible") == true) {
                            var card_no = $("input[name=card_no]").val().replace(/\\s/g,'');
                            var len     = card_no.length;

                            if((len == 16 || len == 18 || len == 19) && luhn(card_no)){
                                $("#bank-card-tips").addClass("t-recharge-tip").hide();
                            }else{
                                $("#bank-card-tips").addClass("t-recharge-tip").show().html('请输入正确的银行卡号');
                                return false;
                            }
                        }

                        if(flag) {
                            $(this).data("canSubmit", true);
                            $("#rechargeForm").submit();
                        }
                        return false;
                    }else{
                        var bankCadStr = $("input[name=bankId]").val();
                        if(bankCadStr){
                            $("#t-box-confirm").show();
                            $("#rechargeForm").attr("target","_blank");
                        }
                    }
                });

                //判断浏览器及支付方式给出提示信息
                var isIE = /msie/.test(navigator.userAgent.toLowerCase());
                if(!isIE){
                    $(".recharge-bank li").each(function(){
                        if($(this).attr("data-type") != 1){
                            return;
                        }

                        if($(this).hasClass("t-selected")){
                            $("#ie-tips").show();
                        }else{
                            $("#ie-tips").hide();
                        }

                        $(this).click(function(){
                            $("#ie-tips").show();
                        });

                    })
                }

                //充值方式
                $(".recharge-method li").click(function(){

                    $("#cash-tips").hide();
                    $(".bank-tips").addClass("t-recharge-tip").hide();

                    var index = $(this).index();
                    currentRechargeMethod = $(this).attr("data-type");

                    $("#rechargeForm").data("canSubmit", false);

                    $(".recharge-bank[data-type="+ currentRechargeMethod +"] li").each(function(){
                        if($(this).hasClass("t-selected")){
                            var selfBvalue = $(this).attr("bvalue");
                            $("input[name=bankId]").val(selfBvalue);
                        }
                    });

                    if(currentRechargeMethod != 1){
                        $("#ie-tips").hide();
                        if(isBind == 0){
                            $(".bank-loupe-box").show();
                        }
                    }else{
                        $("#ie-tips").show();
                        $(".bank-loupe-box").hide();
                    }

                    if($(this).hasClass("t-selected")) {
                        currentRechargeMethod = $(this).parent().attr("data-type");
                        $("input[name=payType]").val(currentRechargeMethod);
                        return false;
                    }else{
                        $(this).addClass("t-selected").siblings().removeClass("t-selected");

                        $(".recharge-bank-box").hide().eq(index).show();
                    }


                    $("input[name=payType]").val(currentRechargeMethod);


                    if($(this).index()==0){
                        $("#bank-limit").text("");
                        $(".t-recharge4").html("<span></span>温馨提示：{{ $withholding_recharge_min_money }}元起充,单笔充值限额视开户行定");
                    }else{
                        $("#t-recharge-method-txt").html("<span></span>仅支持绑定开户名为{{$user['real_name']}}的借记卡（无需开通网银），且单笔最小充值金额为{{ $withholding_recharge_min_money }}元。");
                        if(maxCash>0)
                            $(".t-recharge4").html("<span></span>温馨提示：{{ $withholding_recharge_min_money }}元起充,单笔充值限额"+maxCash+"元");
                        else
                            $(".t-recharge4").html("<span></span>温馨提示：{{ $withholding_recharge_min_money }}元起充");
                        // $("#bank-limit").text("{:getConfig('RECHARGE_NOTE')}");
                    };
                });

                $(".recharge-bank li").click(function(){

                    $("#cash-tips,.bank-tips").hide().html('');
                    $("input[name=card_no]").val('');
                    $(".bank-loupe").text('');

                    var dataValue = $(this).attr("data-value");
                    currentRechargeMethod = $(this).parent().attr("data-type");

                    $("input[name=payType]").val(currentRechargeMethod);

                    if(currentRechargeMethod == 2 && dataValue != 1){
                        $(".bank-loupe-box").show();
                    }else{
                        $(".bank-loupe-box").hide();
                    }

                    if(!$(this).hasClass("t-selected")){
                        $("input[name=bankId]").val($(this).attr("bvalue"));

                        $(".recharge-bank[data-type="+ currentRechargeMethod +"]").each(function(){
                            $(this).find("li").removeClass("t-selected");
                        });

                        $(this).addClass("t-selected");
                        //.siblings().removeClass("selected");
                    }

                });



                //银行卡号码放大镜效果
                $("input[name=card_no]").bind("keyup blur",function(){
                    var cardNo = $(this).val().replace(/\\s/g,'');
                    var formatText = cardNo.replace(/(\\d{4})(?=\\d)/g,"$1 ");
                    $(this).val(formatText);
                    $("#bank-card-large").show().text(formatText);
                });

                //支付渠道 select效果
                $(".recharge-channel").click(function(){
                    if(!$(this).hasClass("channel-select")){

                        $(".recharge-channel").removeClass("channel-select");

                        $(this).addClass("channel-select");

                        $("input[name=channel]").val($(this).attr("bvalue"));
                        $("input[name=realLimit]").val($(this).attr("data-cash"));


                    }else{
                        $(this).removeClass("channel-select");

                    }

                });
                             
            });
        })(jQuery);
    </script>
@endsection
