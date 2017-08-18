@extends('wap.common.wapBase')

@section('title', '资产安全')

@section('keywords', "{{env('META_KEYWORD')}}")

@section('description', "{{env('META_DESCRIPTION')}}")

@section('css')
    <link rel="stylesheet" type="text/css" href="{{assetUrlByCdn('/static/weixin/css/reset12.css')}}"/>
@endsection

@section('content')
    <script>
        var readyRE = /complete|loaded|interactive/;
        var ready = window.ready = function (callback) {
            if (readyRE.test(document.readyState) && document.body) callback()
            else document.addEventListener('DOMContentLoaded', function () {
                callback()
            }, false)
        }
        //rem方法
        function ready_rem() {
            var view_width = document.getElementsByTagName('html')[0].getBoundingClientRect().width;
            var _html = document.getElementsByTagName('html')[0];


            if (view_width > 640) {
                _html.style.fontSize = 640 / 16 + 'px'
            } else if (screen.height > 500) {
                _html.style.fontSize = view_width / 18 + 'px';
                if (screen.height == 1280 && screen.width == 800) {
                    _html.style.fontSize = view_width / 22 + 'px';
                }
            } else {

                _html.style.fontSize = 15 + 'px'
            }

        }
        ready(function () {
            ready_rem();
        });

    </script>
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/safe.css') }}" id="sc"/>
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/animations.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/css/animate.min.css') }}"/>
    </head>
    <body>
    <div>
        <div class="page page-1-1 page-current">
            <div class="wrap">
                <!-- 优质资产-->
                <div class="safe-list animated fadeInRight" style="margin-top:0;">
                    <img class="safe-list-bg" src="{{ assetUrlByCdn('/static/weixin/images/safe/bg-line.png') }}"/>

                    <div class="safe-list-content">
                        <h4 class="safe-list-title">优质资产</h4>

                        <div class="safe-list-flex">
                            <div class="safe-list-imgBox">
                                <img class="safe-list-icon list-icon1"
                                     src="{{ assetUrlByCdn('/static/weixin/images/safe/safe-list-icon1.png') }}"/>
                            </div>
                            <p class="safe-list-txt">九斗鱼根据不同的产品类型及行业特性等特点制定了严苛的质量准则，通过层层筛选、实地勘察甄选出优质资产资源</p>
                        </div>
                    </div>

                </div>

                <!--分割线-->
                <div class="safe-list-line pt-page-scaleUp pt-page-delay500"></div>
                <!--分割线-->

                <!-- 专业风控-->
                <div class="safe-list animated fadeInLeft pt-page-delay200">
                    <img class="safe-list-bg" src="{{ assetUrlByCdn('/static/weixin/images/safe/bg-line.png') }}"/>

                    <div class="safe-list-content">
                        <h4 class="safe-list-title">专业风控</h4>

                        <div class="safe-list-flex">
                            <div class="safe-list-imgBox">
                                <img class="safe-list-icon list-icon2"
                                     src="{{ assetUrlByCdn('/static/weixin/images/safe/safe-list-icon2.png')}}"/>
                            </div>
                            <p class="safe-list-txt">领先的<span class="color-orange">RISKCALC</span><sup
                                        class="color-orange">&reg;</sup>风控定性定量全面剖析企业还款能力银行级别审核机制，360度实勘调查，源头把控项目安全</p>
                        </div>
                    </div>
                </div>

                <!--分割线-->
                <div class="safe-list-line pt-page-scaleUp pt-page-delay500"></div>
                <!--分割线-->

                <!-- 回款保障-->
                <div class="safe-list animated fadeInRight pt-page-delay400">
                    <img class="safe-list-bg" src="{{assetUrlByCdn('/static/weixin/images/safe/bg-line.png')}}"/>

                    <div class="safe-list-content">
                        <h4 class="safe-list-title">回款保障</h4>

                        <div class="safe-list-flex">
                            <div class="safe-list-imgBox">
                                <img class="safe-list-icon list-icon3"
                                     src="{{assetUrlByCdn('/static/weixin/images/safe/safe-list-icon3.png')}}"/>
                            </div>
                            <p class="safe-list-txt" style="padding-top:0.5rem;">
                                千万风险准备金
                                东亚银行资金监管
                                <!-- 第三方担保机构本息安全计划 -->
                                借款额度<span class="color-orange">20%</span>的履约保证金</p>
                        </div>
                    </div>
                </div>


                <img class="icon-arrow pt-page-moveIconUp" src="{{assetUrlByCdn('/static/weixin/images/safe/icon-arrow-up.png')}}"/>

            </div>
        </div>
        <div class="page page-2-1 hide">
            <div class="wrap" style="padding: 3.45rem 0;">
                <img class="icon-arrow icon-arrow-down pt-page-moveIconDown" src="{{assetUrlByCdn('/static/weixin/images/safe/icon-arrow-down.png')}}"/>
                <!-- 技术保障-->
                <div class="safe-list pt-page-scaleUp" style="margin-top:0;">
                    <img class="safe-list-bg" src="{{assetUrlByCdn('/static/weixin/images/safe/bg-line.png')}}"/>

                    <div class="safe-list-content">
                        <h4 class="safe-list-title pt-page-scaleUp">技术保障</h4>

                        <div class="safe-list-flex">
                            <div class="safe-list-imgBox">
                                <img class="safe-list-icon list-icon4"
                                     src="{{assetUrlByCdn('/static/weixin/images/safe/safe-list-icon4.png')}}"/>
                            </div>
                            <p class="safe-list-txt">支持安全套接层协议和256位加密协议，7*24小时不间断主动备份技术。
                                技术团队均来自BAT、360等互联网公司，技术实力雄厚</p>
                        </div>
                    </div>
                </div>

                <!--分割线-->
                <div class="safe-list-line pt-page-scaleUp pt-page-delay500"></div>
                <!--分割线-->

                <!-- 法律保障-->
                <div class="safe-list pt-page-scaleUp pt-page-delay200">
                    <img class="safe-list-bg" src="{{assetUrlByCdn('/static/weixin/images/safe/bg-line.png')}}"/>

                    <div class="safe-list-content">
                        <h4 class="safe-list-title pt-page-scaleUp">法律保障</h4>

                        <div class="safe-list-flex">
                            <div class="safe-list-imgBox">
                                <img class="safe-list-icon list-icon5"
                                     src="{{assetUrlByCdn('/static/weixin/images/safe/safe-list-icon5.png')}}"/>
                            </div>
                            <p class="safe-list-txt">中国金融认证中心（CFCA）认证，国家级网络信息体系保障电子合同合规合法。万商天勤事务所深度合作，专业高效法律服务</p>
                        </div>
                    </div>
                </div>

                <!--分割线-->
                <div class="safe-list-line pt-page-scaleUp pt-page-delay500"></div>
                <!--分割线-->

                <!-- 实力保障-->
                <div class="safe-list pt-page-scaleUp pt-page-delay400">
                    <img class="safe-list-bg" src="{{assetUrlByCdn('/static/weixin/images/safe/bg-line.png')}}"/>

                    <div class="safe-list-content">
                        <h4 class="safe-list-title pt-page-scaleUp">实力保障</h4>

                        <div class="safe-list-flex">
                            <div class="safe-list-imgBox">
                                <img class="safe-list-icon list-icon6"
                                     src="{{assetUrlByCdn('/static/weixin/images/safe/safe-list-icon6.png')}}"/>
                            </div>
                            <p class="safe-list-txt">
                                集团实缴注册资本金 <span class="color-orange">2</span> 亿元，<span class="color-orange">10</span>
                                年专注中小企业金融领域服务经验，业务遍布全国 <span class="color-orange">9</span> 省，涉及信贷、保理、租赁、企业征信、财富管理等多个领域</p>
                        </div>
                    </div>

                </div>



            </div>
        </div>


    </div>

    <script src="{{ assetUrlByCdn('/static/weixin/js/zepto.min.js') }}"></script>
    <script src="{{ assetUrlByCdn('/static/weixin/js/touch.js') }}"></script>
    <script src="{{ assetUrlByCdn('/static/weixin/js/safe.js') }}"></script>
@endsection
