@extends('pc.common.layout')

@section('title', '充值')

@section('content')

    <div class="m-myuser">
        <!-- account begins -->
        <div class="m-myuser-nav">
            <ul>
                <li class="m-title">我的账户</li>
                <li class="m-first"><a href="/user"  class="checkeda"><i class="t1-icon22 iconfont">&#xe615;</i>账户总览</a></li>
                <li class="m-fifth"><a href="/user/fundhistory"   ><i class="t1-icon18 iconfont">&#xe611;</i>资金记录</a></li>
            </ul>
        </div>


        <div class="m-content grayborder">
            <div class="m-pagetitle hidden"><p class="fl">我要充值</p><p class="fr t-racharge"><span></span><a href="javascript:;" id="t-recharge-notice" data-target="modul1">充值须知</a></p></div>
            <div class="t-r-showbox hidden">
                <form action="/user/recharge/doRecharge.html" method="post" id="rechargeForm">
                    <div class="fl t-recharge5">
                        <p class="t-recharge2"><span>充值金额</span><input type="text" id="cash" name="cash" autocomplete="off" maxlength="8" class="form-input t-recharge-input"> 元</p>
                        <p class="tips mt5 t-recharge-tip" id="cash-tips" style="width: 233px; position: static; display: none;"></p>
                    </div>
                    <div class="fr t-recharge3">
                        <p>当前可用余额：<span>0.92</span>元</p>
                        <p id="lastBalance" style="display:none;">充值后余额：<span class="fontorange t-red" balance="0.92">0.92</span>元</p>
                    </div>
                    <div class="clear"></div>
                    <p class="t-recharge4"><span></span>温馨提示：1元起充,单笔充值限额100000元</p>
                    <div class="t-recharge6">
                        <p class="t-recharge6-1">充值方式</p>
                        <ul class="recharge-method t-recharge-nav">
                            <li class="" data-type="1">网上银行充值<span></span></li>
                            <li data-type="2" class="t-selected">快捷支付<span></span></li>
                        </ul>
                    </div>
                    <div class="clear"></div>
                    <!-- 网上银行充值 -->
                    <div class="recharge-bank-box t-recharge8" style="display: none;">
                        <p class="t-recharge7"><span></span>请确保已选择的银行已开通网上支付功能，不支持信用卡充值。</p>
                        <p id="bank-limit" style="width:580px; color:red"></p>
                        <div class="t-recharge9">
                            <p class="t-recharge6-1 t-recharge6-2">选择银行</p>
                            <div class="t-recharge-bank">
                                <ul class="recharge-bank hidden t-recharge-bank1" data-type="1">
                                    <li bvalue="1_0_1_1"><img src="{{assetUrlByCdn('/static/images/new/1.gif')}}" width="136" height="50" alt="中国工商银行"><span></span></li>
                                    <li bvalue="1_0_2_2"><img src="{{assetUrlByCdn('/static/images/new/2.gif')}}" width="136" height="50" alt="中国农业银行"><span></span></li>
                                    <li bvalue="1_0_3_3"><img src="{{assetUrlByCdn('/static/images/new/3.gif')}}" width="136" height="50" alt="中国银行"><span></span></li>
                                    <li bvalue="1_0_4_4"><img src="{{assetUrlByCdn('/static/images/new/4.gif')}}" width="136" height="50" alt="中国建设银行"><span></span></li>
                                    <li bvalue="1_0_5_5"><img src="{{assetUrlByCdn('/static/images/new/5.gif')}}" width="136" height="50" alt="交通银行"><span></span></li>
                                    <li bvalue="1_0_6_6"><img src="{{assetUrlByCdn('/static/images/new/6.gif')}}" width="136" height="50" alt="招商银行"><span></span></li>
                                    <li bvalue="1_0_7_7"><img src="{{assetUrlByCdn('/static/images/new/7.gif')}}" width="136" height="50" alt="浦发银行"><span></span></li>
                                    <li bvalue="1_0_8_8"><img src="{{assetUrlByCdn('/static/images/new/8.gif')}}" width="136" height="50" alt="中国民生银行"><span></span></li>
                                    <li bvalue="1_0_9_9"><img src="{{assetUrlByCdn('/static/images/new/9.gif')}}" width="136" height="50" alt="兴业银行"><span></span></li>
                                    <li bvalue="1_0_10_10"><img src="{{assetUrlByCdn('/static/images/new/10.gif')}}" width="136" height="50" alt="中国光大银行"><span></span></li>
                                    <li bvalue="1_0_11_11"><img src="{{assetUrlByCdn('/static/images/new/11.gif')}}" width="136" height="50" alt="北京银行"><span></span></li>
                                    <li bvalue="1_0_12_12"><img src="{{assetUrlByCdn('/static/images/new/12.gif')}}" width="136" height="50" alt="广东发展银行"><span></span></li>
                                    <li bvalue="1_0_13_13"><img src="{{assetUrlByCdn('/static/images/new/13.gif')}}" width="136" height="50" alt="中信银行"><span></span></li>
                                    <li bvalue="1_0_14_14"><img src="{{assetUrlByCdn('/static/images/new/14.gif')}}" width="136" height="50" alt="中国邮政储蓄银行"><span></span></li>
                                    <li bvalue="1_0_15_15"><img src="{{assetUrlByCdn('/static/images/new/15.gif')}}" width="136" height="50" alt="华夏银行"><span></span></li>
                                    <li bvalue="1_0_16_16"><img src="{{assetUrlByCdn('/static/images/new/16.gif')}}" width="136" height="50" alt="上海银行"><span></span></li>
                                    <li bvalue="1_0_17_17"><img src="{{assetUrlByCdn('/static/images/new/17.gif')}}" width="136" height="50" alt="平安银行"><span></span></li>
                                    <li bvalue="1_0_19_19"><img src="{{assetUrlByCdn('/static/images/new/19.gif')}}" width="136" height="50" alt="南京银行"><span></span></li>
                                    <li bvalue="1_0_20_20"><img src="{{assetUrlByCdn('/static/images/new/20.gif')}}" width="136" height="50" alt="杭州银行"><span></span></li>
                                    <li bvalue="1_0_21_21"><img src="{{assetUrlByCdn('/static/images/new/21.gif')}}" width="136" height="50" alt="宁波银行"><span></span></li>
                                </ul>
                            </div>
                            <p class="bank-tips t-recharge-tip" style="width: 233px; display: none;"></p>
                            <p id="ie-tips" class="t-recharge7 t-recharge7-1" style="display: none;"><span></span>此银行可能暂不支持该浏览器充值，建议您使用绑定银行卡充值或更换浏览器（如IE或360等浏览器）充值。</p>
                            <input name="recharge_method" type="hidden" value="1">
                            <input name="bank_card_str" type="hidden" value="3_1_34003_1">
                            <input type="submit" class="btn btn-red btn-large t-recharge-btn" value="充  值">
                        </div>
                    </div>


                    <!-- 快捷支付  -->

                    <div class="recharge-bank-box t-recharge10 hidden" style="display: block;">
                        <p id="t-recharge-method-txt" class="t-recharge7"><span></span>仅支持绑定开户名为贾艳兰的借记卡（无需开通网银），且单笔最小充值金额为1元。</p>
                        <p class="t-recharge11">每个用户只能选择一张银行卡作为快捷支付卡，一旦支付成功后，将只能提现到到该快捷卡。</p>
                        <p id="bank-limit" style="width:580px; color:red"></p>
                        <div class="t-recharge9">
                            <p class="t-recharge6-1 t-recharge6-2">选择银行</p>
                            <span id="maxCash" data-value="100000"></span>
                            <div class="t-recharge-bank">
                                <span id="bindBankUser"></span>                            <ul class="recharge-bank hidden t-recharge-bank1" data-type="2">

                                    <li bvalue="3_1_34003_1" class="t-selected t-recharge-num" data-value="1" data-cash="100000"><img src="{{assetUrlByCdn('')}}/static/images/bank-img/1.gif" width="136" height="50" class="fl"><i>尾号<em>0912</em></i><span class="t-icon"></span></li>                            </ul>
                            </div>
                            <p class="bank-tips t-recharge-tip" style="width: 233px; display: none;"></p>
                            <div class="clear"></div>
                            <div class="bank-loupe-box t-recharge12" style="display:none">
                                <p id="bank-card-large" class="t-recharge12-1"></p>
                                <p class="t-recharge2"><span>银行卡号</span><input type="text" name="card_no" class="form-input t-recharge-input1" placeholder="此处输入银行卡号"></p>
                                <p id="bank-card-tips" style="width:233px"></p>
                            </div>
                            <input name="recharge_method" type="hidden" value="2">
                            <input name="bank_card_str" type="hidden" value="3_1_34003_1">
                            <input type="submit" class="btn btn-red btn-large t-recharge-btn" value="充  值">
                        </div>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
            </div>
        </div>

        <!--充值确认new -->
        <div class="layer_wrap js-mask" id="t-box-confirm" data-modul="modul2" style="display: none;">
            <div class="Js_layer_mask layer_mask" data-toggle="mask" data-target="js-mask"></div>
            <div class="Js_layer layer">
                <div class="layer_title">充值确认<a href="javascript:;" class="layer_close Js_layer_close" data-toggle="mask" data-target="js-mask"></a></div>
                <em class="t-alert-p">请在新打开的网上银行支付页面完成充值操作</em>
                <a href="#" data-toggle="mask" data-target="js-mask" class="btn btn-red btn-large t-alert-btn2">充值成功</a><a href="#" data-toggle="mask" data-target="js-mask" class="btn btn-yellow btn-large t-alert-btn3">我不想充值了</a>
                <div class="t-r-qustion"><em></em><a href="http://www.sobot.com/chat/pc/index.html?sysNum=54037ae382a141c8b7fa69f402a99b7c" target="_blank" class="t-r-qustion1">我遇到了问题</a></div>
            </div>
        </div>

        <!-- 充值须知new-->
        <div class="layer_wrap js-mask" data-modul="modul1" style="display: none;">
            <div class="Js_layer_mask layer_mask" data-toggle="mask" data-target="js-mask"></div>
            <div class="Js_layer layer" style="margin-left: -340px; margin-top: -240px; width: 680px;">
                <div class="layer_title">充值须知<a href="javascript:;" class="layer_close Js_layer_close" data-toggle="mask" data-target="js-mask"></a></div>
                <dl class="t-alert-know">
                    <dt>1.</dt>
                    <dd>在支付页面完成充值后，请点击"返回商户"连接，不要直接关闭支付页面窗口，否则可能会造成充值金额延后到账；若充值金额未及时到账，请联系客服；</dd>
                    <dt>2.</dt>
                    <dd>单笔充值金额1元起，每日的充值限额依据各银行限额为准；</dd>
                    <dt>3.</dt>
                    <dd>严禁利用充值功能进行信用卡套现、转账、洗钱等行为，一经发现，资金将退回原卡并封停账号30天；</dd>
                    <dt>4.</dt>
                    <dd>账户资金每自然月有4次免费提现机会，超过4次以后的每笔提现将收5元手续费。</dd>
                    <dt>5.</dt>
                    <dd>点击充值按钮，表示您已经仔细阅读并同意以上资金管理规定条款。</dd>
                </dl>

                <a href="#" data-toggle="mask" data-target="js-mask" class="btn btn-blue btn-large t-alert-btn t-mt30px">我知道了</a>

            </div>
        </div>


        <!-- account ends -->
        <div class="clearfix"></div>
    </div>

@endsection
