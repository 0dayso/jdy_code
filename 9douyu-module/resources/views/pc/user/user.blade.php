@extends('pc.common.layout')

@section('title','用户中心')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="m-myuser">
    <!-- account begins -->
    <div class="m-myuser-nav">
        <ul>
            <li class="m-title">我的账户</li>
            <li class="m-first   checked "><a href="/user"  class="checkeda"><i class="t1-icon22 iconfont">&#xe615;</i>账户总览</a></li>
            <li class="m-fifth     "><a href="/user/fund_history.html"   ><i class="t1-icon18 iconfont">&#xe611;</i>资金记录</a></li>
        </ul>
    </div>


    <div class="m-content grayborder">
        <div class="t-showbox hidden">
            <dl class="t-view">
                <dt>HI,  {{ $user_info['real_name'] }}</dt>
                <dd>用户名：{{ $user_info['phone'] }}</dd>
            </dl>
            <dl class="t-view1 js-poshytip">
                <dt>
                    <span title="您已绑定手机 " class="t1-icon23 iconfont">&#xe61f;</span>

                    <span title="还未验证邮箱 <a href='/user/information/setEmail.html' style='color:#1468ec'>点击验证</a>" class="t1-icon25 iconfont t1-icon-gray">&#xe617;</span>

                    <!-- <span title="您已实名认证 " class="t-icon2"></span> -->
                    <span title="您已实名认证 " class="t1-icon20 iconfont">&#xe613;</span>


                    <!-- <span title="您已设置支付密码 " class="t-icon3"></span> -->
                    @if($user_info['password_hash'] == $user_info['trading_password'] || empty($user_info['trading_password']))
                    <span title="还未设置支付密码 " class="t1-icon16 iconfont t1-icon-gray">&#xe61b;</span>
                    @else
                    <span title="您已设置支付密码 " class="t1-icon16 iconfont">&#xe61b;</span>
                    @endif


                </dt>
                <dd>安全等级:
                   <span class="t-m">中</span>
                   <a href="/user/information">立即升级</a>
                </dd>
            </dl>
            <dl class="t-view2">
                <dd>累计收益：{{ number_format($user_info['total_interest']) }}元</dd>
            </dl>

            <dl class="t-view3">
                <dt><a href="/user/bonus.html"><img src="{{assetUrlByCdn('/static/images/new/t1-icon-coupon.png')}}" width="41" height="48" alt="我的优惠券"></a></dt>
                <dd><a href="/user/bonus.html">我的优惠券<br/>{{ $total_bonus }}张</a></dd>
            </dl>

        </div>
        <div class="t-showbox t-mt9px">
            <h3 class="t-view4">账户总资产<span>{{ number_format($user_info['total_amount']) }}元</span></h3>
            <ul class="t-view5">
                <li ><a href="javascript:void(0);" data-target="12" class="t-blue" >账户余额 {{ number_format($user_info['balance']) }}元</a></li>
                <li><a href="javascript:void(0);" data-target="11" >定期资产  {{ number_format($project_account['total_amount']) }}元</a></li>
                <li><a href="javascript:void(0);"  data-target="6" class="t-br0px">零钱计划 {{ number_format($current_account['cash']) }}元</a></li>


            </ul>

            <!-- 账户余额  100,600,00元 -->
            <div class="t-view12"  >
                <div class="t-view13">
                    <p><span class="t-icon5"></span><strong>可用余额</strong><i>{{ number_format($user_info['balance']) }}元</i></p>
                    <p class="t-mt18px"><span class="t-icon6"></span><strong>提现冻结金额</strong><i>0.00(缺少此字段值)元</i></p>
                </div>
                <div class="t-view14">
                    <p><a href="/recharge/index" class="btn btn-red btn-small t-mr30px">充值</a><a href="/pay/withdraw" class="btn btn-blue btn-small">提现</a></p>
                </div>
            </div >


            <!-- 定期资产  1,100,600,00元 -->
            <div class="t-view11"  style="display: none" >
                <div class="t-view9">
                    <p class="t-view9-1">待收收益<br/><span>{{ number_format($project_account['total_amount_interest']) }}元</span></p>
                    <p class="t-view9-2">待收本金<br/><span>{{ number_format($project_account['total_amount_principal']) }}元</span></p>
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
                                <td class="t-l" width="22%"><span class="t-color1">●</span>九省心</td>
                                <td>{{ number_format($project_jsx['principal']) }}元</td>
                                <td width="39%">{{ number_format($project_jsx['interest']) }}元</td>
                            </tr>
                            <tr>
                                <td class="t-l"><span class="t-color2">●</span>九安心</td>
                                <td>{{ number_format($project_jax['principal']) }}元</td>
                                <td>{{ number_format($project_jax['interest']) }}元</td>
                            </tr>
                            <tr>
                                <td class="t-l"><span class="t-color3">●</span>变现宝</td>
                                <td>0.00(缺少此项目值)元</td>
                                <td>0.00元</td>
                            </tr>
                            <tr>
                                <td class="t-l"><span class="t-color4">●</span>投资冻结</td>
                                <td>0.00(缺少此项目值)元</td>
                                <td>出借当日计息</td>
                            </tr>
                            <tr>
                                <td class="t-l"><span class="t-color5">●</span>闪电付息</td>
                                <td>{{ number_format($project_sdf['principal']) }}元</td>
                                <td>投资当日返息</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 零钱计划  100,600,00元 -->
            <div class="t-view6"  style="display: none"  >
                <div class="t-view7">
                    <p class="t-view7-1">收益（元）</p>
                    <div class="t-view7-2"><div id="main" style="height:280px; width:580px"></div></div>
                </div>
                <div class="t-view8">
                    <p class="t-view8-1">昨日收益<span>{{ number_format($current_account['yesterday_interest']) }}元</span></p>
                    <p class="t-view8-2">累计收益<span>{{ number_format($current_account['interest']) }}元</span></p>
                    <p class="t-view8-3"><a href="/project/current/detail" class="btn btn-red btn-small t-mr20px">买入</a>
                        <a class="btn btn-blue btn-small" data-target="modul2">卖出</a>
                    </p>

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
                <dd><input type="text" name="cash" id="cash" class="form-input" value=""  autocomplete="off" placeholder="请输入转出金额" /> 元</dd>
                <dt>交易密码</dt>
                <dd><input type="password" id="trading_password" autocomplete="off" name="tradingPassword" placeholder="交易密码" class="form-input" value=""/>
                    <a href="/user/tradingPassword">忘记密码？</a>
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
        })

        /*控制弹出层*/
        /*function takeout(){
            $(".m-blackbg").show();
            $(".m-transfer").show();
        }

        function closetakeout(){
            $(".m-blackbg").hide();
            $(".m-transfer").hide();
        }
        function showsuccess(){
            $(".m-transfer").hide();
            $(".success").show();
        }

        function cancletranfer(){
            $(".m-blackbg").show();
            $(".m-cancle").show();
        }

        function hidecancle(){
            $(".m-blackbg").hide();
            $(".m-cancle").hide();
        }*/

    })(jQuery);

</script>


<script type="text/javascript">
    /*饼状图*/
    var doughnutData = [
        {
            value: 0,
            color: "#00abee",
            highlight: "#1fb3ed",
            label: "九省心"
        },
        {
            value: 0,
            color:"#fe5353",
            highlight: "#FF5A5E",
            label: "九安心"
        },
        {
            value: 0,
            color: "#ffa85c",
            highlight: "#fcb06e",
            label: "变现宝"
        },
        {
            value: 0,
            color: "#73d473",
            highlight: "#80d680",
            label: "投资冻结"
        },
        {
            value: "0",
            color: "#dd4cee",
            highlight: "#ec6ffb",
            label: "闪电付息"
        }
    ];

    $(function(){
        $('[rel=show-refundRecord]').click(function(){
            $('.js-show-all-refundRecords tr').show(0);
            $(this).remove();
        });
    });

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
        $(".js-poshytip span").poshytip({
            alignY: 'bottom',
            showTimeout: 100
        });
    });

</script>
<script type="text/javascript">
    // 基于准备好的dom，初始化echarts图表
    var myChart = echarts.init(document.getElementById('main'));
    var option = {
        color:['#face8b'],
        polar : {
            center : ['50%', '50%'],    // 默认全局居中
            radius : '75%',
            startAngle : 90,
            splitNumber : 5,
            name : {
                show: true,
                textStyle: {       // 其余属性默认使用全局文本样式，详见TEXTSTYLE
                    color: '#333'
                }
            },
            axisLine: {            // 坐标轴线
                show: true,        // 默认显示，属性show控制显示与否
                lineStyle: {       // 属性lineStyle控制线条样式
                    color: '#ccc',
                    width: 1,
                    type: 'solid'
                }
            },
            axisLabel: {           // 坐标轴文本标签，详见axis.axisLabel
                show: false,
                textStyle: {       // 其余属性默认使用全局文本样式，详见TEXTSTYLE
                    color: '#333'
                }
            },
            splitArea : {
                show : true,
                areaStyle : {
                    color: ['rgba(250,250,250,0.3)','rgba(200,200,200,0.3)']
                }
            },
            splitLine : {
                show : true,
                lineStyle : {
                    width : 1,
                    color : '#000'
                }
            }
        },

        grid: {
            x: 80,
            y: 60,
            x2: 80,
            y2: 60,
            // width: {totalWidth} - x - x2,
            // height: {totalHeight} - y - y2,
            backgroundColor: 'rgba(0,0,0,0)',
            borderWidth: 8,
            borderColor: '#fff',
            // backgroundColor:"#000"
        },

        tooltip : {
            trigger: 'axis',
            backgroundColor: '#fff',
            borderColor: '#f5f5f5',
            borderWidth: 2,
            padding:8,
            borderRadius: 8,

            formatter:function(a)
            {
                // alert(JSON.stringify(a));
                var infokill={"2016-07-06":{"interest":"7.03","principal":"36681.32","rate":"7.0 %","date":"2016-07-06","principle":"36681.32"},"2016-07-05":{"interest":"7.03","principal":"36674.29","rate":"7.0 %","date":"2016-07-05","principle":"36674.29"},"2016-07-04":{"interest":"7.03","principal":"36667.26","rate":"7.0 %","date":"2016-07-04","principle":"36667.26"},"2016-07-03":{"interest":"7.03","principal":"36660.23","rate":"7.0 %","date":"2016-07-03","principle":"36660.23"},"2016-07-02":{"interest":"7.03","principal":"36653.20","rate":"7.0 %","date":"2016-07-02","principle":"36653.20"},"2016-07-01":{"interest":"7.03","principal":"36646.17","rate":"7.0 %","date":"2016-07-01","principle":"36646.17"},"2016-06-30":{"interest":"7.03","principal":"36639.14","rate":"7.0 %","date":"2016-06-30","principle":"36639.14"}};
                var now = new Date();
                var relVal = "";
                var newyears = a[0].name.split('.');
                var newyeard = new Date().getMonth() + 1;
                var year='';
                if(newyears[0]==12 && newyeard==1){
                    year = now.getFullYear() -1;
                }else{
                    year = now.getFullYear();
                }
                var time=a[0].name.replace(".","-");
                relVal = "• 借款利率"+infokill[year+"-"+time]['rate']+" <br/>";
                relVal += "• 计息金额 "+infokill[year+"-"+time]['principle']+"元<br/>";
                relVal += "• 当日收益"+a[0].value+"元<br/>";
                relVal +="• " + year+"-"+time;
                return relVal;
            },

            axisPointer : {
                type : 'line',
                lineStyle : {
                    color : '#0f0',
                    width : 0,
                    type : 'solid'
                }
            } ,
            textStyle: {
                color: '#a5a5a5',
                fontSize:12
            }
        },
        legend: {
            // orient: 'horizontal',
            // x: 'center',
            //  y: 'top',
            data:[]
            // backgroundColor: '#000',
        },
        toolbox: {
            show : false,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data : ['06.30','07.01','07.02','07.03','07.04','07.05','07.06'],
                axisLabel : {
                    show : true,
                    textStyle : {
                        color : '#414141',
                        fontSize:14,
                    },

                },

                axisLine: {            // 坐标轴线
                    show: true,        // 默认显示，属性show控制显示与否
                    lineStyle: {       // 属性lineStyle控制线条样式
                        color: '#e2e2e2',
                        width: 1,
                        type: 'solid'
                    }
                },
                splitLine : {
                    show : true,
                    lineStyle : {
                        width : 0,
                        color : '#e2e2e2'
                    }
                }
            }
        ],
        yAxis : [
            {
                type : 'value',
                axisLabel : {
                    show : true,
                    textStyle : {
                        color : '#414141',
                        fontSize:14

                    }
                },
                axisLine: {            // 坐标轴线
                    show: true,        // 默认显示，属性show控制显示与否
                    lineStyle: {       // 属性lineStyle控制线条样式
                        color: '#e2e2e2',
                        width: 1,
                        type: 'solid'
                    }
                },splitLine : {
                show : true,
                lineStyle : {
                    width : 1,
                    color : '#e2e2e2'
                }
            },


                // splitLine : {
                //     lineStyle : {

                //         borderWidth: 8,
                //     borderColor: '#fff',
                //     backgroundColor:"#000"

                //     }
                // }
            }
        ],

        series : [
            {
                name:"账户总额",
                type:'line',
                stack: '总量',
                data:[7.03 , 7.03 , 7.03 , 7.03 , 7.03 , 7.03 , 7.03 ,],
                symbol: '/static/images/new/t-y.png',
                symbolSize: 8 ,
                // color:"#facd89",
                backgroundColor: 'rgba(0,0,0,0)',
                borderColor: '#ccc',
                itemStyle:{
                    normal:{

                        lineStyle:{
                            color:"#facd89",
                            width:2,
                        }
                    }

                }
            }
        ]
    };

    // 为echarts对象加载数据
    myChart.setOption(option);
</script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    (function($){
        $("#investOutForm").click(function() {
            var cash  = $.toFixed($.trim($("input[name=cash]").val()));
            var balance  = $.toFixed($.trim($("input[name=balance]").val()));
            var tradingPassword  = $.trim($("input[name=tradingPassword]").val());
            var maxOut  = $.toFixed($.trim($("input[name=maxOut]").val()));

            if( cash<0 || cash==''){
                $("#error_msg").html("请输入正确金额！");
                $("#cash").focus();
                $("#error_msg").show();
                return false;
            }
            if( cash>maxOut ){
                $("#error_msg").html("单日转出金额不超过"+maxOut+"元！");
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
        });
    })(jQuery);

</script>

@endsection