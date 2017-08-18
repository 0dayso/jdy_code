@extends('pc.common.layout')
@section('title','我要提现-九斗鱼,心安而有余')
@section('csspage')

@endsection
@section('content')
    <div class="m-myuser">
        <!-- account begins -->
        @include('pc.common.leftMenu')
        <div class="m-rightcon">
            <p class="m-tocash m-pr">我要提现<i class="m-icon"></i><a href="javascript:;" data-target="modul1" id="withdraw-info">提现须知</a></p>
            <p class="m-tips">
                提示：每位用户每自然月有{{$maxFreeNum}}次免费提现机会，超过{{$maxFreeNum}}次以后的每笔提现将收{{$handlingFree}}元手续费，请知晓。
            </p>
            <span id="withdrawFeeRate" data-value="{{env('WITHDRAW_FEE_RATE')}}"></span>
            <span id="minMoney" data-value="{{$minMoney}}"></span>
            <span id="freeAmount" data-value="{{ $userInfo['balance']}}"></span>
            <span id="needHandling" data-value="{{ $commission }}"></span>
            <span id="withDrawNum" data-value="{{ $withDrawNum['total'] }}"></span>
            <span id="withDrawFreeNum" data-value="{{$maxFreeNum}}"></span>
            @if($withdrawCard)
                <form  action="{{ URL('/pay/withdraw/submit') }}" method="post">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="m-firsttd">姓名</td>
                            <td>{{ $userInfo['real_name'] }}</td><input name="real_name" type="hidden" value="{{ $userInfo['real_name'] }}" class="form-control" readonly>
                        </tr>
                        <tr>
                            <td class="m-firsttd">选择银行卡</td>
                            <td class="m-pr">
                                <div class="m-mselect m-pr">
                                    <span class="m-mselectshow">{{ substr($withdrawCard[0]['card_no'],0,4) }}****{{ substr($withdrawCard[0]['card_no'],-4) }} {{ App\Http\Models\Bank\CardModel::getBankName($withdrawCard[0]['bank_id']) }}</span><i class="m-btnselect"  ></i>
                                    <ul class="m-mlist" id="bank_card_id">
                                        @foreach($withdrawCard as $card)
                                            <li data-target="{{ $card['card_no'] }}" data-target1="{{ $card['bank_id'] }}">{{ substr($card['card_no'],0,4) }}****{{ substr($card['card_no'],-4) }} {{ App\Http\Models\Bank\CardModel::getBankName($card['bank_id']) }}</li>
                                        @endforeach
                                    </ul><p id="bank_card_id_msg"></p>
                                    <input type="hidden" name="card_no" value="{{$withdrawCard[0]['card_no']}}">
                                    <input type="hidden" name="bank_id" value="{{$withdrawCard[0]['bank_id']}}">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="m-firsttd">当前可用余额</td>
                            <td  id="balance" value="{{ $userInfo['balance']}}">{{ $userInfo['balance']}}元</td>
                            <input name="balance" type="hidden" value="{{ $userInfo['balance'] }}" class="form-control" readonly>
                        </tr>
                        <tr>
                            <td class="m-firsttd">申请提现金额</td>
                            <td class="m-pr"><input id="cash" type="text" name="withdraw_cash" value="" maxlength="12" data-validate="age" autocomplete="off" class="m-user js-autocomplete-off" />
                                元<p id="cash_msg"></p></td>
                        </tr>
                        <tr>
                            <td class="m-firsttd">提现手续费</td>
                            <td id="cash_rate">{{ $commission }}元</td>
                            <input name="commission" type="hidden" value="{{ $commission }}" class="form-control" readonly>
                        </tr>
                        <tr>
                            <td class="m-firsttd"> 实际到账金额</td>
                            <td  id="last_cash">0.00元</td>
                            <input name="real_cash" type="hidden" value="" class="form-control" readonly>
                        </tr>
                        <tr>
                            <td class="m-firsttd">交易密码</td>
                            <td class="m-pr">
                                <input type="password" id="trading_password" name="trading_password" data-validate="password"  autocomplete="off" class="m-user js-autocomplete-off" placeholder="6到16位的字母及数字组合" />
                                @if( (Session::get('errors') == \App\Lang\LangModel::getLang('ERROR_USER_PASSWORD_CHECKED')) && Session::has('errors') ) <a href="/user/setting/tradingPassword">设置交易密码</a> @endif
                                <p id="trading_password_msg"> </p></td>
                        </tr>
                    </table>
                    <div class="m-wrongtip">
                        @if(Session::has('errors'))
                            {{  Session::get('errors') }}
                        @endif
                    </div>
                    <input type="hidden" name="from" value="pc">
                    <input name="version" type="hidden" value="">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="btn btn-blue btn-large btn-block w230px mauto" id="withdraw-info1" >确认提现</button>
                </form>
                @else
                <div class=" center mb40">暂未添加提现银行卡：<a href="/user/bankcard/add" class="blue">立即添加？</a> </div>
            @endif
                    <!-- 提现须知new -->
                <div class="layer_wrap js-mask" data-modul="modul1">
                    <div class="Js_layer_mask layer_mask" data-toggle="mask" data-target="js-mask"></div>
                    <div class="Js_layer layer">
                        <div class="layer_title">提现须知<a href="javascript:;" class="layer_close Js_layer_close" data-toggle="mask" data-target="js-mask"></a></div>
                        <dl class="t-alert-know">
                            <dt>1.</dt>
                            <dd> 每位用户每自然月有{{$maxFreeNum}}次免费提现机会，超过{{$maxFreeNum}}次以后的每笔提现将收{{$handlingFree}}元手续费：</dd>
                            <dt>2.</dt>
                            <dd>单笔提现金额{{$minMoney}}元起</dd>
                            <dt>3.</dt>
                            <dd> 收到您的提现申请后，九斗鱼将在下一个工作日将钱转入您绑定的提现银行卡中（如遇周末或节假日，顺延至假期后的第一个工作日）；</dd>
                            <dt>4.</dt>
                            <dd>周末和法定节假日期间，用户可申请提现，九斗鱼将在假期后的第一个工作日进行处理，不便之处，敬请谅解！</dd>
                            <dt>5.</dt>
                            <dd> 提现时，只支持提现到借记卡，不能提现到信用卡。</dd>
                            <dt>6.</dt>
                            <dd>九斗鱼提现全部由第三方支付公司“网银在线”代付，单笔代付不超过5万，当提现金额超过5万，将分为多笔到账，请知晓。</dd>
                        </dl>
                        <a href="#" data-toggle="mask" data-target="js-mask" class="btn btn-blue btn-large t-alert-btn t-mt30px">我知道了</a>
                    </div>
                </div>
        </div>

        <div class="clearfix"></div>
    </div>
@endsection
@section('jspage')
    <script type="text/javascript" src="{{assetUrlByCdn('/static/js/jquery.plugin.js')}}"></script>
    <script type="text/javascript">
        (function($){
        //$(document).ready(function(){
            //超过一定次数,提现手续费,提现次数
            var needHandling = parseInt($("#needHandling").attr('data-value'));
            var withDrawNum  = parseInt($("#withDrawNum").attr('data-value'));
            var withDrawAllNum  = parseInt($("#withDrawFreeNum").attr('data-value'));

            var credit           = $("#minMoney").attr("data-value");
            var freeAmount       = $("#freeAmount").attr("data-value");
            var withdrawFeeRate  = $("#withdrawFeeRate").attr("data-value");
            var balance          = $("#balance").attr("value");
            $("#cash_msg").html(credit+"元起提，请输入提现金额").show();

            function passwordValitate() {
                var password = $("#trading_password").val();
                var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
                //var pattern = /^[0-9]{6}$/i;

                if(!password) {
                    $("#trading_password_msg").html("请输入交易密码").show();
                    return false;
                } else if(password.length < 6){
                    $("#trading_password_msg").html("交易密码不少于6位").show();
                    return false;
                } else if(!password.match(pattern)){
                    $("#trading_password_msg").html("6到16位的字母及数字组合").show();
                    return false;
                } else {
                    $("#trading_password_msg").html("").hide();
                    return true;
                }
            }

            $(".m-btnselect").parent("div").click(function() {
                var select_btn = $(this).children(".m-btnselect");
                if(select_btn.next(".m-mlist").is(":hidden")){
                    select_btn.next(".m-mlist").show();
                }else{
                    select_btn.next(".m-mlist").hide();
                }
            });

            $("#bank_card_id li").click(function(){
                var select=$(this).html();
                var card_no=$(this).attr("data-target");
                var bank_id=$(this).attr("data-target1");
                $(this).parent(".m-mlist").siblings(".m-mselectshow").html(select);
                $(this).parent(".m-mlist").siblings(".m-mselectshow").attr("data-key",$(this).attr("data-key"));
                $("input[name='card_no']").val(card_no);
                $("input[name='bank_id']").val(bank_id);
                $("#bank_card_id_msg").html('');
            });
            // 金额验证
            function cashValitate() {
                var cash = $("#cash").val()
                if(!cash) {
                    $("#cash_msg").html(credit+"元起提，请输入提现金额").show();
                    return false;
                } else if(Number(cash) < credit){
                    $("#cash_msg").html("提现不小于"+ credit +"元").show();
                    $("#cash_rate").html("0.00元");
                    $("#last_cash").html("0.00元");
                    return false;
                } else if(Number(cash) > balance) {
                    $("#cash_msg").html("余额不足").show();
                    $("#cash_rate").html("0.00元");
                    $("#last_cash").html("0.00元");
                    return false;
                } else {
                    $("#cash_msg").html("").hide();
                    return true;
                }
            }
            $("#cash").keyup(function() {
                $(this).formatInput(/^[0-9]+([.][0-9]{0,2})?$/);
                var cash = $("#cash").val();
                var rate    = 0;
                var lastCash = cash;

                var desc = "(本月已经免费提现"+withDrawNum+"笔,剩余"+(withDrawAllNum-withDrawNum)+"次免费提现机会)";
                //需要手续费
                if(needHandling>0 || withDrawAllNum-withDrawNum<=0){
                    desc = "(本月已经免费提现"+withDrawNum+"笔,当前提现将收取"+needHandling+"元手续费)";
                    rate = needHandling;
                    lastCash = cash - rate;
                }
                if(cash>=credit){
                    $('#cash_rate').html(rate+"元 "+desc);             //手续费
                    $('#last_cash').html(lastCash+"元");//实际到账金额
                    $("input[name='real_cash']").val(lastCash);//实际到账金额
                }else{
                    $("#cash_rate").html("0.00元");
                    $("#last_cash").html("0.00元");
                }
                cashValitate(cash);
            });
            $('form').submit(function(){
                var card_no =$("input[name='card_no']").val();
                if(card_no == ''){
                    $("#bank_card_id_msg").html("请选择银行");
                    return false;
                }
                if(!cashValitate($('#cash').val())) return false;
                if(!passwordValitate($('#trading_password').val())) return false;

            });

            //页面加载完毕清空被填充的表单
            $("#cash").val('');
            $("#cash_rate").html('0.00元');
            $('#last_cash').html('0.00元');
            $("#trading_password").val('');
        //});
        })(jQuery);
    </script>
@endsection
