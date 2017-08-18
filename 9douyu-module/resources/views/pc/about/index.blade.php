@extends('pc.common.layout')
@section('title', $title)
@section('keywords', $keywords)
@section('description', $description)
@section('content')
@include('pc.about.common.menu')
    <!-- 九斗鱼介绍 -->
    <div>
        <div class="m-introduction">
            <h3>九斗鱼介绍</h3>
            <p class="engname">INTRODUCTION</p>
            <p class="txt"></p>
            <div class="m-pictxt">
                <img src="{{assetUrlByCdn('/static/images/new/m-logo-new.png')}}" />
                <div class="emptys"></div>
                <p class="mb25">九斗鱼隶属于国内领先的综合性金融服务集团——耀盛投资管理集团（以下简称“耀盛中国”），是其旗下的互联网金融平台，于2014年6月正式上线。</p>
                <p>九斗鱼积极拥抱监管，坚持用专业的风控方法、领先的科技手段为借款人和出借人提供安全高效的资金撮合服务。平台上线以来，已服务借款企业、个人超3000家。</p>
                
            </div>
            <div class="m-txtintro">
                <div class="flmr">
                    <p><span>我们不颠覆，融合大于竞争。</span>我们深信传统金融与互联网金融都是开创美好社会的金融支撑力，平台愿携手各类金融机构共同助力实体经济、服务大众创业、实现万众创新</p>
                    <p><span>我们不搅局，尊重监管、恪守规则。</span>我们深信对金融创新的最大保护就是拥有一个合规合法、公平公正的环境。我们严防各类法律风险、信贷风险、关联风险、披露风险，确保平台项目安全、资金安全、账户安全、信息安全</p>
                    <p><span>我们不投机，时刻敬畏风险。</span>互联网金融的本质仍属于金融，没有改变金融经营风险的本质属性。我们运用自主掌握的现代金融理论与核心技术——瑞思科雷中小企业信用评价系统，管控风险，以获得长远的、合理的风险溢价</p>
                    <p><span>我们不浮躁，坚守“工匠精神”。</span>我们知道信任需要时间验证，安全更需要经济周期的考量。让每一位“鱼客”“安心”，是我们这群“金融匠人”不断雕琢产品、不断改善体验的职责所在。当然，我们也享受着产品在“鱼客”口碑中升华的过程</p>
                </div>
                <img src="{{assetUrlByCdn('/static/images/new/m-paper-new-replace.jpg')}}" />
            </div>
        </div>
    </div>
<!--
       管理团队
   -->
<div class="m-honor m-marginshezhi ys-team">
    <h3>管理团队</h3>
    <p>MANAGEMEN</p>
    <p class="txt mb34"></p>
    <ul class="piclist">
        <li class="firstli">
            <a href="javascript:;" data-target="team-module1"><img alt="郭鹏-九斗鱼创始人" src="{{assetUrlByCdn('/static/images/new/about/team-guopeng.jpg')}}" /></a>
            <h3>郭鹏</h3>
            <p>九斗鱼创始人</p>
        </li>
        <li>
            <a href="javascript:;" data-target="team-module2"><img alt="刘丽慷-联合创始人" src="{{assetUrlByCdn('/static/images/new/about/team-liulikang.jpg')}}" /></a>
            <h3>刘丽慷</h3>
            <p>联合创始人</p>
        </li>
        <li>
            <a href="javascript:;" data-target="team-module5"><img alt="廖祜秋-首席工程师" src="{{assetUrlByCdn('/static/images/new/about/team-liaohuqiu.jpg')}}" /></a>
            <h3>廖祜秋</h3>
            <p>首席工程师</p>
        </li>
        <li>
            <a href="javascript:;" data-target="team-module4"><img alt="孔令珍-首席财务官" src="{{assetUrlByCdn('/static/images/new/about/team-konglingzhen.jpg')}}" /></a>
            <h3>孔令珍</h3>
            <p>首席财务官</p>
        </li>
        
        
    </ul>
</div>
<!--管理团队 弹层 -->
<div class="layer_wrap js-mask" data-modul="team-module1">
    <div class="Js_layer_mask layer_mask ys-layer-mask" data-toggle="mask" data-target="js-mask"></div>
    <div class="Js_layer layer ys-layer">
        <a href="javascript:;" class="ys-layer-close" data-toggle="mask" data-target="js-mask"></a>
        <div class="layer_con">

            <div class="ys-team-module">
                <div class="ys-team-head fl">
                    <img alt="郭鹏-九斗鱼创始人" src="{{assetUrlByCdn('/static/images/new/about/team-guopeng.jpg')}}" />
                </div>
                <h2 class="ys-team-name fl">郭鹏<span>九斗鱼创始人</span></h2>
                <div class="clearfix"></div>
                <div class="ys-team-detail">
                    <p>毕业于首都师范大学数学科学学院信息数学专业；</p>
                    <p>2005年起任北京天机移联科技有限公司营销总监，开创数字产品电子商务营销模式，并成功将天机移联打造为全国最大的数字产品分销商；
                    </p>
                    <p>2009年起供职于北京钱袋宝支付技术有限公司，先后担任互联网事业部总监，产品运营中心总经理，商务合作中心总经理，主导钱袋宝扩展卡，钱袋宝小精灵，钱袋宝支付手机等创新支付产品的产品规划及设计工作；</p>
                    <p>2013年12月与合伙人共同创立互联网金融服务平台—九斗鱼。</p>
                    <p>2015年7月22日，中国第四届财经峰会上，郭鹏荣获“最佳青年榜样”奖项。</p>
                    <p>2016年8月，入选美国著名商业杂志《Fast Company》（快公司）中文版“中国商业最具创意人物100”
                    </p>
                </div>
            </div>
        </div>


    </div>
</div>
<div class="layer_wrap js-mask" data-modul="team-module2">
    <div class="Js_layer_mask layer_mask ys-layer-mask" data-toggle="mask" data-target="js-mask"></div>
    <div class="Js_layer layer ys-layer">
        <a href="javascript:;" class="ys-layer-close" data-toggle="mask" data-target="js-mask"></a>
        <div class="layer_con">

            <div class="ys-team-module">
                <div class="ys-team-head fl">
                    <img alt="刘丽慷-联合创始人" src="{{assetUrlByCdn('/static/images/new/about/team-liulikang.jpg')}}" />
                </div>
                <h2 class="ys-team-name fl">刘丽慷<span>联合创始人</span></h2>
                <div class="clearfix"></div>
                <div class="ys-team-detail">
                    <p>毕业于首都师范大学计算机科学与技术专业;
                    </p>
                    <p>2005年毕业后进入 360 参与开发第一版病毒库管理平台;
                    </p>
                    <p>2007年加入百度，负责百度指数与百度之星程序大赛; 2009年作为创始合伙人带领技术团队开发彩吏网彩票平台;
                    </p>
                    <p>2010年加入开心网，负责平台架构改进，带领团队开发广受好评的手机游戏；</p>
                    <p>2013 年加入全球著名手机游戏开发商 Kabam，负责开发与改进收入高达数亿美金的游戏-亚瑟王国;
                    </p>
                    <p>2014年担任九斗鱼首席技术官，2016年开始担任九斗鱼执行副总经理，负责九斗鱼投资平台的研发及技术团队管理。
                    </p>
                </div>
            </div>
        </div>


    </div>
</div>

<div class="layer_wrap js-mask" data-modul="team-module4">
    <div class="Js_layer_mask layer_mask ys-layer-mask" data-toggle="mask" data-target="js-mask"></div>
    <div class="Js_layer layer ys-layer">
        <a href="javascript:;" class="ys-layer-close" data-toggle="mask" data-target="js-mask"></a>
        <div class="layer_con">

            <div class="ys-team-module">
                <div class="ys-team-head fl">
                    <img alt="孔令珍-首席财务官" src="{{assetUrlByCdn('/static/images/new/about/team-konglingzhen.jpg')}}" />
                </div>
                <h2 class="ys-team-name fl">孔令珍<span>首席财务官 </span></h2>
                <div class="clearfix"></div>
                <div class="ys-team-detail">
                    <p>先后就读于对外经济贸易大学、北方交通大学的财务专业，拥有近20年的大中型企业财务管理从业经验。
                    </p>
                    <p>2004年加入“嘉信保险”，出任集团财务经理
                    </p>
                    <p>2013年加入“崇高妈妈”，出任公司财务总监
                    </p>
                    <p>2015年加入“耀盛中国”，出任首席财务官。
                    </p>
                    <p>目前负责耀盛中国总体的会计、报表、预算等工作；负责制定公司利润计划、资本投资、财务规划、销售前景、开支预算、成本标准等；负责建立健全公司内部核算的组织、指导和数据管理体系，以及核算和财务管理的规章制度；组织公司有关部门开展经济活动分析，组织编制公司财务计划、成本计划，努力降低成本、增收节支、提高效益。
                    </p>
                </div>
            </div>
        </div>


    </div>
</div>
<div class="layer_wrap js-mask" data-modul="team-module5">
    <div class="Js_layer_mask layer_mask ys-layer-mask" data-toggle="mask" data-target="js-mask"></div>
    <div class="Js_layer layer ys-layer">
        <a href="javascript:;" class="ys-layer-close" data-toggle="mask" data-target="js-mask"></a>
        <div class="layer_con">

            <div class="ys-team-module">
                <div class="ys-team-head fl">
                    <img alt="廖祜秋-首席工程师" src="{{assetUrlByCdn('/static/images/new/about/team-liaohuqiu.jpg')}}" />
                </div>
                <h2 class="ys-team-name fl">廖祜秋<span>首席工程师</span></h2>
                <div class="clearfix"></div>
                <div class="ys-team-detail">
                    <p>毕业于北京航空航天大学，先后获『飞行器动力工程』专业学士学位，『航空宇航推进理论和工程』专业硕士学位。
                    </p>
                    <p>出于对互联网行业和计算机前沿技术的热爱，放弃继续攻读博士学位的机会，2011年进入开心网（kaixin001）工作。同年成为开心网最年轻的技术经理。
                    </p>
                    <p>先后在开心网，阿里巴巴，美国硅谷等地工作，多年一线国内外顶级互联网公司工作经验。曾为多个创业公司担任技术顾问，擅长于架构设计，应用开发，性能调优，系统安全等领域。
                    </p>
                    <p>2016 年加入耀盛中国，致力于不断提升耀盛中国的技术和工程能力，并将耀盛中国打造成一个对优秀的工程师极具吸引力的企业。
                    </p>
                    
                </div>
            </div>
        </div>


    </div>
</div>

    <!--
        九斗鱼优势
    -->
   {{--  <div class="m-advantage">
        <h3>九斗鱼优势</h3>
        <p>ADVANTAGE</p>
        <p class="txt"></p>
        <ul>
            <li class="m-firstli">
                <img src="{{assetUrlByCdn('/static/images/new/m-advantage1.png')}}" />
                <h3>雄厚实力</h3>
                <p>注册资本金6000万，11年金融风控经验，公司业务遍布全国各地。</p>
            </li>
            <li>
                <img src="{{assetUrlByCdn('/static/images/new/m-advantage2.png')}}" />
                <h3>高稳收益</h3>
                <p>最高借款利率12%，35倍银行活期存款， 1元起投， 期限灵活，随时变现</p>
            </li>
            <li>
                <img src="{{assetUrlByCdn('/static/images/new/m-advantage3.png')}}" />
                <h3>本息安全</h3>
                <p>银行资金监管，千万风险准备金及多重保障方式</p>
            </li>
            <li>
                <img src="{{assetUrlByCdn('/static/images/new/m-advantage4.png')}}" />
                <h3>风控严谨</h3>
                <p>国际领先的risckcalc风控系统定量分析风险，专业的风控团队层层把关，完善的风险保障机制</p>
            </li>
        </ul>
    </div> --}}
    <!--
        荣誉墙
    -->
    <div class="m-honor">
        <h3>荣誉墙</h3>
        <p>HONOR</p>
        <p class="txt"></p>
        <ul>
            <li>
                <img alt="九斗鱼荣誉证明" src="{{assetUrlByCdn('/static/images/new/01.jpg')}}">
                <div class="black-bg"></div>
            </li>
            <li>
                <img src="{{assetUrlByCdn('/static/images/new/02.jpg')}}">
                <div class="black-bg"></div>
            </li>
            <li>
                <img src="{{assetUrlByCdn('/static/images/new/03.jpg')}}">
                <div class="black-bg"></div>
            </li>
            <li>
                <img src="{{assetUrlByCdn('/static/images/new/04.jpg')}}">
                <div class="black-bg"></div>
            </li>
            <li>
                <img src="{{assetUrlByCdn('/static/images/new/05.jpg')}}">
                <div class="black-bg"></div>
            </li>
        </ul>
    </div>
    <!--
        关于我们
    -->
    <div class="m-honor m-marginshezhi">
        <h3>关于我们</h3>
        <p>ABOUT US</p>
        <p class="txt mb34"></p>
        <ul class="piclist aboutus">
            <li>
                <a rel="example_group" href="{{assetUrlByCdn('/static/images/new/pc2-photo-01-new.png')}}" title=" "><img alt="九斗鱼公司环境" src="{{assetUrlByCdn('/static/images/new/pc2-photo-small-01-new.png')}}" /></a>
                <div class="black-bg"></div>
            </li>
            <li>
                <a rel="example_group" href="{{assetUrlByCdn('/static/images/new/pc2-photo-02-new.png')}}" title=" "><img src="{{assetUrlByCdn('/static/images/new/pc2-photo-small-02-new.png')}}" /></a>
                <div class="black-bg"></div>
            </li>
            <li>
                <a rel="example_group" href="{{assetUrlByCdn('/static/images/new/pc2-photo-03-new.png')}}" title=" "><img src="{{assetUrlByCdn('/static/images/new/pc2-photo-small-03-new.png')}}" /></a>
                <div class="black-bg"></div>
            </li>
            <li>
                <a rel="example_group" href="{{assetUrlByCdn('/static/images/new/pc2-photo-04-new.png')}}" title=" "><img src="{{assetUrlByCdn('/static/images/new/pc2-photo-small-04-new.png')}}" /></a>
                <div class="black-bg"></div>
            </li>
            <li>
                <a rel="example_group" href="{{assetUrlByCdn('/static/images/new/pc2-photo-05-new.png')}}" title=" "><img src="{{assetUrlByCdn('/static/images/new/pc2-photo-small-05-new.png')}}" /></a>
                <div class="black-bg"></div>
            </li>
            <li>
                <a rel="example_group" href="{{assetUrlByCdn('/static/images/new/pc2-photo-06-new.png')}}" title=" "><img src="{{assetUrlByCdn('/static/images/new/pc2-photo-small-06-new.png')}}" /></a>
                <div class="black-bg"></div>
            </li>
            <li>
                <a rel="example_group" href="{{assetUrlByCdn('/static/images/new/pc2-photo-07-new.png')}}" title=" "><img src="{{assetUrlByCdn('/static/images/new/pc2-photo-small-07-new.png')}}" /></a>
                <div class="black-bg"></div>
            </li>
            <li>
                <a rel="example_group" href="{{assetUrlByCdn('/static/images/new/pc2-photo-08-new.png')}}" title="公司前台"><img src="{{assetUrlByCdn('/static/images/new/pc2-photo-small-08-new.png')}}" /></a>
                <div class="black-bg"></div>
            </li>
            <li>
                <a rel="example_group" href="{{assetUrlByCdn('/static/images/new/pc2-photo-09-new.png')}}" title=" "><img src="{{assetUrlByCdn('/static/images/new/pc2-photo-small-09-new.png')}}" /></a>
                <div class="black-bg"></div>
            </li>
            <li>
                <a rel="example_group" href="{{assetUrlByCdn('/static/images/new/pc2-photo-10-new.png')}}" title=" "><img src="{{assetUrlByCdn('/static/images/new/pc2-photo-small-10-new.png')}}" /></a>
                <div class="black-bg"></div>
            </li>
        </ul>
    </div>

    <!--
        公司证件
    -->
    <div class="m-honor m-marginshezhi">
        <h3>公司证件</h3>
        <p>documents</p>
        <p class="txt mb34"></p>
        <ul class="piclist aptitude piclist1">

            <li>
                <a rel="example_group" href="{{assetUrlByCdn('/static/images/new/pc2-document-img6.jpg')}}" title=" "><img alt="九斗鱼公司证件" src="{{assetUrlByCdn('/static/images/new/pc2-document-small-img6.jpg')}}" /></a>
                <div class="black-bg"></div>
            </li>
            <li>
                <a rel="example_group" href="{{assetUrlByCdn('/static/images/new/pc2-document-img7.jpg')}}" title=" "><img src="{{assetUrlByCdn('/static/images/new/pc2-document-small-img7.jpg')}}" /></a>
                <div class="black-bg"></div>
            </li>
            <li>
                <a rel="example_group" href="{{assetUrlByCdn('/static/images/new/pc2-document-img8.jpg')}}" title=" "><img src="{{assetUrlByCdn('/static/images/new/pc2-document-small-img8.jpg')}}" /></a>
                <div class="black-bg"></div>
            </li>
            <li>
                <a rel="example_group" href="{{assetUrlByCdn('/static/images/new/pc2-document-img9.jpg')}}" title=" "><img src="{{assetUrlByCdn('/static/images/new/pc2-document-small-img9.jpg')}}" /></a>
                <div class="black-bg"></div>
            </li>


        </ul>
    </div>
    <!--
        联系我们
    -->
    <div class="m-contact">
        <h3>联系我们</h3>
        <p>CONTACT US</p>
        <p class="txt mb44"></p>
        <ul>
            <li class="ml0">
                <a href="http://www.sobot.com/chat/pc/index.html?sysNum=54037ae382a141c8b7fa69f402a99b7c" target="_blank">
                    <img src="{{assetUrlByCdn('/static/images/new/m-contactus1-new.png')}}" />
                    <p>在线客服<br>
                        customer@9douyu.com</p>
                </a>
            </li>
            <li>
                <img src="{{assetUrlByCdn('/static/images/new/m-contactus2.png')}}" />
                <p>北京市朝阳区郎家园6号<br>
                    郎园vintage 2号楼A座2层
                </p>
            </li>
            <li>
                <img src="{{assetUrlByCdn('/static/images/new/m-telphone.png')}}" />
                <p>400-6686-568<br />
                    服务时间：9:00-18:00</p>
            </li>
            <li>
                <img src="{{assetUrlByCdn('/static/images/new/m-contactus4.png')}}" />
                <p>商务合作<br>
                    business@9douyu.com</p>
            </li>
        </ul>
    </div>
    <div class="m-bottomtip">
        <a href="{{ App\Tools\ToolUrl::getUrl('/register') }}" class="m-goregist">立即注册领取大礼包</a>
        <p><a href="/" >再看看，返回首页</a></p>
    </div>


@endsection

@section('jspage')
    <script>
        $(document).ready(function() {
            $("a[rel=example_group]").fancybox({
                'transitionIn'		: 'none',
                'transitionOut'		: 'none',
                'titlePosition' 	: 'over',
                'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
                    return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
                }
            });
        });


    </script>
@endsection