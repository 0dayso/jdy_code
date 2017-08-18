@extends('wap.common.wapBase')
@section('title', '零钱计划')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('content')
    <article>
        <section class="w-box-show pb0px">
            <p class="center"><span class="gray-title-bj font15px plr15px">零钱计划</span></p>
            <p class="center w-bule-color pr"><span class="font60px">{{ (float)$rate }}</span><span class="font18px">%</span></p>
            <p class="center w-999-color font12px">借款利率</p>
            <div class="w_current font12px mt15px">
                <div class="alignleft"><span class="span-icon"></span>当日计息</div>
                <div class="center"><span class="span-icon1"></span>灵活存取</div>
                <div class="alignright"><span class="span-icon2"></span>1元起投</div>
            </div>
        </section>

        <section class="w-box-show mt15px hidden">
            <p class="w-414141-color font13px">1万元投资预期收益（元）</p>
            <div class="data-box mb25" id="bar">
                <div class="data-graph">
                    <div class="data-graph-box">
                        <div class="data-graph-bar data-bar1"  style=" height: 0.2rem; display: block;">
                            <span>{{$day_interest}}</span>
                            <div class="data-point"></div>
                        </div>
                        <p>1天</p>

                    </div>
                    <div class="data-graph-box">
                        <div class="data-graph-bar data-bar2" style=" height: 0.6rem; display: block;">
                            <span>{{$day_interest*3}}</span>
                            <div class="data-point"></div>
                        </div>
                        <p>3天</p>

                    </div>
                    <div class="data-graph-box">
                        <div class="data-graph-bar data-bar3" style=" height: 1rem; display: block;">
                            <span>{{$day_interest*5}}</span>
                            <div class="data-point"></div>
                        </div>
                        <p>5天</p>

                    </div>
                    <div class="data-graph-box">
                        <div class="data-graph-bar data-bar4" style=" height: 1.4rem; display: block;">
                            <span>{{$day_interest*7}}</span>
                            <div class="data-point"></div>
                        </div>
                        <p>7天</p>


                    </div>
                    <div class="data-graph-box">
                        <div class="data-graph-bar data-bar5" style=" height: 2rem; display: block;">
                            <span>{{$day_interest*10}}</span>
                            <div class="data-point"></div>
                        </div>
                        <p>10天</p>
                    </div>

                    <div class="data-graph-box">
                        <div class="data-graph-bar data-bar5" style=" height: 3rem; display: block;">
                            <span>{{$day_interest*15}}</span>
                            <div class="data-point"></div>
                        </div>
                        <p>15天</p>

                    </div>

                    <div class="data-graph-box">
                        <div class="data-graph-bar data-bar5" style=" height: 4rem; display: block;">
                            <span>{{$day_interest*20}}</span>
                            <div class="data-point"></div>
                        </div>
                        <p>20天</p>

                    </div>
                </div>
            </div>

            <a href="/project/descriptions" class="gray-title gray-button mt15px fr">什么是零钱计划？</a>

        </section>

        {{-- <section class="w-h-box mt20px pb0px">
            <p class="center"><span class="gray-title-bj font15px plr15px">九斗鱼是什么？</span></p>
            <p class="center font12px mt10px w-999-color pb20px">九斗鱼是耀盛中国旗下的新一代的手机理财神器，安全可靠高收益</p>
        </section>

        <section class="w-h-box1 hidden">
            <dl class="w-h-dl">
                <dt class="w-h-icon1"></dt>
                <dd>
                    <ul>
                        <li><h3>更安全</h3></li>
                        <li><span>●</span>3亿实缴资本</li>
                        <li><span>●</span>耀盛中国东亚银行强强联合</li>
                        <li><span>●</span>成立11年，4重本息保障计划</li>
                    </ul>
                <dd>
            </dl>

            <dl class="w-h-dl">
                <dd>
                <dt class="w-h-icon2"></dt>
                <ul>
                    <li><h3>高收益</h3></li>
                    <li><span>●</span>零钱计划产品，最低年化7%起</li>
                    <li><span>●</span>定期产品，最高借款利率15%</li>
                </ul>
                <dd>
            </dl>

            <dl class="w-h-dl">
                <dd>
                <dt class="w-h-icon3"></dt>
                <ul>
                    <li><h3>更便捷</h3></li>
                    <li><span>●</span>1元起投，手机随时理财</li>
                    <li><span>●</span>随时变现，快速回收资金</li>
                </ul>
                <dd>
            </dl>

            <dl class="w-h-dl">
                <dd>
                <dt class="w-h-icon4"></dt>
                <ul>
                    <li><h3>更好玩</h3></li>
                    <li><span>●</span>月月有活动，Macbook、</br>&nbsp;&nbsp;&nbsp;iphone 6s等大奖送不停</li>
                </ul>
                <dd>
            </dl>
        </section> --}}

        <section class="w-line"></section>
        <section id="invest_project" class="w-bottom">
            <div class="w-bottom-btn w-mt8px">
                <table class="w-table2">
                    <tr>
                        <td>
                            <a href="/invest/current/confirm" class="w-btn"><span class=" pr15px">立即出借</span><span class="font12px">1元起投</span></a>
                        </td>
                    </tr>
                </table>
            </div>
        </section>
    </article>
@endsection


@section('jsScript')
    <script src="{{ assetUrlByCdn('static/js/numloop.js')}}"></script>
@endsection
