@extends('wap.common.wapBase')

@section('title', '用户提现')

@section('keywords', "{{env('META_KEYWORD')}}")

@section('description', "{{env('META_DESCRIPTION')}}")

@section('css')

@endsection

@section('content')
    <article>
        <form action="{{ URL('/withdraw/preview') }}" method="post" id="withDrawPreview">
            <section class="wap2-input-group">
                <div class="wap2-input-box2 mt1 clearfix">
                    <!-- 已绑定银行卡 -->
                    @if($withdrawCard)
                        <select class="wap2-input-3 bankCardList" name="card_no" style="height: 3.0rem;">
                            @foreach($withdrawCard as $card)
                                <option value="{{$withdrawCard[0]['card_no']}}" data-bank-id="{{$withdrawCard[0]['bank_id']}}">储蓄卡 （****&nbsp;{{ substr($withdrawCard[0]['card_no'],-4) }}) {{ App\Http\Models\Bank\CardModel::getBankName($withdrawCard[0]['bank_id']) }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="bank_id" value="{{$withdrawCard[0]['bank_id']}}">
                        <!-- 未绑定银行卡 -->
                    @else
                            <p>没有绑定提现银行卡</p>
                        {{--<section class="wap2-input-group w-q-mt">
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
                            <input type="hidden" name="bank_id" value="">
                            <div class="wap2-input-box">
                                <span class="wap2-input-icon wap2-input-icon8"></span>
                                <input type="text" class="m-input mb5" id="card_no" name="card_no" data-validate="card" placeholder="银行卡号" />
                            </div>
                        </section>
                        <!--  银行卡列表的弹出框 -->
                        <div class="box_alert">
                            <div class="mask"></div>
                            <div class="bomb_box">
                                <span class="close_box"><img src="/static/weixin/images/wap2/w-icon-close.png"/></span>

                                <div id="m-bank-new-main" class="w-bank">
                                    @foreach($authBanks as $authBank)
                                        <p class="mt15px"><label class="m-bank-label"><input type="radio" name="bank_card_str" data-value="1" data-cash="{{ $authBank['cash'] }}" data-validate="bank" value="{{ $authBank['bank_id'] }}" bname="{{ App\Http\Models\Bank\CardModel::getBankName($authBank['bank_id']) }}" class="paylimit">&nbsp;<img src="{{ assetUrlByCdn('/') }}static/images/bank-img/{{ $authBank['bank_id'] }}.gif" class="bank-img2"></label></p>
                                    @endforeach
                                </div>
                            </div>
                        </div>--}}
                    @endif
                </div>
            </section>
            <input type="hidden" id="canWithdrawAmount" name="canWithdrawAmount" value="{{ $userInfo['balance']}}">
            <input id="needHandling" name="needHandling" type="hidden" value="{{ $commission }}" >
            <input  id="minMoney" name="minMoney" type="hidden" value="{{$minMoney}}">
            <input id="withDrawNum" name="withDrawNum" type="hidden" value="{{ $withDrawNum['total'] }}">
            <input id="withDrawFreeNum" name="withDrawFreeNum" type="hidden" value="{{$maxFreeNum}}">
            <input id="withdrawFeeRate" name="withdrawFeeRate" type="hidden" value="{{env('WITHDRAW_FEE_RATE')}}">
            <section class="wap2-input-group">
                <div class="wap2-input-box2">
                    <p class="fr pr">
                        <input type="text" placeholder="最小提现金额为100元" class="wap2-input-cash withdraw " id="out_cash" name="withdraw_cash" maxlength="12" data-validate="cash" autocomplete="off" role-value="">元
                    </p>
                    <p>转出金额</p>
                </div>
            </section>
            <input name="commission" type="hidden" value="{{ $commission }}" class="form-control" readonly>
            <div class="withdraw-blance">账号可提金额 <span>{{ $userInfo['balance']}} 元</span></div>
            <input name="balance" type="hidden" value="{{ $userInfo['balance'] }}" class="form-control" readonly>
            <input name="real_name" type="hidden" value="{{ $userInfo['real_name'] }}" class="form-control" readonly>
            <input name="real_cash" type="hidden" value="" class="form-control" readonly>
            <input name="last_withdraw_num" type="hidden" value="" class="form-control" readonly>
            <section class="wap2-tip error">
                <p>
                    @if(Session::has('errors'))
                        {{  Session::get('errors') }}
                    @endif
                </p>
            </section>
            <section class="wap2-btn-wrap">
                @if($withdrawCard)
                    <input type="submit" class="wap2-btn wap2-btn-blue" id="withdrawCashMsg" value="申请提现">
                @else
                    <a href="javascript:" class="w-btn-gray"><span class=" pr15px">申请提现</span></a>
                @endif
            </section>
            <input type="hidden" name="_token" value="{{csrf_token()}}">

            <div class="withdraw-tip">T+1日到账,节假日顺延<i class="question"></i></div>

        </form>
    </article>

    <section class="wap2-pop" style="display: none;">
        <div class="wap2-pop-mask"></div>
        <div class="wap2-pop-main" >
            <div class="withdraw-pop">
                <h3>提现须知</h3>
                <p>1.单笔提现金额{{$minMoney}}元起；</p>
                <p>2.每位用户每自然月有{{$maxFreeNum}}次免费提现机会，超过{{$maxFreeNum}}次以后的每笔提现将收{{$handlingFree}}元手续费；</p>
                <p>3.用户发起提现申请并在平台审核之后，九斗鱼会在下一个工作日将钱转入您绑定的提现银行卡中（如遇周末或节假日，顺延至假期后的第一个工作日）；</p>
                <p>4.九斗鱼提现全部由第三方支付公司“网银在线”代付，单笔代付不超过5万，当提现金额超过5万，将分为多笔到账，请知晓；</p>
                <p>5.为保证用户资金安全，平台实行同卡进出，银行卡一经绑定，无法自行修改；</p><br>
                <p>如有疑问请咨询在线客服或拨打全国客服电话：400-6686-568</p>
            </div>
            <div class="withdraw-close">知道了</div>
        </div>
    </section>
@endsection

@section('jsScript')
    <script src="{{assetUrlByCdn('/static/weixin/js/jquery.mobileTips.js')}}"></script>
    <script type="text/javascript">

        $(document).ready(function () {
            $(".bankCardList").on("change",function () {
                $('.withdraw-img img').attr("src","/static/app/images/bank/"+$(this).find("option:selected").attr('data-bank-id')+".png");
                $('input[name=bank_id]').val($(this).find("option:selected").attr('data-bank-id'))
            })

            $(".withdraw-tip").click(function(){

                $(".wap2-pop").show();
                var h = $(".wap2-pop-main").outerHeight();
                var mt = parseInt(-h/2) + 'px';
                $(".wap2-pop-main").css({'top':'50%',"margin-top":mt});
            });
            $(".withdraw-close,.wap2-pop-mask").click(function(){
                $(".wap2-pop").hide();
            });

            var tip_error = $('.wap2-tip.error p');

            var credit = parseInt($("#minMoney").val());

            //超过一定次数,提现手续费,提现次数
            var needHandling = parseInt($("#needHandling").val());
            var withDrawNum  = parseInt($("#withDrawNum").val());
            var withDrawAllNum  = parseInt($("#withDrawFreeNum").val());
            var lastWithDrawNum = parseInt(withDrawAllNum-withDrawNum);
            var desc = "(本月已经免费提现"+withDrawNum+"笔,剩余"+(withDrawAllNum-withDrawNum)+"次免费提现机会)";
            if(lastWithDrawNum <= 0){
                $("input[name='last_withdraw_num']").val(0);//剩余可免费提现次数
            }else{
                $("input[name='last_withdraw_num']").val(lastWithDrawNum);//剩余可免费提现次数
            }
            $('#out_cash').keyup(function() {
                $(this).formatInput(/^[0-9]+([.][0-9]{0,2})?$/);

                if(isNaN($("#out_cash").val()) || $("#out_cash").val() == ''){
                    var cash = '';
                }else{
                    var cash = $("#out_cash").val();
                }
                var rate    = 0;
                var lastCash = cash;
                $(this).val(cash);
                //需要手续费
                if(needHandling>0 || withDrawAllNum-withDrawNum<=0){
                    desc = "(本月已经免费提现"+withDrawAllNum+"笔,当前提现将收取"+needHandling+"元手续费)";
                    rate = needHandling;
                    lastCash = cash - rate;
                    $('#cash_rate').html(desc);
                }
                //需要手续费
                if(cash >= credit){
                    $("#withdrawCashMsg").val("申请提现").removeClass("disabled").attr("submit-lock",'on');
                    $("input[name='real_cash']").val(lastCash);//实际到账金额
                }else{
                    $("#withdrawCashMsg").val("最小提现金额"+credit+"元").addClass("disabled").attr("submit-lock",'off');
                }

            });


            //提现预览页面表单验证
            $("#withDrawPreview").submit(function () {
                var lock = $("#withdrawCashMsg").attr("submit-lock");
                if( lock =='off'){
                    return false;
                }
                {{--<if condition="$autoStatus neq true">--}}
                    @if($withdrawCard)
                    var card_no = $.trim($("select[name=card_no]").val());
                    @else
                    var card_no = $.trim($("input[name=card_no]").val());
                    @endif
                    if (card_no == '') {
                    tip_error.html('提现银行卡不能为空');
                    return false;
                    }
                    var len = card_no.length;
                    if ((len == 19 || len == 16 || len == 18) && luhn(card_no)) {
                        tip_error.html('');
                    } else {
                        tip_error.html('请输入正确的银行卡号');
                        return false;
                    }
               /* </if>*/

                var canWithdrawAmount = $('#canWithdrawAmount').val();

                if (canWithdrawAmount == 0) {
                    tip_error.html('没有可提现的金额');
                    return false;
                }
                var cash = $("#out_cash").val();
                var pattern = /^[0-9]+(?:\.[0-9]+)?$/i;

                if ($.trim(cash) == '') {
                    tip_error.html('请填写提现金额');
                    return false;
                }
                if (!cash.match(pattern)) {
                    tip_error.html('金额必须为数字');
                    return false;
                }
                if (cash == '' || cash <= 0) {
                    tip_error.html('请输入正确的金额');
                    return false;
                }
                if ((cash) < (credit)) {
                    tip_error.html('最小提现金额为' + credit);
                    return false;
                }
                cash = parseFloat(cash);
                canWithdrawAmount = parseFloat(canWithdrawAmount);
                if ((cash) > (canWithdrawAmount)) {
                    tip_error.html('账户余额不够');
                    return false;
                }
            });

            $("input[name=bank_card_str]").click(function () {
                var name = $(this).attr('bname');

                $('#bname').text(name);
                $('.box_alert').hide();

                $("#withdrawCashMsg").val("申请提现").removeClass("disabled").attr("submit-lock",'on');

                $("input[name=bank_id]").val($(this).val());
                //ajaxrechargecash(cash,card_str)

            });
            var luhn = function(num){
                var str='';
                var numArr = num.split('').reverse();
                for(var i=0;i<numArr.length;i++){
                    str+= (i % 2 ? numArr[i] * 2 : numArr[i]);
                }
                var arr = str.split('');
                return  eval(arr.join("+")) % 10 == 0;
            }
        });
    </script>
@endsection
