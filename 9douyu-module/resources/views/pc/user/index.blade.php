@extends('pc.common.layout')

@section('title','用户中心')

@section('content')
<div class="m-myuser">
    <!-- account begins -->
    @include('pc.common/leftMenu')
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <div class="m-content grayborder" id='user-content'>
        <div class="t-showbox hidden">
            <dl class="t-view">
                <dt>HI,  {{ $user_info['real_name'] }}</dt>
                <dd>用户名：{{ $user_info['phone'] }}</dd>
            </dl>
            @if(!empty($user_info['assessment_type']))
            <dl class="t-view1 js-poshytip t-view1-risk">
            @else
            <dl class="t-view1 js-poshytip">
            @endif
                <dt>
                    <span title="您已绑定手机 " class="t1-icon23 iconfont">&#xe61f;</span>

                    <!-- <span title="您已实名认证 " class="t-icon2"></span> -->
                    @if(!empty($user_info['real_name']))
                    <span title="您已实名认证 " class="t1-icon20 iconfont">&#xe613;</span>
                    @else
                        <span title="您还未实名认证 <a href='/user/setting/verify' style='color:#1468ec'>点击认证</a>" class="t1-icon20 iconfont t1-icon-gray">&#xe613;</span>
                    @endif

                    <!-- <span title="您已设置支付密码 " class="t-icon3"></span> -->
                    @if($user_info['password_hash'] == $user_info['trading_password'] || empty($user_info['trading_password']))
                    <span title="还未设置支付密码 " class="t1-icon16 iconfont t1-icon-gray">&#xe61b;</span>
                    @else
                    <span title="您已设置支付密码 " class="t1-icon16 iconfont">&#xe61b;</span>
                    @endif

                    @if(!empty($user_info['assessment_type']))
                        <span title="" class="t1-icon20 iconfont" id="evaluate1">&#xe67b;</span>
                    @else
                        <span title="" class="t1-icon20 iconfont t1-icon-gray" id="evaluate">&#xe67b;</span>
                    @endif

                </dt>
                <dd>安全等级:
                   <span class="t-m">中</span>
                </dd>
                <dd class="sec">评估结果:
                   <span class="t-m">{{$user_info['assessment_type']}}</span>

                   <a href="/user/riskAssessment" class="riskA-btn">重新评估</a>

                </dd>
            </dl>
            <dl class="t-view2">
                <dd>累计收益：{{ number_format($user_info['total_interest'],2) }}元</dd>
            </dl>

            <dl class="t-view3">
                <dt><img src="{{assetUrlByCdn('/static/images/new/t1-icon-coupon.png')}}" width="41" height="48" alt="我的优惠券"></dt>
                <dd>我的优惠券<br/>{{ $total_bonus }}张</dd>
            </dl>

        </div>
        <div class="t-showbox t-mt9px">
            <h3 class="t-view4">账户总资产<span>{{ number_format($user_info['total_amount'],2) }}元</span></h3>
            <ul class="t-view5">
                <li ><a href="javascript:void(0);" data-target="12" class="t-blue t-rdl7px" >账户余额 {{ number_format($user_info['balance'],2) }}元</a></li>
                <li><a href="javascript:void(0);" data-target="11" >优选项目  {{ number_format($project_account['total_amount'],2) }}元</a></li>

                <li><a href="javascript:void(0);"  data-target="6" class="t-br0px">零钱计划 {{ number_format($current_account['cash'],2) }}元</a></li>


            </ul>

            <!-- 账户余额  100,600,00元 -->
            <div class="t-view12"  >
                <div class="t-view13">
                    <p><span class="t-icon5"></span><strong>可用余额</strong><i>{{ number_format($user_info['balance'],2) }}元</i></p>
                </div>
                <div class="t-view13">
                    <p><a href="/recharge/index" class="btn btn-red btn-small t-mr30px">充值</a><a href="/pay/withdraw" class="btn btn-blue btn-small">提现</a></p>
                </div>
            </div >


            <!-- 优选项目  1,100,600,00元 -->
            <div class="t-view11"  style="display: none" >
                <div class="t-view9">
                    <p class="t-view9-1">待收收益<br/><span>{{ number_format($project_account['total_amount_interest'],2) }}元</span></p>
                    <p class="t-view9-2">待收本金<br/><span>{{ number_format($project_account['total_amount_principal'],2) }}元</span></p>
                </div>
                <div class="t-view10">
                    <div class="t-view10-1">

                        <div class="w-zc">
                            <div id="canvas-holder">
                                <canvas id="chart-area" width="126" height="126"/>
                            </div>
                        </div>
                    </div>
                    <div class="t-view10-2">
                        <table class="t-view10-3">
                            <tr>
                                <td class="t-l"><span class="t-color1">●</span>九省心</td>
                                <td>{{ number_format($project_jsx['principal'],2) }}元</td>
                                <td width="39%">{{ number_format($project_jsx['interest'],2) }}元</td>
                            </tr>
                            <tr>
                                <td class="t-l"><span class="t-color2">●</span>九安心</td>
                                <td>{{ number_format($project_jax['principal'],2) }}元</td>
                                <td>{{ number_format($project_jax['interest'],2) }}元</td>
                            </tr>
                            <tr>
                                <td class="t-l"><span class="t-color5">●</span>闪电付息</td>
                                <td>{{ number_format($project_sdf['principal'],2) }}元</td>
                                <td>投资当日返息</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 零钱计划  100,600,00元 -->
            <div class="t-view6"  style="display: none"  >
                <div class="t-view13">
                    <p class="t-view8-1">昨日收益<span style="float:none;background: none;">{{ number_format($current_account['yesterday_interest'],2) }}元</span></p>
                    <p class="t-view8-2">累计收益<span style="float:none;background: none;">{{ number_format($current_account['interest'],2) }}元</span></p>
                </div>
                <span class="t-view13">
                    <p class="t-view8-1"><a href="/project/current/detail" class="btn btn-red btn-small t-mr20px">买入</a><font style="color:#616161;font-size:12px;">资金从账户余额转入到零钱计划</font></p>
                    <p class="t-view8-2"><a class="btn btn-blue btn-small t-mr20px" data-target="modul2" >卖出</a><font style="color:#616161;font-size:12px;">资金从零钱计划转出到账户余额</font></p>
                </div>
            </div>

        </div>

    </div>


    <!-- 转出弹层new -->
    <div id="lay_wrap1"  class="layer_wrap js-mask" data-modul="modul2"  style="display:none;">
        <div class="Js_layer_mask layer_mask" data-toggle="mask" data-target="js-mask"></div>
        <div class="Js_layer layer">
            <div class="layer_title">转出零钱计划<a href="javascript:;" class="layer_close Js_layer_close m-closeblackbg" data-toggle="mask" data-target="js-mask"></a></div>
            <dl class="m-current-turnout">
                <dt>零钱计划总额</dt>
                <dd><span>
             {{ $current_account['cash'] }}             </span> 元</dd>
                <dt>转出金额</dt>
                <dd><input type="text" name="investOutCash" id="cash" class="form-input" value=""  autocomplete="off" placeholder="请输入转出金额" /> 元</dd>
                <dt>交易密码</dt>
                <dd><input type="password" id="trading_password" autocomplete="off" name="tradingPassword" placeholder="交易密码" class="form-input" value=""/>
                    <p class="addredcolor f12" id="error_msg"> </p>
                    <input type="hidden" name="balance" value="{{ $current_account['cash'] }}">
                    <input type="hidden" name="maxOut" value="100000">
                    <p><input type="submit" id="investOutForm"  class="btn btn-blue btn-block" value="确认转出"></p>
                </dd>
            </dl>
        </div>
    </div>

    <!-- 弹层 -->
    <div id="turn-wap" class="layer_wrap js-mask">
        <div class="Js_layer_mask layer_mask" data-toggle="mask" data-target="js-mask"></div>
        <div class="Js_layer layer">
            <div class="layer_con">
                <div class="t-v-bj t-turn-bj">
                    <p>转出失败！</p>
                </div>
                <em class="t-turn-p"></em>
                <a  class="btn btn-blue btn-block t-alert-btn1" data-toggle="mask" data-target="js-mask">关闭</a>

            </div>
        </div>
    </div>


    <!-- 风险提示书 -->
    <div id="lay_wrap3"  class="layer_wrap js-mask" data-modul="modul3"  @if(!empty($user_info['assessment_type']))style="display:none;" @else style="display:block;" @endif>
        <div class="Js_layer_mask layer_mask" data-toggle="mask" data-target="js-mask-bak"></div>
        <div class="Js_layer layer">
            <div class="layer_title">风险提示书<a href="javascript:;" class="layer_close Js_layer_close m-closeblackbg" data-toggle="mask" data-target="js-mask" style='display:none;'></a>

            </div>
            <div class="layer_con riskAssessment-con">
                <p><strong>重要提示：</strong></p>
                <p>1、由于相关风险因素可能导致您的本金及收益全部或部分损失，因此，在您选择投资本网站发布的项目前，请仔细阅读本风险提示、网站公示信息、项目信息、电子合同（注册协议、借款合同、债权转让合同、产品交易类合同等）、第三方保障机构提供的电子担保函、承诺函等公示信息等。本风险提示书未涵盖全部风险因素，您仍需对其他可能存在的风险因素自行进行了解与评估。<br>
                2、“九斗鱼”平台不保证您的本金及收益，您可能损失全部本金且无法取得任何收益。<br>3、请您谨慎投资，如果您无法接受，请立即停止后续注册、投资操作。如您通过网络操作确认等方式继续进行交易，则视为您已仔细阅读本风险提示书并愿意自行承担由此产生的本息损失及风险。<br>4、投资前，请您先进行风险承受能力评估，并根据您的评估结果选择您可以投资的项目。评级结果所对应的可投项目如下：<br>（1）保守型，对应网站上标示为“保守型”的产品；<br>（2）稳健型，对应网站上标示为“保守型”、“稳健型”的产品；<br>（3）平衡型，对应网站上标示为“保守型”、“稳健型”、“平衡型”的产品；<br>（4）积极型，对应网站上标示为“保守型”、“稳健型”、“平衡型”、“积极型”的产品；<br>（5）激进型，对应网站上标示为“保守型”、“稳健型”、“平衡型”、“积极型、激进型”的产品；</p><br>
                <p><strong>尊敬的用户：</strong></p>
                <p>1、在您成为“九斗鱼”平台注册出借人前，请确认您具备以下条件，<br>（1）拥有非保本类金融产品投资的经历并熟悉互联网；<br>（2）向网络借贷信息中介机构提供真实、准确、完整的身份等信息；<br>（3）出借资金为来源合法的自有资金；<br>（4）了解融资项目信贷风险，确认具有相应的风险认知和承受能力；<br>（5）自行承担借贷产生的本息损失；<br>
                2、若您不具备以上条件，请您立即停止注册，并勿通过“九斗鱼”平台开展网络借贷活动。如果您仍继续后续操作进行注册程序，则视为您已确认您具备上述条件并自愿履行上述义务。<br>
                3、在您在“九斗鱼”平台进行出借（投资）过程中，可能会面临多种风险因素，包括但不限于借款人违约风险、第三方担保或第三方保障机构风险、政策风险、延期风险、信息传递风险、不可抗力风险。请您认真阅读本风险提示书，并依据自身风险承受能力、财务状况及投资理财经验自行决定是否对 “九斗鱼”平台发布项目进行投资。鉴于风险因素存在多样性、不确定性，本风险提示未包括所有风险，仅供您参考，请您谨慎投资，独立判断。<br>
                4、借贷关系的第一个风险是，借款人/出让人可能违约，因其自身财务状况紧张、投资失败、经营恶化等各种可能因素导致借款人/出让人无法按时足额支付本息/回购款，并且该违约将可能导致您的投资本息无法得到偿还。<br>
                 5、“九斗鱼”平台上发布的借款项目，通常会有第三方专业担保公司担保或第三方准入机构（以下统称“第三方保障机构”）提供债权回购/买断等方式保障出借人的资金安全。但是，“九斗鱼”平台不保证出借人的投资本息均能得到偿还。如借款人逾期还款，第三方保障机构破产、依法撤销或遭遇行业限制及因其他因素导致无法实现资金本息保障的，则出借人的投资本息将可能无法得到偿还。<br>
                 6、“九斗鱼”平台仅为借贷项目参与各方的借贷、担保、资金安全保障提供居间服务。“九斗鱼”平台自身不提供任何担保。“九斗鱼”平台在出借人与借款人/出让人或其它债权参与方之间的债权债务关系中不担任任何担保人或者保证人的角色。“九斗鱼”平台在服务过程中，任何文件、声明、说明、规则等均不应解释为“九斗鱼”平台提供任何形式的担保。 <br>
                 7、借款人/出让人提出融资申请后，“九斗鱼”平台会对借款人/出让人申请的拟发布融资信息进行审核。目前国内信用征信体系尚不完善，“九斗鱼”平台不能完全保证发布信息的真实性、有效性、完整性。平台网站提供的信息资料仅供参考，最终是否进行投资，需要出借人、出借人综合考虑自身投资经验、风险承受能力、相关法律法规、金融知识及对网络金融现状的了解，进行独立判断，并自行承担由此产生的风险。<br>
                 8、鉴于网络金融的特殊性，因技术故障、支付故障（银行、网关运营商、电信运营商服务技术障碍）、网络数据传输故障、网络安全、有权机关管制或限制等因素或其他不可抗力因素有可能导致出借人的投资本息延迟或损失。“九斗鱼”平台不担保服务不会中断，也不担保服务的及时性和/或安全性。系统因相关状况无法正常运作，使会员无法使用任何“九斗鱼”平台服务或使用任何“九斗鱼”平台服务时受到任何影响或损失的，“九斗鱼”平台对会员或任何第三方均不负任何责任。<br>9、出借人有可能面临因国家法律政策的出台或重大变化而遭受本金及收益损失。<br>
                 10、出借人在“九斗鱼”平台进行交易过程中，请确保<br>
                 （1）投资资金来源合法且有权进行出借处分；<br>
                 （2）未使用非法资金进行投资或在“九斗鱼”平台洗钱；<br>
                 （3）未使用非自有资金（包括但不限于银行贷款、从他人处筹措的资金等）进行投资；<br>
                 （4）与借款人/出让人串通进行虚假的融资和投资。否则，因此所引发的任何纠纷均由您自行负责解决并承担相应责任。<br>
                 11、“九斗鱼”平台发布的借贷项目可提前还款，借款人可在借款期间任何时候通过“九斗鱼”平台的提前还款功能提前偿还全部剩余借款。请您认真阅读电子借款合同中的提前还款条款，以合理判断预期收益。</p><br>
                 <p>星果时代信息技术有限公司</p>
            </div>
            <div class="riskAssessment-tips">
                <p><input type="checkbox" name="checkbox" id="checkbox"><label for="checkbox">本人已经认真阅读，完全理解，认可并接受以上全部内容。</label></p>
                <p><a href="javascript:;" class="btn btn-blue btn-small disabled" id="checkbox-link">开始评估</a></p>
            </div>
        </div>
    </div>

    <!-- account ends -->
    <div class="clearfix"></div>
</div>

<script src="{{assetUrlByCdn('/static/js/pc2.js')}}" type="text/javascript"></script>
<script src="{{assetUrlByCdn('/static/js/pc2/excanvas.js')}}" type="text/javascript"></script>
<script src="{{assetUrlByCdn('static/js/pc2/Chart.js')}}" type="text/javascript"></script>
<script src="{{assetUrlByCdn('static/js/pc2/echarts-all.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    (function($){
        /*左边导航*/
        $(".m-myuser-nav ul li").click(function(){
            if($(this).index()>0){
                $(this).addClass("checked").siblings().removeClass("checked");
                $(this).find("a").addClass("checkeda").parent("li").siblings().find("a").removeClass("checkeda")
            }
        });

    })(jQuery);

</script>


<script type="text/javascript">
    /*饼状图*/
    var doughnutData = [
        {
            value:{{ $jsx_total }},
            color: "#00abee",
            highlight: "#1fb3ed",
            label: "九省心"
        },
        {
            value:{{ $jax_total }},
            color:"#fe5353",
            highlight: "#FF5A5E",
            label: "九安心"
        },
        {
            value:{{ $sdf_total }},
            color: "#dd4cee",
            highlight: "#ec6ffb",
            label: "闪电付息"
        }
    ];

</script>
<script type="text/javascript">
    $(function(){

        $(".t-view5 li a").click(function(){
            var filter=$(this).attr('data-target');
            $(".t-view5 li a").each(function(i){
                if(filter==$(this).attr('data-target')){
                    $(this).addClass('t-blue');
                }else{
                    $(this).removeClass('t-blue');
                }
            });
            if(filter==12){
                $(".t-view12").show();
                $(".t-view11").hide();
                $(".t-view6").hide();
            }else if(filter==11){
                $(".t-view12").hide();
                $(".t-view11").show();
                $(".t-view6").hide();
                var ctx = document.getElementById("chart-area").getContext("2d");
                window.myDoughnut = new Chart(ctx).Doughnut(doughnutData, {responsive : true});
            } else{
                $(".t-view12").hide();
                $(".t-view11").hide();
                $(".t-view6").show();
            }
        });

    });

    $(function(){
        $(".js-poshytip span:not(#evaluate)").poshytip({
            alignY: 'bottom',
            showTimeout: 100
        });

        $('#evaluate1').poshytip({
            content:'您已参与评估',
            alignY: 'bottom',
            showTimeout: 100
        })

        $('#evaluate').poshytip({
            content:'您还未进行<a href="javascript:;" data-target="modul3" class="blue">风险评估</a>',
            alignY: 'bottom',
            showTimeout: 100
        })
    });

</script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    (function($){
        $("#investOutForm").click(function() {
            var cash  = $.toFixed($.trim($("input[name=investOutCash]").val()));
            var balance  = $.toFixed($.trim($("input[name=balance]").val()));
            var tradingPassword  = $.trim($("input[name=tradingPassword]").val());
            var maxOut  = $.toFixed($.trim($("input[name=maxOut]").val()));

            if( cash<0 || cash==''){
                $("#error_msg").html("请输入正确金额！");
                $("#cash").focus();
                $("#error_msg").show();
                return false;
            }

            if( cash>balance ){
                $("#error_msg").html("转出金额不能超过零钱计划总资产！");
                $("#error_msg").show();
                return false;
            }
            var pattern = /^(?![0-9]+$)(?![a-z]+$).{6,16}$/i;
            if( tradingPassword.length == 0 ) {
                $("#error_msg").html("请输入交易密码！");
                $("#error_msg").show();
                return false;
            }
            if( !tradingPassword.match(pattern) ){
                $("#error_msg").html("请输入正确格式的交易密码！");//6到16位的字母及数字组合
                $("#error_msg").show();
                return false;
            }
            //提交成功后防止表单再次提交(前端限制)
            $("#investOutForm").attr("disabled","1");

            $.ajax({
                url     : '/invest/current/doInvestOut',
                type    : 'POST',
                dataType: 'json',
                data    : {cash:cash,trading_password:tradingPassword},
                success : function(data) {
                    if(data.status) {
                        $(".t-v-bj p").html("转出成功");
                        $(".t-v-bj").removeClass("t-turn-bj1");
                        $(".t-v-bj").addClass("t-turn-bj");
                    } else {
                        //显示失败
                        $(".t-v-bj p").html("转出失败");
                        $(".t-v-bj").removeClass("t-turn-bj");
                        $(".t-v-bj").addClass("t-turn-bj1");

                    }
                    //$(".t-turn-p").html(data.msg);
                    $("#lay_wrap1").hide();
                    $("#turn-wap").mask({"layerWidth":"600"});
                },
                error   : function(msg) {
                    alert('操作失败，请重试！');
                }
            });
        });

        $(".m-closeblackbg").click(function(){
            closecontrollist();
        });

        $(".t-alert-btn1").click(function(){
            window.location.href='/user';
        });
        /*关闭弹框*/
        function closecontrollist(){
            window.location.href='/user';
        }
        $(document).ready(function(){
            //回款计划层
            $(".assign").nm();
            //            输入框清空
            $("a[data-target='modul2']").click(function(){
                $("#cash,#trading_password").val('');
            })


            // 评估
            $('#checkbox').click(function(){
                if($(this).prop('checked')){
                    $('#checkbox-link').attr("href","/user/riskAssessment");
                    $('#checkbox-link').removeClass('disabled');
                }else{
                    $('#checkbox-link').addClass('disabled');

                };

            })

        });
        $('#evaluate').click(function(){
           $('#lay_wrap3').css('display','block');
        })
    })(jQuery);

</script>

@endsection
