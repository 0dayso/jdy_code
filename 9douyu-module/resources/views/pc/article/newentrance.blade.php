@extends('pc.common.layout')
@section('title', $title)
@section('keywords', $keywords)
@section('description', $description)
@section('content')
    <!--
            图片
        -->
    <div class="m-centershow">
        <p class="textstyle">九斗鱼，心安财有余。<br>
            银行资金监管的互联网理财平台，为出借人提供本息安全计划的理财服务</p>
    </div>
    <!--
        为什么选择九斗鱼
    -->
    <div>
        <div class="m-reason">
            <i class="m-text1"></i>
            <ul>
                <li class="first">
                    <img src="{{ assetUrlByCdn('/static/images/new/m-reasonicon1.png') }}"/>

                    <h3 class="pr"><img src="{{ assetUrlByCdn('/static/images/new/m-num1.png') }}" />雄厚实力</h3>
                    <p>注册资本金6000万，11年金融风控经验，公司业务遍布全国各地</p>
                </li>
                <li>
                    <img src="{{ assetUrlByCdn('/static/images/new/m-reasonicon2.png') }}"/>

                    <h3 class="pr"><img src="{{ assetUrlByCdn('/static/images/new/m-num2.png') }}" />高稳收益</h3>

                    <p>借款利率高达12%，35倍银行活期存款，1元起投，期限灵活，随时变现</p>
                </li>
                <!-- <li>
                    <img src="{{ assetUrlByCdn('/static/images/new/m-reasonicon3.png') }}"/>

                    <h3 class="pr"><img src="{{ assetUrlByCdn('/static/images/new/m-num3.png') }}" />本息安全</h3>

                    <p>银行资金监管，千万风险准备金和第三方担保机构共同提供多重保障方式</p>
                </li> -->
                <li class="last">
                    <img src="{{ assetUrlByCdn('/static/images/new/m-reasonicon4.png') }}"/>

                    <h3 class="pr"><img src="{{ assetUrlByCdn('/static/images/new/m-num3.png') }}" />风控严谨</h3>

                    <p>具有国家专利技术的risckcalc风控系统定量分析风险，专业的风控团队层层把关，完善的风险保障机制</p>
                </li>
            </ul>
        </div>
    </div>
    <!--
        众多出借项目任您选
    -->
    <div class="m-reasoncontains">
        <div class="m-managepro">
            <i class="m-text2"></i>
            <ul>
                <a href="/project/index" target="_blank">
                    <li class="m-first">
                        <h3 class="color1">超短期</h3>
                        <img src="{{ assetUrlByCdn('/static/images/new/m-managepro1.png') }}"/>
                        <span class="txt">
                            <p class="special"><i class="redicon"></i><span>8%</span>借款利率</p>
                            <p><i class="redicon"></i><span>30天</span>借款期限</p>
                        </span>
                        <a href="/project/index" target="_blank" class="button"></a>
                    </li>
                </a>


                <a href="/project/current/detail">
                    <li class="m-second">
                        <h3 class="color2">灵活存取</h3>
                        <img src="{{ assetUrlByCdn('/static/images/new/m-managepro2.png') }}"/>
                        <span class="txt">
                            <p class="special"><i class="yellowicon"></i><span>7%</span>借款利率</p>
                            <p><i class="yellowicon"></i><span>1元</span>起投</p>
                        </span>
                        <a href="/project/current/detail" class="button"></a>
                    </li>
                </a>
                <a href="/project/index" target="_blank">
                    <li class="m-third">
                        <h3 class="color3">明星产品</h3>
                        <img src="{{ assetUrlByCdn('/static/images/new/m-managepro3.png') }}"/>
                        <span class="txt">
                            <p class="special"><i class="greenicon"></i><span>10%</span>借款利率</p>
                            <p><i class="greenicon"></i><span>3个月</span>借款期限</p>
                        </span>
                        <a href="/project/index" target="_blank" class="button"></a>
                    </li>
                </a>
                <a href="/project/index" target="_blank">
                    <li class="m-last">
                        <h3 class="color4">抢疯了</h3>
                        <img src="{{ assetUrlByCdn('/static/images/new/m-managepro4.png') }}"/>
                        <span class="txt">
                            <p class="special"><i class="blueicon"></i><span>12%</span>借款利率</p>
                            <p><i class="blueicon"></i><span>12个月</span>借款期限</p>
                        </span>
                        <a href="/project/index" target="_blank" class="button"></a>
                    </li>
                </a>
            </ul>
        </div>
    </div>
    <!--
        新手疑问
    -->
    <div>
        <div class="m-newer">
            <i class="m-text3"></i>
            <i class="m-bgicon"></i>
            <ul>
                <li class="first">
                    <h3 class="color-white">九斗鱼收益为什么会高达12%，并且保证安全呢？</h3>

                    <p class="color-white">
                        九斗鱼依托耀盛中国11年金融服务经验为广大出借人提供优质的中小企业债权，这类中小企业都是传统银行机构不愿意服务的对象（因为借款额度低、财务资产比较分散不容易评估，银行更喜欢大企业），而耀盛依托RISKCALC风控系统定性定量的分析风险，通过降低风控成本，提高运营效率，可以更好的服务企业并让出借人享受高收益，而九斗鱼推出多重安全保障措施维护出借人本息安全。</p>
                </li>
                <li class="last">
                    <h3 class="color-black">很多平台跑路，九斗鱼如何避免?</h3>

                    <p class="color-gray">
                        九斗鱼是耀盛中国旗下的互联网理财平台，依托耀盛强大的集团实力和11年的金融经验可确保出借人的本息安全。从技术手段上来讲，通过RISKCALC风控技术将风险控制在合理范围内；其次，从道德上讲，耀盛中国及九斗鱼都要做百年企业，不仅恪守国家各项法律法规，更以超高的安全与服务标准自我约束，真正让广大出借人做到安心财有余。</p>
                </li>
            </ul>
        </div>
    </div>
    <!--
        品牌值得信赖
    -->
    <div class="m-reasoncontain">
        <div class="m-value">
            <i class="m-text4"></i>
            <ul>
                <li class="first"><img src="{{ assetUrlByCdn('/static/images/new/m-cctv.png') }}"></li>
                <li><img src="{{ assetUrlByCdn('/static/images/new/m-sina.png') }}"></li>
                <li><img src="{{ assetUrlByCdn('/static/images/new/m-wangyi.png') }}"></li>
                <li><img src="{{ assetUrlByCdn('/static/images/new/m-china.png') }}"></li>
            </ul>
        </div>
    </div>
    <div class="m-bottomshow">
        <a href="{{ App\Tools\ToolUrl::getUrl('/register') }}" class="bottombtn"></a>
    </div>
@endsection
