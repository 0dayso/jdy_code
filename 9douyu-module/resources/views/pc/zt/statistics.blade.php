@extends('pc.common.layout')
@section('title','平台数据-九斗鱼,心安而有余')
@section('csspage')
    <link rel="stylesheet" href="{{ assetUrlByCdn('/static/css/base.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ assetUrlByCdn('/static/css/common.css') }}" type="text/css" />
    <style>
        @font-face {
            font-family: 'Conv_DINCondensedBold';
            src: url('{{ assetUrlByCdn('/static/css/font/DINCondensedBold.eot') }}');
            src: local('☺'), url('{{ assetUrlByCdn('/static/css/font/DINCondensedBold.woff') }}') format('woff'), url('{{ assetUrlByCdn('/static/css/font/DINCondensedBold.ttf') }}') format('truetype'), url('{{ assetUrlByCdn('/static/css/font/DINCondensedBold.svg') }}') format('svg');
        }
    </style>
@endsection
@section('content')
    <div class="data-bgcolor hidden">
        <div class="data-wrap bc">
            <h1 class="data-title">数据统计</h1>
            <div class="data-box mb25">
                <h2 class="data-title2 mb40">耀盛中国</h2>
                <div class="data-block data-block1 fl">服务中小企业8千余家</div>
                <div class="data-block data-block2 fl">11年中小企业金融服务经验</div>
                <div class="data-block data-block3 fr">每年创造经济总产值2千亿元</div>
                <div class="clear mb40"></div>
            </div>
            <div class="data-box mb25" id="datamove">
                <h2 class="data-title2 mb40">九斗鱼平台</h2>
                <div class="data-move pr fl mr65">
                    <p class="f16 mb30">融资总额</p>
                    @if($totalAmount > 8)
                        <span class="data data-smaller data-smaller-cash" data-rel="{{$totalAmount}}">0</span>
                    @else
                        <span class="data" data-rel="{{$totalAmount}}">0</span>
                    @endif
                    <span class="data-unit">元</span>
                </div>
                <div class="data-product">
                    <ins></ins>
                    <div class="data-product-box">
                        <i></i>
                        <p>直投项目</p>
                        <span class="data-product-sum data" data-rel="{{$projectInvestAmount}}">0</span>
                        <sup>元</sup>
                    </div>
                    <div class="data-product-box">
                        <i></i>
                        <p>零钱计划</p>
                        <span class="data-product-sum data" data-rel="{{$currentInvestAmount}}">0</span>
                        <sup>元</sup>
                    </div>
                    <div class="data-product-box mn">
                        <p>债权转让</p>
                        <span class="data-product-sum data" data-rel="{{$creditAssignInvestAmount}}">0</span>
                        <sup>元</sup>
                    </div>
                </div>
                <div class="clear"></div>
                <h2 class="data-title2 mb40">&nbsp;</h2>
                <div class="data-move pr fl">
                    <p class="f16 mb30">累计预期收益</p>
                    @if($totalInterest > 8)
                        <span class="data data-smaller data-yellow" data-rel="{{$totalInterest}}">0</span>
                    @else
                        <span class="data data-yellow" data-rel="{{$totalInterest}}">0</span>
                    @endif
                    <span class="data-unit">元</span>
                </div>
                <div class="data-move lf23px pr fr">
                    <p class="f16 mb30">安全归还用户本息</p>
                    @if($refundAmount > 8)
                        <span class="data data-smaller data-red" data-rel="{{$refundAmount}}">0</span>
                    @else
                        <span class="data data-red" data-rel="{{$refundAmount}}">0</span>
                    @endif
                    <span class="data-unit">元</span>
                </div>
                <div class="clear mb30"></div>
            </div>
            {{--九斗鱼借贷不良率--}}
            <div class="data-box mb25">
                <h2 class="data-title2 mb10">九斗鱼借贷不良率</h2>
                <div class="flighter data-font f18 mb50">2014年7月上线推广以来</div>
                <div class="data-rate fl pr w330">
                    <div class="data-rate-left"></div>
                    <p class="mt40">九斗鱼平台项目至今<br />无一笔逾期</p>
                </div>
                <div class="data-rate fr pr">
                    <div class="data-rate-right"></div>
                    <p class="mt10">2014-2015财务年度<br />耀盛中国整体借贷不良率为0.76%<br />不足国内银行借贷不良率的二分之一</p>
                    <p class="data-font">注：数据由世界知名会计师事务所审计</p>
                    <p class="tr mt5"><a href="http://www.riskcalc.cn/" target="_blank" style="text-decoration: underline;">了解RISKCALC</a></p>
                </div>
                <div class="clear mb20"></div>
            </div>
           {{--平台项目的收益对比--}}
            <div class="data-box mb25" id="bar">
                <h2 class="data-title2 mb40">平台项目的收益对比</h2>
                <div class="data-graph">
                    <div class="data-graph-box">
                        <div class="data-graph-bar data-bar1">
                            <span>3.5%</span>
                        </div>

                        <p>CPI</p>
                    </div>
                    <div class="data-graph-box">
                        <div class="data-graph-bar data-bar2">
                            <span>0.35%</span>
                        </div>

                        <p>银行活期</p>
                    </div>
                    <div class="data-graph-box">
                        <div class="data-graph-bar data-bar3">
                            <span>2.75%</span>
                        </div>

                        <p>银行一年定期</p>
                    </div>
                    <div class="data-graph-box">
                        <div class="data-graph-bar data-bar4">
                            <span>7-12%</span>
                        </div>

                        <p>九斗鱼</p>
                    </div>
                    <div class="data-graph-box">
                        <div class="data-graph-bar data-bar5">
                            <span>3.0%</span>
                        </div>

                        <p>宝宝类产品</p>
                    </div>
                </div>
            </div>
            {{--注册人数--}}
            <div class="data-box mb25">
                <h2 class="data-title2 mb10">已有<span class="data-font3">{{$userCount}}</span>位出借人选择了九斗鱼</h2>
                <div class="flighter data-font f18 mb50">分布在全国34个地区</div>

                <div id="main" class="datamap-main fl">

                </div>

                <dl class="data-map-remark fr">
                    <dt class="data-num1"></dt>
                    <dd>广东：12.77%</dd>
                    <dd class="clear mb17"></dd>
                    <dt class="data-num1"></dt>
                    <dd>北京：8.04%</dd>
                    <dd class="clear mb17"></dd>
                    <dt class="data-num2"></dt>
                    <dd>浙江：7.78%</dd>
                    <dd class="clear mb17"></dd>
                    <dt class="data-num2"></dt>
                    <dd>江苏：6.87%</dd>
                    <dd class="clear mb17"></dd>
                    <dt class="data-num3"></dt>
                    <dd>山东：5.86%</dd>
                    <dd class="clear mb17"></dd>
                    <dt class="data-num6"></dt>
                    <dd>其余：58.68%</dd>
                </dl>

                <div class="clear mb20"></div>
            </div>
            <div class="data-box mb25">
                <div class="data-title3 bc mt15 mb20">您的信赖就是我们前进的动力</div>
                <a href="/register" class="data-btn bc" target="_blank">免费注册</a>
            </div>
        </div>
    </div>
    <div class="data-bottom">统计数据截止至 {{$nowDay}}</div>
@endsection

@section('jspage')
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/js/nummove.js')}}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/js/echarts-all.js')}}"></script>
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/js/data-visiable.js')}}"></script>
    <script type="text/javascript" language="JavaScript">
        (function($){
            $(document).ready(function(){

                //城市数据加载

                var h = parseInt($(".data-map-remark").css("height").replace(/px/, ''));

                if(h < 480){
                    var mt = (480 - h)/2 + "px";
                    $(".data-map-remark").css("margin-top",mt).show();
                }



            });
        })(jQuery)

        //出借人地图分布
        var myChart = echarts.init(document.getElementById('main'));

        var option = {
            tooltip: {
                trigger: 'item',
                formatter: function(params) {
                    var value = value || 0;
                    if(params.value.substr(1)>0){
                        return params.name + '<br/>' + params.value.substr(1)+'%' ;
                    }else{
                        return params.name + '<br/>--';
                    }
                }
            },
            series : [
                {
                    name: '九斗鱼出借人分布',
                    type: 'map',
                    mapType: 'china',
                    mapLocation: {
                        x: 'left'
                    },
                    // selectedMode : 'multiple',
                    itemStyle: {
                        normal: {
                            borderWidth:1,
                            borderColor:'#fff',
                            color: '#dcf4f3',
                            label: {
                                show: true,
                                textStyle: {
                                    color: '#666666'
                                }
                            }
                        },
                        emphasis: {                 // 也是选中样式
                            borderWidth:1,
                            borderColor:'#fff',
                            color: '#72d2d0',
                            label: {
                                show: true,
                                textStyle: {
                                    color: '#fff'
                                }
                            }
                        }
                    }, data:[
                    //{name:'南海诸岛',
                    // itemStyle: {
                    //    normal: {
                    //        label: {
                    //        show: false,
                    //        textStyle: {
                    //            color: '#666666'
                    //        }
                    //    }
                    //    }
                    //}},

                    {"name":"广东","value":"12.77","itemStyle":{"normal":{"color":"#72d2d0"}}},{"name":"北京","value":"8.04","itemStyle":{"normal":{"color":"#72d2d0"}}},{"name":"浙江","value":"7.78","itemStyle":{"normal":{"color":"#87d9d7"}}},{"name":"江苏","value":"6.87","itemStyle":{"normal":{"color":"#87d9d7"}}},{"name":"山东","value":"5.86","itemStyle":{"normal":{"color":"#9cdfde"}}},{"name":"上海","value":"4.46","itemStyle":{"normal":{"color":"#9cdfde"}}},{"name":"河北","value":"4.11","itemStyle":{"normal":{"color":"#b2e6e5"}}},{"name":"福建","value":"3.93","itemStyle":{"normal":{"color":"#b2e6e5"}}},{"name":"河南","value":"3.80","itemStyle":{"normal":{"color":"#c7edec"}}},{"name":"山西","value":"3.60","itemStyle":{"normal":{"color":"#c7edec"}}},{"name":"湖南","value":"3.27","itemStyle":{"normal":{"color":"#D5EDEB"}}},{"name":"湖北","value":"3.17","itemStyle":{"normal":{"color":"#D5EDEB"}}},{"name":"四川","value":"3.14","itemStyle":{"normal":{"color":"#D5EDEB"}}},{"name":"陕西","value":"2.97","itemStyle":{"normal":{"color":"#D5EDEB"}}},{"name":"辽宁","value":"2.75","itemStyle":{"normal":{"color":"#D5EDEB"}}},{"name":"安徽","value":"2.25","itemStyle":{"normal":{"color":"#D5EDEB"}}},{"name":"黑龙江","value":"2.17","itemStyle":{"normal":{"color":"#D5EDEB"}}},{"name":"云南","value":"2.06","itemStyle":{"normal":{"color":"#D5EDEB"}}},{"name":"天津","value":"2.02","itemStyle":{"normal":{"color":"#dcf4f3"}}}
                ]
                }
            ],
            animation: true
        };
        myChart.setOption(option);
    </script>

@endsection
