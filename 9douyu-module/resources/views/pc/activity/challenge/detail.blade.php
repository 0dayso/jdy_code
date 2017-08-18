@extends('pc.common.layout')

@section('title', '耀盛高校金融精英挑战赛，投票送2%加息券')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
@endsection
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/activity/challenge/css/challenge.css') }}">
    <style type="text/css">
        body{background: url("{{assetUrlByCdn('/static/activity/challenge/images/banner1.jpg')}}") center top no-repeat #061127;}
    </style>
        <div class="Js_tab_box">
                <div class="chall1-nav">
                    <div class="wrap">
                    <ul class="Js_tab_click">
                        <li ><a href="#1">郭鹏战队</a></li>
                        <li ><a href="#2">刘丽慷战队</a></li>
                        <li ><a href="#3">姜传震战队</a></li>
                        <li ><a href="#4">王艺筱战队</a></li>
                        <li ><a href="#5">刘凯战队</a></li>
                        <li ><a href="#6">臧克战队</a></li>
                    </ul>
                    </div>
                </div>
                <div class="wrap">
                <div class="Js_tab_main_click chall1-box" style="display:block;">
                    <div class="chall-title chall1-title">
                        <h2>挑战题目<span>金融科技如何更好地解决中小企业融资问题？</span></h2>
                    </div>
                    <dl class="chall1-pro">
                        <dt>
                            <div><img src="{{ assetUrlByCdn('/static/activity/challenge/images/tuto01.jpg') }}" /></div>
                            <p> <strong>导师:郭 鹏</strong></p>
                            <p>耀盛中国</p>
                            <p>金融科技事业群总裁</p>
                        </dt>
                        <dd>
                            <p>毕业于首都师范大学数学科学学院信息数学专业；</p>
                            <p>2005年起任北京天机移联科技有限公司营销总监，开创数字产品电子商务营销模式，并成功将天机移联打造为全国最大的数字产品分销商；</p>
                            <p>2009年起供职于北京钱袋宝支付技术有限公司，先后担任互联网事业部总监，产品运营中心总经理，商务合作中心总经理，主导钱袋宝扩展卡，钱袋宝小精灵，钱袋宝支付手机等创新支付产品的产品规划及设计工作；</p>
                            <p>2013年12月与合伙人共同创立互联网金融服务平台—九斗鱼。</p>
                            <p>2015年7月22日，中国第四届财经峰会上，郭鹏荣获“最佳青年榜样”奖项。</p>
                            <p>2016年8月，入选美国著名商业杂志《Fast Company》（快公司）中文版颁布的“中国商业最具创意人物100”</p>
                        </dd>
                    </dl>
                     <div class="chall-title chall2-title">
                        <h2>战队介绍</h2>
                        <p>昌航江财<span></span>Suzie Chou （粟子粥队）</p>
                    </div>
                    <dl class="chall1-pro1">
                        <dt><img src="{{ assetUrlByCdn('/static/activity/challenge/images/tuto-01.jpg') }}" /></dt>
                        <dd>
                            <p>我们是“栗子粥队”，来自南昌航空工业学院和江西财经大学。“没有完美的个人，只有完美的团队”是我们团队的理念。下一部分是对我们的成员的介绍。</p>
                            <p>刘子红：获得铜牌得主书法比赛、摄影比赛；</p>
                            <p>陈敏丽：学生会副主席以及南昌航空工业学院社区协会的副会长，主修会计；在中国光大银行南昌分行实习生；有沟通技巧和管理能力</p>
                            <p>方舟：国际经济与贸易专业；2016获得挑战杯一等奖。具有较强的英语和日语交流和写作。</p>
                            <p>“猛虎细嗅蔷薇”一首美丽的诗，这是苏子锷筹最好的描述。</p>
                        </dd>
                    </dl>
                    <!-- <a href="javascript:;" class="chall1-btn challengeVote" attr-vote-item="10" >投我一票</a> -->

                    <div class="chall-title chall3-title">
                        <p>对外经济贸易大学<span></span>Cosmos队</p>
                    </div>
                    <dl class="chall1-pro1 chall1-pro2">
                        <dt><img src="{{ assetUrlByCdn('/static/activity/challenge/images/tuto-02.jpg') }}" /></dt>
                        <dd>
                            <p>投疯了！听说14亿人都在关注→</p>
                            <p>浩瀚无边的黑暗尽头，闪烁着点点星光，这就是我们。</p>
                            <p>我们来自宇宙，我们是cosmos。</p>
                            <p>现在，从宇宙深处空降而来的贸大小分队，正式登陆蓝筹赛区赛！</p>
                            <p>践行用美食和见识滋养自己的情怀安</p>
                            <p>笃信脚踏实地力争上游的奋斗仪</p>
                            <p>立志修身齐家闯世界的文艺琪</p>
                            <p>三个性格迥异却又志趣相同的执着青年，带着来自宇宙的洪荒之力，突破一个又一个最好的自己！</p>
                            
                        </dd>
                    </dl>
                    <!-- <a href="javascript:;" class="chall1-btn challengeVote " attr-vote-item="11" >投我一票</a> -->

                </div>
                <div class="Js_tab_main_click chall1-box" >
                        <div class="chall-title chall1-title">
                            <h2>挑战题目<span>网贷平台综合竞争力主要体现哪些方面，如何提升？</span></h2>
                        </div>
                        <dl class="chall1-pro">
                            <dt>
                                <div><img src="{{ assetUrlByCdn('/static/activity/challenge/images/tuto02.jpg') }}" /></div>
                                <p> <strong>导师:刘丽慷</strong></p>
                                <p>九斗鱼联合创始人</p>
                            </dt>
                            <dd>
                                <p>毕业于首都师范大学计算机科学与技术专业; </p>
                                <p>2005年毕业后进入 360 参与开发第一版病毒库管理平台; </p>
                                <p>2007年加入百度，负责百度指数与百度之星程序大赛; 2009年作为创始合伙人带领技术团队开发彩吏网彩票平台; </p>
                                <p>2010年加入开心网，负责平台架构改进，带领团队开发广受好评的手机游戏； </p>
                                <p>2013 年加入全球著名手机游戏开发商 Kabam，负责开发与改进收入高达数亿美金的游戏-亚瑟王国;</p>
                                <p>2014年担任九斗鱼首席技术官，2016年开始担任九斗鱼执行总经理，负责九斗鱼投资平台的研发及技术团队管理。</p>
                            </dd>
                        </dl>
                         <div class="chall-title chall2-title">
                            <h2>战队介绍</h2>
                            <p>河北农业大学<span></span>三才九尾队</p>
                        </div>
                        <dl class="chall1-pro1 chall1-pro3">
                            <dt><img src="{{ assetUrlByCdn('/static/activity/challenge/images/tuto-03.jpg') }}" /></dt>
                            <dd>
                                <p>“三才九尾”团队来自河北农业大学，团队名称取自“士亦有三才：视才，识才，智才”以及“王法修明，三才得所，九尾狐现”。 </p>
                                <p>队长：马铭希</p>
                                <p>个人介绍：爱阅读、爱旅行，想跟着自己的心去做想做的事情，一直在努力前进不忘初心，我们的选择远比我们的能力更能表明我们是怎样的人，曾获“万人之上”金融挑战赛校内赛优胜奖，全国大学生英语能力竞赛第三名和普惠金融人才成长集训营结业证明</p>
                                <p>队员：张嘉嘉</p>
                                <p>个人介绍：喜爱风险投资，户外运动和阅读，想在未来将自己塑造成为行业中的精英，以最伟大的对冲基金经理之一——詹姆斯▪西蒙斯作为偶像，胜而后战则是一直坚持的信念，获得过北大赛第26名，“万人之上”校内赛优胜奖，FFP第44名（共7757人）</p>
                                <p>队员:祝博轩</p>
                                <p>个人介绍：乐天派、爱冒险，有生活情趣。在校期间参加多项活动，如“2016万人之上金融挑战赛”华北赛区获“优秀选手”称号，在“大学生创新创业”任省级立项负责人，在“挑战杯创业大赛”中获校级二等奖。</p>
                            </dd>
                        </dl>
                        

                        <div class="chall-title chall3-title">
                            <p>湖北经济学院法商学院<span></span>华尔街之狼队</p>
                        </div>
                        <dl class="chall1-pro1 chall1-pro4">
                            <dt><img src="{{ assetUrlByCdn('/static/activity/challenge/images/tuto-04.jpg') }}" /></dt>
                            <dd>
                                <p>世间的事情都是一样，从来都不是看到希望才努力，而是看不到希望也要努力，这样才能抓住机会，这就是坚韧</p>
                                <p>愿得E Offer,白首不分离。</p>

                            </dd>
                        </dl>
                        

                </div>
                <div class="Js_tab_main_click chall1-box">
                    <div class="chall-title chall1-title">
                        <h2>挑战题目<span>金融科技在智能支付管理服务行业的创新与实践？</span></h2>
                    </div>
                    <dl class="chall1-pro chall1-pro5">
                        <dt>
                            <div><img src="{{ assetUrlByCdn('/static/activity/challenge/images/tuto03.jpg') }}" /></div>
                            <p> <strong>导师:姜传震</strong></p>
                            <p>耀盛中国</p>
                            <p>支付事业部副总裁</p>
                        </dt>
                        <dd>
                            <p>2009年，东北大学计算机科学与技术专业毕业后加入北京钱袋宝支付技术有限公司，担任银行商务合作部总监，主要负责商业银行、银联等金融机构的合作对接工作，并全程参与钱袋宝支付牌照申请工作。</p>
                            <p>2012年，以为持牌支付公司银行卡收单业务独家提供招商及市场拓展服务为方向开始自主创业，三年时间累计拓展服务银行卡收单商户数十万，日交易额超过5亿元。开启国内首家与支付公司合作、创立自主出资为银行卡收单商户提供结算款T0垫付业务模式先河。</p>
                            <p>2015年10月加入耀盛中国，联合创立支付终端管理平台“普付宝”，用科技手段升级小微商户的综合经营能力，进而支撑起小微商户销售全过程，汇集资金流、商品流、信息流等全面信息，覆盖消费者、商户和各级供应商。透过大数据建立为小微商户提供基于收银需求的多样增值业务服务。</p>
                        </dd>
                    </dl>
                     <div class="chall-title chall2-title">
                        <h2>战队介绍</h2>
                        <p>吉林大学<span></span>JLU&VC之鹰队</p>
                    </div>
                    <dl class="chall1-pro1 chall1-pro6">
                        <dt><img src="{{ assetUrlByCdn('/static/activity/challenge/images/tuto-05.jpg') }}" /></dt>
                        <dd>
                            <p>“JLU&VC之鹰”队来自吉林大学经济学院，成员分别是2015级金融专业的李文璇（队长））、张雨婷和2014级国际经济与贸易的专业的刘杰靖。我们秉持着“若成大业、金融为器”的信念，对金融各领域有着浓厚的兴趣，并立志在金融之路上能有所作为。团队的口号是“VC之鹰，搏击苍穹，蓄养深厚，一飞冲天”。团队成员各有所长，但共同的特征是执着信念；不抛弃，不放弃，不惧艰险，勇为人先的精神。团队成员成绩优秀，有较强的学习能力与团队意识，均是校奖学金获得者，曾公开发表论文并在创新创业、企业沙盘等比赛中获得国家级、省级荣誉；且注重实操锻炼，均有公司实习、企业研习等相关经历，并十分渴望进一步深入了解金融行业内部情况。</p>
                            <p>我们也许尚且稚嫩，但心中有一个崇高的梦想，想要成为金融精英、职业高手，更希望成为中国真正意义上的金融家、投资家，用自己的学识与积淀，让祖国在金融的路上走得更加昂首阔步！</p>
                            <p>VC之鹰，我们来了！</p>
                        </dd>
                    </dl>
                    
                </div>
                <div class="Js_tab_main_click chall1-box">
                    <div class="chall-title chall1-title">
                        <h2>挑战题目<span>移动互联网时代互联网金融平台怎样提升流量？</span></h2>
                    </div>
                    <dl class="chall1-pro chall1-pro5">
                        <dt>
                            <div><img src="{{ assetUrlByCdn('/static/activity/challenge/images/tuto04.jpg') }}" /></div>
                            <p> <strong>导师:王艺筱</strong></p>
                            <p>九斗鱼运营总监</p>
                        </dt>
                        <dd>
                            <p class="chall1-pt100px">七年互联网品牌及市场运营经验,资深策划人。具有敏锐的的市场洞察力与运营决策力,拥有丰富的市场及媒体资源,深谙各线上线下渠道的优劣势及执行策略,对市场战略规划具有良好的把控力。对移动互联及产品运营有独到的实操经验。</p>
                        </dd>
                    </dl>
                     <div class="chall-title chall2-title">
                        <h2>战队介绍</h2>
                        <p>南开大学<span></span>蓝开Elites</p>
                    </div>
                    <dl class="chall1-pro1 chall1-pro3">
                        <dt><img src="{{ assetUrlByCdn('/static/activity/challenge/images/tuto-06.jpg') }}" /></dt>
                        <dd>
                            <p>团队名称：蓝开Elites</p>
                            <p>教育水平：飞鸟二世在南开大学</p>
                            <p>团队信条：什么事都要有所尝试</p>
                            <p>团队的目标：追求卓越</p>
                            <p>团队特征：</p>
                            <p>强大的金融背景----金融与金融工程专业；扎实的财务知识基础</p>
                            <p>丰富经验----实习、海外交流、金融中心的竞赛、志愿者活动、学生社团</p>
                            <p>高团队精神----活跃的讨论组，不同的理念和高效的团队合作</p>
                            <p>通用性强</p>
                            <p>良好的英语水平</p>
                        </dd>
                    </dl>
                    
                </div>
                <div class="Js_tab_main_click chall1-box">
                    <div class="chall-title chall1-title">
                        <h2>挑战题目<span>面向理财小白的年轻人，如何讲好九斗鱼故事？</span></h2>
                    </div>
                    <dl class="chall1-pro chall1-pro5">
                        <dt>
                            <div><img src="{{ assetUrlByCdn('/static/activity/challenge/images/tuto05.jpg') }}" /></div>
                            <p> <strong>导师:刘 凯</strong></p>
                            <p>九斗鱼市场总监</p>
                        </dt>
                        <dd>
                            <p class="chall1-pt86px">毕业于南京大学金融专业，十多年金融行业从业经验，曾经服务过“苏宁电器”与《南方周末·人物周刊》，在市场营销和品牌推广方面有着丰富的经验。</p>
                            <p>加入九斗鱼两年多以来，带领市场部团队，将九斗鱼以及耀盛中国的产品和品牌向广大用户进行传播，极大的扩大了九斗鱼以及耀盛中国的市场品牌影响力和美誉度。</p>
                        </dd>
                    </dl>
                     <div class="chall-title chall2-title">
                        <h2>战队介绍</h2>
                        <p>天津外国语大学<span></span>重案六组队</p>
                    </div>
                    <dl class="chall1-pro1">
                        <dt><img src="{{ assetUrlByCdn('/static/activity/challenge/images/tuto-07.jpg') }}" /></dt>
                        <dd>
                            <p>一个是国奖拿到手软，通过十科ACCA全球统考的清新小美女；一个是点子无限迸发，CFA出坑中并且拥有上万粉丝的公众号博主；一个是沉迷于各类大赛，拿到ACCA JHC 华北区四强和东北亚英文辩论赛优秀裁判的老司机。英语八级，CFA一级，ACCA，证券从业，基金从业，BEC高级，全球IC3计算机认证，对待专业我们是严肃的；达沃斯论坛，新领军者年会，世界矿业大会，中国大学生金融联合会的志愿者，二级茶艺师，摄影，话剧，合唱团，视频制作，热爱长跑，对待生活我们是认真的。重案六组，一个热爱金融，热爱分析，热爱英语，无节操但是有底线的青春美少女组合！</p>
                        </dd>
                    </dl>
                    

                </div>
                <div class="Js_tab_main_click chall1-box">
                    <div class="chall-title chall1-title">
                        <h2>挑战题目<span>用户体验为王的时代，怎样做好客户服务？</span></h2>
                    </div>
                    <dl class="chall1-pro chall1-pro5">
                        <dt>
                            <div><img src="{{ assetUrlByCdn('/static/activity/challenge/images/tuto06.jpg') }}" /></div>
                            <p> <strong>导师:臧 客</strong></p>
                            <p>九斗鱼客服总监</p>
                        </dt>
                        <dd>
                            <p class="chall1-pt50px">2000年毕业于中国人民大学企管与人力资源专业，在职研究生学历。</p>
                            <p>2000--2005年，华旗资讯，专卖店主管，北京渠道经理，西南区域经理</p>
                            <p>2006--2009年，艾维普思客服部经理</p>
                            <p>2010--2011年，纳斯达克上市教育集团，呼叫中心高级顾问
</p>
                            <p>2012--2014年，朗致药业集团客服部总监</p>
                            <p>2014年--至今，耀盛中国-九斗鱼事业部-客服总监</p>
                        </dd>
                    </dl>
                     <div class="chall-title chall2-title">
                        <h2>战队介绍</h2>
                        <p>中央财经大学<span></span>金牛队</p>
                    </div>
                    <dl class="chall1-pro1">
                        <dt><img src="{{ assetUrlByCdn('/static/activity/challenge/images/tuto-08.jpg') }}" /></dt>
                        <dd>
                            <p>我们是中央财经大学15级的本科生，大家在金融学院相知相识。志趣相投的我们为了共同的诉求走在一起，同侪共济。在追梦的途中，坎坷，辛酸，苦楚，我们一同面对；在成功的面前，喜悦，荣誉，欢笑，我们一同分享。始终不放弃的是我们天生的傲骨，永不言败的我们始终在超越自我。我们坚信在无数的风暴中将永远岿然屹立，在未来的旅程将挥舞着我们的旗帜，豪迈向前。</p>
                        </dd>
                    </dl>
                    

                </div>
            </div>
        </div>
        <!-- 弹窗 -->
    @if($userStatus['status'] != true)
        <div class="chall1_alert">
            <div class="chall1-mask"></div>
            <div class="chall1_box">
                <h3><dt>未登录</dt><span class="chall1-close"></span></h3>
                <div class="chall1-content">
                    <p class="chall1-n">别着急您还没有登录呢</p>
                    <a href="/login" class="chall1-btn1">立即登录</a>
                </div>
            </div>
        </div>
    @else

        <div class="chall1_alert">
            <div class="chall1-mask"></div>
            <div class="chall1_box">
                <h3><dt></dt><!-- 恭喜你 --><!-- 对不起 --><span class="chall1-close"></span></h3>
                <div class="chall1-content">
                    <!-- 未登录 -->
                    <!-- <p class="chall1-n">别着急您还没有登录呢</p>
                    <a href="#" class="chall1-btn1">立即登录</a> -->
                    <!-- 投票成功 -->
                    <p class="chall1-y">投票失败</p>
                    <p class="chall1-y1">加息券将于五日内发送到您的九斗鱼账户</p>
                    <!-- 已经投过 -->
                    <p class="chall1-y2">加息券将于五日内发送到您的九斗鱼账户</p>
                </div>
            </div>
        </div>
    @endif

    <script type="text/javascript">

    $(document).ready(function() {
        var paramSting  = location.hash;
        var param       = 1;
        if(paramSting){
            param       = paramSting.split("#")[1];
        }

        $(".Js_tab_click li").eq(param-1).addClass("cur").siblings(".Js_tab_click li").removeClass("cur");
        $(".Js_tab_main_click").eq(param-1).show().siblings(".Js_tab_main_click").hide();

//       $(".chall1-btn").click(function(e){
//        e.preventDefault();
//            $(".chall1_alert").fadeIn();
//        });
        $(".chall1-mask,.chall1-close").click(function(){
            $(".chall1_alert").fadeOut();
        })

        $(".challengeVote").click(function(e){

            var vote       =   $(this).attr("attr-vote-item");
            var loginStatus=   '{{$userStatus['status']}}';

            if( loginStatus != true ){
                e.preventDefault();
                $(".chall1_alert").fadeIn();
                return false;
            }
            var lock    =   $(".challengeVote").attr("lock-status");
            if( lock == 'closed'){
                return false;
            }
            $(".challengeVote").attr("lock-status",'closed');

            $.ajax({
                url      :"/activity/challenge/doVote",
                dataType :'json',
                data     :{choices:vote},
                type     :'post',
                success : function(json){

                    var title   = '';
                    var content = '';
                    $(".chall1_box").find("dt").html("");
                    $(".chall1-content").html("");
                    if( json.status ==false ){

                        title   ="对不起";
                        content = '<p class="chall1-y">投票失败</p>'+
                                        '<p class="chall1-y1">'+json.msg+'</p>';

                    }else{
                        title   ="恭喜您";
                        content = '<p class="chall1-y">投票成功</p>'+
                                '<p class="chall1-y1">加息券将于五日内发送到您的九斗鱼账户</p>';

                    }

                    $(".chall1_box").find("dt").html(title);
                    $(".chall1-content").html(content);
                    $(".challengeVote").attr("lock-status","opened");
                    e.preventDefault();
                    $(".chall1_alert").fadeIn();
                    return false;
                },
                error : function(msg) {
                    alert('领取失败，请稍候再试');
                    $(".challengeVote").attr("lock-status",'opened');
                }
            })

        })
            
    })

     // tab click
    function tabclick(tab,tabmain,cur){
        $(tab).click(function(){
            var index = $(tab).index(this);
            $(this).addClass(cur).siblings(tab).removeClass(cur);
            $(tabmain).eq(index).show().siblings(tabmain).hide();
        })
    };
    tabclick('.Js_tab_click li','.Js_tab_main_click','cur');
</script>
@endsection



