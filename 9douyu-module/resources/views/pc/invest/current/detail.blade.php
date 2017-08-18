@extends('pc.common.layout')

@section('title','零钱计划项目详情')

@section('content')

    <div class="clearfix"></div>
<div class="wrap">
    <div class="t-center-nav"><a href="/">九斗鱼</a> > <a href="/project/index"> 我要出借 </a> > <a href="#" class="t-blue">零钱计划</a> </div>
    <!-- current block operate -->
    <div class="current-operate clearfix">
        <ins class="current-line"></ins>
        <!-- 零钱计划项目信息 -->
        <div class="current-data">
            <h1>零钱计划<small>每万元每日收益<span class="text-primary">{{ $interest }}</span>元</small></h1>
            <div class="current-rate">
                <p><big>{{ (float)$rateInfo['rate'] }}<span></span></big>%</p>
                <p><small>借款利率 </small></p>
            </div>
            <div class="current-data-num">
                <div class="current-last">可投金额<big>{{ number_format($freeAmount) }}</big>元</div>
                <div class="current-total">
                    <p><span>•</span>累计加入人数：{{ $investUserNum }}人</p>
                    <p><span>•</span>累计加入金额：{{ $investAmount }}元</p>
                </div>
            </div>

        </div>
        <!-- 右侧操作框 -->
        <div class="current-operate-box">
            <!--未登录-->
            @if($showStatus['is_login'] == 'off')
            <div class="current-unlogin">
                <p class="current-operate-account">账户余额<span><a href='/login'>登录</a>可见</span>
                </p>
                <div class="t-follow6 mb30">
                    <input type="text" name="cash" value="" placeholder="请输入出借金额">
                    <span>元</span>
                </div>
                <p><a href='/login' class="btn btn-red btn-large btn-block">立即出借</a></p>
                <!--<input type="submit" class="btn btn-red btn-large btn-block" value="立即出借">-->
                <p class="current-warm">温馨提示：网贷有风险，投资需谨慎。</p>
            </div>
            @elseif($showStatus['name_checked'] == 'off')

            <!-- 未实名认证 -->
            <p class="current-operate-status">投资前请先实名认证</p>
            <p><a href="/user/setting/verify" class="btn btn-red  btn-large btn-block">立即实名</a></p>
            <p class="current-warm">温馨提示：网贷有风险，投资需谨慎。</p>

            @elseif($showStatus['password_checked'] == 'off')
            <!-- 未设置交易密码和实名认证 -->
            <p class="current-operate-status">投资前请先设置交易密码</p>
            <p><a href="/user/setting/tradingPassword" class="btn btn-red  btn-large btn-block">设置交易密码</a></p>
            <p class="current-warm">温馨提示：网贷有风险，投资需谨慎。</p>
            @else
                <!-- 登录可投资 -->
            <p class="current-operate-account">账户余额<span>{{ number_format($balance,2) }}元</span>
             <a href="/recharge/index" class="link-active">充值</a>
             </p>
             <form action="/invest/current/confirm" method="post" id="investForm">
                 <p class="error project-tips">
                    @if(Session::has('msg'))
                         {{ Session::get('msg') }}
                    @endif
                 </p>
                 <div class="t-follow6">
                     <input type="text" name="cash" value="{{ Input::old('cash') }}" placeholder="请输入出借金额">
                     <span>元</span>
                 </div>

                 @if($addRate <= 0)
                     @if(!empty($bonus_list))
                     <div class="t-center-right-5"><p class="fl t-mt">加息券：</p>
                         <div class="t-select-box t-w250px">
                             <select name="bonus_id" id="bonus_id" class="bonus-items t-w">
                                 <option value="" data-value="0">请选择可使用的优惠券</option>
                                 @foreach($bonus_list as $bonus)
                                     <option value="{{ $bonus['id'] }}" data-value="{{ $bonus['rate'] }}">{{ $bonus['name'] }} 连续加息{{ $bonus['current_day'] }}天 ({{ $bonus['use_end_time'] }}前可用)</option>
                                 @endforeach
                             </select>
                         </div>
                     </div>
                     @endif
                 @else
                     <p class="x-current-addrate">{{ (float)$addRate }}%加息券生效中，今日年利率<span>{{ (float)$rateInfo['rate'] }}%+{{ (float)$addRate }}%</span></p>
                 @endif
                 <input type="hidden" name="projectFreeAmount" value="{{$freeAmount}}"/>
                 <input type="hidden" name="userAssessment" value="{{$assessment}}"/>
                 <input type="hidden" name="userBalance" value="{{ $balance }}" />
                 <input type="hidden" name="currentRate" value="{{ $rateInfo['rate'] }}"/>
                 <input type="hidden" name="addRate" id="rate" value="{{ $addRate }}"/>
                 <input type="hidden" name="investMax" id="investMax" value="{{ $investMax }}"/>
                 <input type="submit" class="btn btn-red btn-large btn-block" id="investSubmit" value="立即出借">
                 <p class="current-warm">温馨提示：网贷有风险，投资需谨慎。</p>
                 <input type="hidden" name="_token" value="{{ csrf_token() }}">
             </form>
            @endif

        </div>
    </div>


    <div class="current-summary">
        <ins></ins>
        <ul>
            <li>
                <h3>一元起投，稳赚收益</h3>
                <p>出借1万元，零钱计划30天能赚58元<br>享受浪漫双人电影</p>
            </li>
            <li>
                <h3>灵活变现，当日计息</h3>
                <p>灵活选择，取现出借自由随心</p>
            </li>
            <li>
                <h3>分散出借，更加安全</h3>
                <p>资金分散投向多种优质资产</p>
            </li>
        </ul>
    </div>

    <div class="Js_tab_box current-qa">
        <ul class="Js_tab t-question">
            <li class="cur">零钱计划详情</li>
            <li>常见问题？</li>
        </ul>
        <div class="js_tab_content">
            <div class="Js_tab_main t-question-main" style="display: block">
                @if(!empty($footerAtr['plan']))
                    {!! $footerAtr['plan'] !!}
                @else
                    <table class="table table-bordered">
                        <tr>
                            <td width="10%" class="tc">项目介绍</td>
                            <td>零钱计划是九斗鱼平台为出借人推出的优质小额债权组合；出借人加入后，资金将分散匹配多个优质小额债权项目，每日结算的利息也将自动复投到零钱计划中，让您享受资金不站岗天天拿收益。</td>
                        </tr>
                        <tr>
                            <td class="tc">收益</td>
                            <td>出借当日开始计息，转出当日不计息（次日0点结算当日收益）</td>
                        </tr>
                        <tr>
                            <td class="tc">投资限额</td>
                            <td>账户余额投资限额20万元（回款本息自动投资部分不计算在内）</td>
                        </tr>
                        <tr>
                            <td class="tc">转出限额</td>
                            <td>10万元+当日自动加入金额，且不超过当日转出总限额</td>
                        </tr>
                        <tr>
                            <td class="tc">收益计算</td>
                            <td>收益复投：例如您今天投资10000元，按7%借款利率，你次日0点将获得1.9元收益，次日将以10001.90元本金计算收益，如不进行转出，依次类推将一直享受利息复投的高收益。</td>
                        </tr>
                    </table>
                @endif
            </div>
            <dl class="Js_tab_main t-question-main">
                @if(!empty($footerAtr['ques']))
                    {!! $footerAtr['ques'] !!}
                @else
                    <dt>1.什么是零钱计划？</dt>
                    <dd>1零钱计划是九斗鱼平台为出借人推荐优质债权组合，1元即可投资，出借人可随时申请加入转出。</dd>
                    <dt>2.怎么加入零钱计划？</dt>
                    <dd>
                        <p>2.1、登录九斗鱼账户，选择零钱计划并输入加入金额，可将账户余额（整数部分）转入到零钱计划中，享受资金不站岗天天拿收益。</p>
                        <p>2.2、九斗鱼会将您当日回款的本息（整数部分）自动加入零钱计划，可享受时时刻刻拿收益。</p> </dd>
                    <dt>3.为什么我申请转出零钱计划的时候，可转出金额小于账户零钱计划总额？</dt>
                    <dd>
                        <p>可能是由于以下两种情况造成的：</p>
                        <p>3.1、单人单日转出限额为“10万+当日自动加入零钱计划的金额“（例如：今日用户回款后自动加入零钱计划20万，则今日转出限额为10+20=30万元），当日累计转出金额达到当日限额后则需要在次日继续转出。</p>
                        <p>3.2、为避免平台发生流动性风险，系统设置每日转出总额度为“全部用户持有的零钱计划总额的20%＋当日全部用户自动加入零钱计划的金额”，一旦当日转出额度用尽，用户无法申请转出，在次日开放新的转出额度后可重新申请转出零钱计划。</p>
                    </dd>
                    <dt>4.可以设置自动加入零钱计划吗？</dt>
                    <dd>每日回款金额（整数部分）自动加入零钱计划，账户余额暂不支持自动转入零钱计划。</dd>
                    <dt>5. 什么时候开始计息？什么时候可以提取收益？</dt>
                    <dd>加入当日计息，用户每日的收益在次日凌晨00:00计算并发放至零钱计划总额中，零钱计划总额大于0.01元时都可以申请转出，不受时间限制。</dd>
                    <dt>6.我加入零钱计划的收益怎么计算？</dt>
                    <dd><p>我们的收益每日计算，次日0点返还至零钱计划总额：</p>
                        <p>当日收益＝当日零钱计划总额（每日24点结算）＊借款利率／365</p>
                        <p>注意：每日收益四舍五入后不足0.01元不计入零钱计划账户。</dd>
                @endif
            </dl>


        </div>
    </div>

</div>
<div class="x-pop-wrap1">
    <div class="x-pop-mask1"></div>
    <div class="x-current-pop">
        <!-- 二维码图片 -->
        <span><img src="{{assetUrlByCdn('/static/images/activity/wapCode.png')}}" width="131" height="132"></span>
        <i></i>
        <ins></ins>
    </div>
</div>

<!-- 左侧广告位 -->
<script type="text/javascript">
    (function($){

        function ishidden(id){
            if($(id).size() && $(id).is(':visible')){
                $("body").css({"overflow":"hidden"});
            }else {
                $("body").css({"overflow":"auto"});
            }
        };


        $(document).ready(function(){
            // 零钱计划利率动画

            $(".x-nummove").click(function(){
                // 弹层
                $(".x-pop-wrap1").show();
                ishidden(".x-pop-wrap1");
            });
            $(".x-current-pop i,.x-pop-mask1").click(function(){
                $(".x-pop-wrap1").hide();
                ishidden(".x-pop-wrap1");
            })

            Groupbuy_Calculation_Time_Init();

        });

    })(jQuery);
</script>

<script type="text/javascript">
    (function($) {
        $(document).ready(function () {
            //投资收益计算
            $("input[name=cash]").bind("keyup blur", function () {
                if (!!$(this).attr("data-pattern")) {
                    $(this).formatInput(new RegExp($(this).attr("data-pattern")));
                } else {
                    $(this).formatInput(/(?!^0)^\d*$/);   //格式化，不能输入非数字，开头不能为零
                }
                return calInvestSum();
            });

            //零钱计划期加息券
            $("#bonus_id").change(function () {
                var rate = $(this).find("option:selected").attr('data-value');

                if (rate > 0) {
                    $("#useBonus").show();
                    $("#rate").val(rate);
                } else {
                    $("#useBonus").hide();
                    $("#rate").val('');
                }
                return calInvestSum();
            });

            /*$("#investSubmit").click(function(){
                return calInvestSum();
            });*/

            function calInvestSum() {
                if ( $.trim($("input[name=userAssessment]").val() ) =='' ) {
                    window.location.href='/user';
                    return false;
                }
                if ($.trim($("input[name=cash]").val()) == '') {
                    $(".project-tips").html('请输入出借金额');
                    return false;
                }

                var invest = $.toFixed($.trim($("input[name=cash]").val()));
                /*var investMin   = $.toFixed($("input[name=investMin]").val());*/
                var investMax   = $.toFixed($("input[name=investMax]").val());
                var userBalance = $.toFixed($("input[name=userBalance]").val());
                //var leftAmount   = $.toFixed($("input[name=leftAmount]").val());
                var currentRate = $.toFixed($("input[name=currentRate]").val());
                var rate = $.toFixed($("input[name=addRate]").val());

                var projectFreeAmount = $.toFixed($("input[name=projectFreeAmount]").val());

                if (isNaN(invest)) {
                    $(".project-tips").html('请输入正确出借金额');
                    return false;
                }

                if (invest > userBalance) {
                    $(".project-tips").html('账户余额不足');
                    return false;
                }

                if(invest > investMax) {
                    $(".project-tips").html('单人加入零钱计划总额不超过'+$.formatMoney(investMax)+'元');
                    return false;
                }

                if(invest > projectFreeAmount){

                    $(".project-tips").html('您当前可加入额度为'+$.formatMoney(projectFreeAmount)+'元');
                    return false;
                }

                var planInterest = invest * (currentRate + rate) / 100 / 365;
                $(".project-tips").html('当前投资额预期每日收益' + $.formatMoney(planInterest.toFixed(2)) + '元');

                return true;
            }

            $("#investForm").submit(function(){

                return calInvestSum();

            });

        });
    })(jQuery);
</script>

@endsection
