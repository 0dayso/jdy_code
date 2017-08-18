 @extends('wap.common.wapBase')

@section('title', '数据统计')

@section('keywords', "{{env('META_KEYWORD')}}")

@section('description', "{{env('META_DESCRIPTION')}}")
@section('css')
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/datastatistics.css')}}">
    <style>
        body{background-color: #fff;}
    </style>
@endsection
@section('content')
    <section class="data-section">
        <h1 class="data-bt data-btbg1 data-color-blue">耀盛中国</h1>
        <div class="data-mar-lr data-flex data-mar-b">
            <div class="data-flex-box">
                <div class="data-circle data-border-blue">
                    <p class="data-color-blue data-font">8</p>
                    <p class="data-color-blue data-font-small">万余家</p>
                    <span class="data-circle-arrow data-arrow-blue"></span>
                </div>
                <p class="data-sunfund-txt">服务中小企业</p>
            </div>

            <div class="data-flex-box">
                <div class="data-circle data-border-red">
                    <p class="data-color-red data-font">2</p>
                    <p class="data-color-red data-font-small">千亿元</p>
                    <span class="data-circle-arrow data-arrow-red"></span>
                </div>
                <p class="data-sunfund-txt">每年创造经济总产值</p>
            </div>

            <div class="data-flex-box">
                <div class="data-circle data-border-yellow">
                    <p class="data-color-orange data-font">10</p>
                    <p class="data-color-orange data-font-small">年</p>
                    <span class="data-circle-arrow data-arrow-yellow"></span>
                </div>
                <p class="data-sunfund-txt">中小企业服务经验</p>
            </div>

        </div>

        <!-- 九斗鱼平台-->

        <h1 class="data-bt data-btbg1 data-color-blue">九斗鱼平台</h1>
        <div class="data-mar-lr">
            <div class="data-border-box">
                <div class="data-item-hd">
                    <p class="data-9douyu-font1">融资总额（元）</p>
                    <p class="data-color-red data-9douyu-font2">{{(int)$projectInvestAmount+(int)$currentInvestAmount+(int)$creditAssignInvestAmount}}</p>
                </div>
                <div class="data-hr-dotted"></div>
                <div class="data-item-bd data-flex">
                    <div class="data-item-ls">
                        <p class="data-color-grey data-9douyu-font3">定期项目（元）</p>
                        <p class="data-color-yellow data-9douyu-font4">{{(int)$projectInvestAmount}}</p>
                    </div>
                    <div class="data-item-ls">
                        <p class="data-color-grey data-9douyu-font3">零钱计划（元）</p>
                        <p class="data-color-yellow data-9douyu-font4">{{(int)$currentInvestAmount}}</p>
                    </div>
                    <div class="data-item-ls">
                        <p class="data-color-grey data-9douyu-font3">债权转让（元）</p>
                        <p class="data-color-yellow data-9douyu-font4">{{(int)$creditAssignInvestAmount}}</p>
                    </div>
                </div>
            </div>
            <div class="data-item-bot data-flex">
                <div class="data-border-box data-item-ll">
                    <p class="data-9douyu-font5">累计预期收益（元）</p>
                    <p class="data-color-blue data-9douyu-font6">{{(int)$totalInterest}}</p>
                </div>
                <div class="data-border-box data-item-ll">
                    <p class="data-9douyu-font5">安全归还用户本息(元)</p>
                    <p class="data-color-orange data-9douyu-font6">{{(int)$refundAmount}}</p>
                </div>
            </div>
        </div>

        <!-- 九斗鱼借贷不良率-->

        <h1 class="data-bt data-btbg2 data-color-blue data-no-marb">九斗鱼借贷不良率</h1>
        <p class="data-color-grey data-info-font1">2014年7月上线推广以来</p>
        <div class="data-info-inner">
            <div class="data-info-circle data-circle-blue">零</div>
            <p class="data-color-blue data-info-font2">九斗鱼平台项目至今<br/>无一笔逾期</p>
        </div>
        <div class="data-info-inner data-info-mart">
            <div class="data-info-circle data-circle-red">0.76%</div>
            <p class="data-color-red data-info-font3">2014-2015财务年度</p>
        </div>
        <p class="data-color-red data-info-font4">
            耀盛中国整体借贷不良率为0.76%<br/>
            不足国内银行借贷不良率的二分之一
        </p>
        <p class="data-color-grey data-info-font5 data-mar-b">注：数据由世界知名会计师事务所审计</p>

        <!-- 平台项目的收益对比-->

        <h1 class="data-bt data-btbg2 data-color-blue">平台项目的收益对比</h1>
        <img src="{{assetUrlByCdn('/static/weixin/images/data/data-chart1.png')}}" alt="数据图" class="data-img-chart"/>
        <div class="data-hr-dotted data-hr-color-dark"></div>

        <!-- 投资者选择了九斗鱼-->
        <?php
            $len = strlen((int)$userCount);
            $userCountStr = '';
            for($i=0; $i<$len; $i++)
            {
                $userCountStr .= "<span>".substr($userCount,$i,1)."</span>";
            }
        ?>
        <div class="data-user-wrap">
            <p class="data-user-num">已有&nbsp;&nbsp;{!! $userCountStr !!}&nbsp;&nbsp;位</p>
            <p class="data-9douyu-font7">投资者选择了九斗鱼</p>
        </div>
        <div class="data-canvas-wrap">
            <div id="main" style="height:260px;"></div>
            <div class="data-canvas-mask">用户分布</div>
        </div>
        <div class="data-mar-lr data-flex data-canvas-list">
            <ul>
                <li><span class="data-item-colorred"></span>广东</li>
                <li><span class="data-item-coloryellow"></span>浙江</li>
                <li><span class="data-item-colorblue"></span>山东</li>
            </ul>
            <ul>
                <li><span class="data-item-colororange"></span>北京</li>
                <li><span class="data-item-colorpurple"></span>江苏</li>
                <li><span class="data-item-colordarkblue"></span>其余</li>
            </ul>
        </div>

    </section>
    </block>
    <block name="jsScript">
        <script type="text/javascript" src="{{ assetUrlByCdn('static/weixin/js/lazyload.js')}}"></script>
        <script type="text/javascript" src="{{ assetUrlByCdn('static/weixin/js/echarts-all.js') }}"></script>
        <script>
            /**
             * Created by MF839 on 16/5/6.
             */
            var myChart = echarts.init(document.getElementById('main'));
            option = {

                series : [

                    {
                        name:'用户分布',
                        type:'pie',
                        center : ['50%', '50%'],
                        radius : [40, 60],
                        data:[
                            {
                                value:800,
                                name:'12.77%',
                                itemStyle : {
                                    normal : {
                                        color : '#ff6e52',
                                        label : {
                                            textStyle : {
                                                color : '#666',
                                            }
                                        },
                                        labelLine : {
                                            lineStyle : {
                                                color : '#999',

                                            }
                                        }
                                    }
                                }
                            },
                            {
                                value:600,
                                name:'8.04%',
                                itemStyle : {
                                    normal : {
                                        color : '#ffbe57',
                                        label : {
                                            textStyle : {
                                                color : '#666',
                                            }
                                        },
                                        labelLine : {
                                            lineStyle : {
                                                color : '#999',

                                            }
                                        }
                                    }
                                }
                            },
                            {
                                value:600,
                                name:'7.78%',
                                itemStyle : {
                                    normal : {
                                        color : '#ffe000',
                                        label : {
                                            textStyle : {
                                                color : '#666',
                                            }
                                        },
                                        labelLine : {
                                            lineStyle : {
                                                color : '#999',

                                            }
                                        }
                                    }
                                }
                            },
                            {
                                value:440,
                                name:'6.87%',
                                itemStyle : {
                                    normal : {
                                        color : '#cb7bff',
                                        label : {
                                            textStyle : {
                                                color : '#666',
                                            }
                                        },
                                        labelLine : {
                                            lineStyle : {
                                                color : '#999',

                                            }
                                        }
                                    }
                                }
                            },
                            {
                                value:300,
                                name:'5.86%',
                                itemStyle : {
                                    normal : {
                                        color : '#8abff9',
                                        label : {
                                            textStyle : {
                                                color : '#666',
                                            }
                                        },
                                        labelLine : {
                                            lineStyle : {
                                                color : '#999',

                                            }
                                        }
                                    }
                                }
                            },
                            {
                                value:1000,
                                name:'58.68%',
                                itemStyle : {
                                    normal : {
                                        color : '#66ccf5',
                                        label : {
                                            textStyle : {
                                                color : '#666',
                                            }
                                        },
                                        labelLine : {
                                            lineStyle : {
                                                color : '#999',

                                            }
                                        }
                                    }
                                }
                            }



                        ]
                    }

                ]
            };
            myChart.setOption(option);
        </script>
@endsection