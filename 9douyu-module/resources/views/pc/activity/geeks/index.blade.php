@extends('pc.common.layout')

@section('title', '中国极客大奖年度评选')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('csspage')
    <meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/activity/geeks/css/geeks.css') }}">
    <script type="text/javascript" src="{{ assetUrlByCdn('/static/activity/geeks/js/imageflow.js') }}"></script>
@endsection
@section('content')
<input type="hidden" name="isReceived" id="isReceived" value="{{ $isReceived }}">
<input type="hidden" name="repeatHit"  id="repeatHit"  value="{{ $repeatHit }}">
<div class="g-banner">
    <div class="wrap">
        <!-- top -->
        <div class="g-top">
            <div class="g-logo">
                <img src="{{ assetUrlByCdn('/static/activity/geeks/images/logo.png') }}" width="340" height="104" alt="中国极客大奖年度评审">
            </div>
            <div class="g-menu">
                <a href="#spirit" class="hover">极客精神</a>
                <a href="#awards">奖项设置</a>
                <a href="#pioneer">对话创客先锋</a>
                <a href="#list">获奖名单</a>
                <a href="#report">媒体报道</a>
                <a href="#media">媒体支持</a>
            </div>
        </div>
        <div class="clear"></div>
        <a name="spirit"></a>
        <div class="g-summary">
            <p>他们崇尚科技、自由、与创新；他们勇于创造，敢为天下先，不受创同商业模式羁绊；</p>
            <p>他们洞察人性，却不失本色，他们历尽坎坷，却不言放弃；</p>
            <p>他们不懂投机取巧，只会乘风破浪，砥砺前行；</p>
            <p>无论过去，还是现在，他们都是中国梦的最佳编织者；</p>
            <p>他们身上有着共同的标签：<strong>极客！</strong></p>
        </div>

        

    </div>
</div>
<div class="wrap">
    <!-- loop -->
    <div id="LoopDiv">
        <input id="S_Num" type="hidden" value="2" />
        <div id="starsIF" class="imageflow">
            <img src="{{ assetUrlByCdn('/static/activity/geeks/images/nn1.png') }}" num="0" longdesc="/"  width="295" height="295">
            <img src="{{ assetUrlByCdn('/static/activity/geeks/images/gg1.png') }}" num="1" longdesc="/" width="295" height="295">
            <img src="{{ assetUrlByCdn('/static/activity/geeks/images/ll1.png') }}" num="2" longdesc="/" width="295" height="295">
            <img src="{{ assetUrlByCdn('/static/activity/geeks/images/dd1.png') }}" num="3" longdesc="/" width="295" height="295">
            <img src="{{ assetUrlByCdn('/static/activity/geeks/images/yy1.png') }}" num="4" longdesc="/"  width="295" height="295">
            
        </div>
            <div id="LoopTxt" class="caption">
                <div class="g-loop"><span id="LoopTitle">华为技术有限公司总裁</span><strong id="LoopName">任正非</strong></div>
                <div id="LoopQuote">Geek语录：企业发展就是要发展一批狼。狼有三大特性：一是敏锐的嗅觉；二是不屈不挠、奋不顾身的进攻精神；三是群体奋斗的意识。</div>
            </div>
    </div>
    <div class="clear"></div>
    <div class="g-line"></div>
</div>
<div class="g-wrap">
    <!-- video -->
    <div class="g-video">
        <img src="{{ assetUrlByCdn('/static/activity/geeks/images/title01.png') }}" width="442" height="28" ><br>
        <embed src="https://imgcache.qq.com/tencentvideo_v1/playerv3/TPout.swf?max_age=86400&v=20161117&vid=e03580igjla&auto=0" allowFullScreen="true" quality="high" width="580" height="460" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash"></embed>
        <p>耀盛中国 郭鹏</p>
    </div>
    <div class="g-line"></div>
    
    <!-- awards -->
    <div class="g-awards"><a name="awards"></a>
        <div class="g-title g-title1"></div>
        <div class="g-awards-main">
            <p>变局已然成为了我们这个时代的新常态，<br>蓬勃发展的技术代表着时代前进的脚步，<br>拥有极客精神的创新型企业才是未来的践行科学发展观的明星企业。</p>
            <p><strong>2016年度“极客大奖”</strong></p>
            <p>系列奖项从IT网络通信、新电商&互联网、手机&智能硬件、新能源汽车、人物这五个维度进行展开，共计50多个奖项。</p>
        </div>
        <ul class="g-awards-species">
            <li class="person">
                <p><span></span></p>
                <p><strong>人物类</strong></p>
                <p>年度人物</p>
                <p>创客先锋</p>
            </li>
            <li class="brand">
                <p><span></span></p>
                <p><strong>品牌类</strong></p>
                <p>最具影响力品牌</p>
                <p>最佳供应商</p>
                <p>最具成长潜力品牌</p>
            </li>
            <li class="product">
                <p><span></span></p>
                <p><strong>产品类</strong></p>
                <p>年度最佳产品</p>
                <p>年度最受欢迎产品</p>
                <p>最具竞争力产品</p>
            </li>
        </ul>
        <div class="g-awards-summary">
            <p><strong>覆盖领域</strong></p>
            <p>IT网络通信、新电商&互联网、手机&智能硬件、汽车科技&新能源汽车、金融科技(FinTech)</p>
        </div>
        <div class="g-line"></div>

    </div>

    <!-- pioneer -->
    <div class="g-pioneer"><a name="pioneer"></a>
        <div class="g-title g-title2"></div>
        <ul>
            <li>
                <p><img src="{{ assetUrlByCdn('/static/activity/geeks/images/pioneer01.jpg') }}" width="222" height="270" alt="刘强东"></p>
                <p><strong>刘强东</strong></p>
                <p>以极客精神打造智能生态链条<br>&nbsp;</p>
                <p><a href="javascript:;" id="p-btn">查看详情</a></p>
            </li>
            <li class="middle">
                <p><img src="{{ assetUrlByCdn('/static/activity/geeks/images/pioneer02.jpg') }}" width="222" height="270" alt="郭鹏"></p>
                <p><strong>郭鹏</strong></p>
                <p>立足金融科技 打破产品隔阂 <br>搭建小微金融生态圈</p>
                <p><a href="http://www.fromgeek.com/talking/77.html" target="_blank">查看详情</a></p>
            </li>
            <li>
                <p><img src="{{ assetUrlByCdn('/static/activity/geeks/images/pioneer03.jpg') }}" width="222" height="270" alt="王秀娟"></p>
                <p><strong>王秀娟</strong></p>
                <p>极尽颠覆 偏执创新<br>&nbsp;</p>
                <p><a href="http://www.fromgeek.com/talking/62.html" target="_blank">查看详情</a></p>
            </li>
            <li>
                <p><img src="{{ assetUrlByCdn('/static/activity/geeks/images/pioneer04.jpg') }}" width="222" height="270" alt="李凌霄"></p>
                <p><strong>李凌霄</strong></p>
                <p>将服装智能化 突破社交局限<br> 拓展未来智能发展方向</p>
                <p><a href="http://www.fromgeek.com/talking/72.html" target="_blank">查看详情</a></p>
            </li>
            <li class="middle">
                <p><img src="{{ assetUrlByCdn('/static/activity/geeks/images/pioneer05.jpg') }}" width="222" height="270" alt="马旭"></p>
                <p><strong>马旭</strong></p>
                <p>互联网+不应该只有<br>“颠覆”还要有“帮助”</p>
                <p><a href="http://www.fromgeek.com/talking/71.html" target="_blank">查看详情</a></p>
            </li>
            <li>
                <p><img src="{{ assetUrlByCdn('/static/activity/geeks/images/pioneer06.jpg') }}" width="222" height="270" alt="郭伟"></p>
                <p><strong>郭伟</strong></p>
                <p>互联网人力资源平台缔造者 <br>打造社保无死角时代</p>
                <p><a href="http://www.fromgeek.com/talking/76.html" target="_blank">查看详情</a></p>
            </li>
        </ul>
        <div class="g-btn">
            <a href="javascript:;" id="g-btn"><img src="{{ assetUrlByCdn('/static/activity/geeks/images/btn.png') }}" width="525" height="84" alt="赢取红包，致敬极客"></a>
        </div>
        <div class="g-line"></div>
    </div>

    <!-- list -->
    <div class="g-list">
        <div class="tc">
            <img src="{{ assetUrlByCdn('/static/activity/geeks/images/list-title.png') }}" width="265" height="131" alt="获奖名单" class="g-list-title">
            
        </div>
        <a name="list"></a>
        <div class="g-list-box">
            <dl class="g-list-main">
                <dt><strong>获奖人物：</strong></dt>
                <dd class="left"><span>2016年度通信产业最具影响力人物</span>任正非</dd>
                <dd class="orange"><span>2016年度科技金融创客先锋</span>郭    鹏</dd>
                <dd class="left"><span>2016年度实体+互联网最具影响力人物</span>王健林</dd>
                <dd><span>2016年度手机产业最具影响力人物</span>刘江峰</dd>
                <dd class="left"><span>2016年度新电商领域最具影响力人物</span>马    云</dd>
                <dd><span>2016年度终端产业年度风云人物</span>余承东</dd>
                <dd class="left"><span>2016年度互联网产业最具影响力人物</span>刘强东</dd>
                <dd><span>2016年度O2O领域创客先锋</span>吴    玮</dd>
                <dd class="left"><span>2016年度互联网产业年度风云人物</span>贾跃亭</dd>
                <dd><span>2016年度区块链领域创客先锋</span>陈    刚</dd>
                <dd class="left"><span>2016年度O2O领域最具影响力人物</span>李彦宏</dd>
                <dd><span>2016年度IT产业最具影响力人物</span>郭    平</dd>
                <dd class="left"><span>2016年度O2O领域风云人物</span>姚劲波</dd>
                <dd><span>2016年度IT产业最具影响力人物</span>徐直军</dd>
                <dd class="left"><span>2016年度通信产业年度风云人物</span>王晓初</dd>
                <dd><span>2016年度IT产业最具影响力人物</span>胡厚崑</dd>
                <dd class="left"><span>2016年度IT产业年度风云人物</span>王恩东</dd>
            </dl>
            <dl class="g-list-main">
                <dt><strong>获奖企业：</strong></dt>
                <dt><p>新电商&互联网</p></dt>
                <dd class="left"><span>2016年度最具影响力新电商品牌</span>阿里巴巴</dd>
                <dd><span>2016年度科技金融最具竞争力品牌</span>京东金融</dd>
                <dd class="left"><span>2016年度互联网产业最具影响力品牌</span>阿里巴巴</dd>
                <dd><span>2016年度最佳在线人力资源服务平台</span>金柚网</dd>
                <dd class="left"><span>2016年度互联网产业最具竞争力品牌</span>京  东</dd>
                <dd><span>2016年度最具影响力开放平台</span>飞  凡</dd>
                <dd class="left orange"><span>2016年度最具成长潜力科技金融创新品牌</span>普付宝</dd>
                <dd><span>2016年度科技金融最具影响力品牌</span>蚂蚁金服</dd>
                <dd class="left orange"><span>2016年度最具成长潜力消费金融创新产品</span>快  金</dd>
                <dd><span>2016年度最具成长潜力网络银行</span>I邦银行</dd>
                <dd><span class="w">2016年度最具潜力大数据招聘服务平台</span>上海逸橙信息科技有限公司</dd>

            </dl>
            <dl class="g-list-main">
                <dt><p class="g-icon2">IT网络通信</p></dt>
                <dd class="left"><span>2016年度通信产业最具影响力品牌</span>华  为</dd>
                <dd><span>2016年度最具影响力解决方案供应商</span>华  为</dd>
                <dd class="left"><span>2016年度通信产业最具竞争力品牌</span>爱立信</dd>
                <dd><span>2016年度最佳无线解决方案供应商</span>思  科</dd>
                <dd class="left"><span>2016年度IT产业最具影响力品牌</span>华  为</dd>
                <dd><span>2016年度最佳业务解决方案服务商</span>云智慧</dd>
                <dd class="left"><span>2016年度IT产业最具竞争力品牌</span>浪  潮</dd>
                <dd><span>2016年度最具影响力办公设备供应商</span>惠  普</dd>
                <dd class="left"><span>2016年度最具影响力数据库安全供应商</span>Oracle</dd>
                <dd><span>2016年度最佳办公设备供应商</span>联  想</dd>
                <dd class="left"><span>2016年度最佳数据库安全供应商</span>安华金和</dd>
                <dd><span>2016年度最佳eLTE解决方案供应商</span>华  为</dd>
                <dd class="left"><span>2016年度最具影响力云主机供应商</span>阿里云</dd>
                <dd><span>2016年度最佳SDN解决方案供应商</span>华  为</dd>
                <dd class="left"><span>2016年度最具竞争力云服务（云盘）</span>天翼云盘</dd>
                <dd><span>2016年度最佳智慧城市供应商</span>华  为</dd>
                <dd class="left"><span>2016年度最佳云主机供应商</span>天翼云</dd>
                <dd><span>2016年度最佳 5G解决方案供应商</span>华  为</dd>
                <dd class="left"><span>2016年度最具影响力云计算解决方案供应商</span>华  为</dd>
                <dd><span>2016年度最佳移动销售供应商</span>红圈营销</dd>
                <dd class="left"><span>2016年度最佳云计算解决方案供应商</span>浪  潮</dd>
                <dd><span>2016年度最佳验证技术供应商</span>极验验证</dd>
            </dl>
            <dl class="g-list-main">
                <dt><p class="g-icon3">手机&智能硬件</p></dt>
                <dd class="left"><span>2016年度中国手机行业最具影响力品牌</span>OPPO、VIVO</dd>
                <dd><span>2016年度中国手机行业最佳奋进奖</span>天语手机</dd>
                <dd class="left"><span>2016年度中国手机行业最具竞争力品牌</span>华  为</dd>
                <dd><span>2016年度最具影响力互联网电视品牌</span>乐视电视</dd>
                <dd class="left"><span>2016年度最佳国产手机</span>华为Mate 9</dd>
                <dd><span>2016年度最具竞争力互联网电视品牌</span>暴风TV</dd>
                <dd class="left"><span>2016年度最佳手机设计</span>三星C9 PRO</dd>
                <dd><span>2016年度最具潜力互联网电视品牌</span>微  鲸</dd>
                <dd class="left"><span>2016年度最佳商务手机</span>8848</dd>
                <dd><span>2016年度最具潜力大数据服务商</span>北京泰迪熊</dd>
                <dd class="left"><span>2016年度最佳安全手机</span>360 Q5plus</dd>
                <dd><span>2016年度最具影响力游戏品牌</span>苏州蜗牛</dd>
                <dd class="left"><span>2016年度最佳户外手机</span>云狐手机</dd>
                <dd><span>2016年度最受欢迎运动手表</span>华米手表</dd>
                <dd class="left"><span>2016年度最佳手机分身解决方案供应商</span>Graphite Software</dd>
            </dl>
            <dl class="g-list-main last">
                <dt><p class="g-icon4">新能源汽车</p></dt>
                <dd class="left"><span>2016年度最具影响电动汽车品牌</span>特斯拉</dd>
                <dd><span>2016年度最受消费者欢迎电动汽车</span>北汽新能源</dd>
                <dd class="left"><span>2016年度最具竞争力电动汽车品牌</span>比亚迪</dd>
                <dd><span>2016年度最具潜力电动汽车品牌</span>蔚来汽车</dd>
                
            </dl>
        </div>
        <div class="g-line"></div>
        <a name="report"></a>
        <div class="g-title g-title4"></div>
        <dl class="g-report">
            <dt>
                <a href="http://www.shfinancialnews.com/xww/2009jrb/node5019/node5284/u1ai180259.html"  target="_blank"><img src="{{ assetUrlByCdn('/static/activity/geeks/images/media-pic.png') }}" width="348" height="252">2016年度科技金融创客先锋     九斗鱼CEO－郭鹏</a>
            </dt>
            <dd>
                <p><a href="http://www.shfinancialnews.com/xww/2009jrb/node5019/node5284/u1ai180259.html" target="_blank">2016中国极客大奖揭晓 耀盛中国郭鹏获选“科技金融创客先锋”</a></p>
                <p><a href="http://finance.ce.cn/rolling/201701/16/t20170116_19639905.shtml" target="_blank">普付宝荣膺“2016最具成长潜力科技金融创新品牌” </a></p>
                <p><a href="http://info.3news.cn/info/ft/2017/0116/51117.html" target="_blank">快金TimeCash喜获“2016最具成长潜力消费金融创新产品”大奖 </a></p>
                <p><a href="http://www.fromgeek.com/news/70859.html" target="_blank">2016极客大奖颁奖盛典落幕 50个大奖勾勒中国创新图谱</a></p>
                <p><a href="http://www.fromgeek.com/awards/news/70883.html" target="_blank">2016年度人物揭晓：任正非王健林最具影响，贾跃亭当选风云人物</a></p>
            </dd>
        </dl>
        <div class="g-line"></div>
    </div>
</div>
<div class="g-media">
    <a name="media"></a>
    <img src="{{ assetUrlByCdn('/static/activity/geeks/images/media.png') }}" width="912" height="492" >
</div>

<span class="g-left-btn"></span>

<!-- pop -->
<div class="pop-wrap" style="display: none;" id="g-main">
    <div class="pop-mask"></div>
    <div class="g-pop">
        <div class="g-pop-main tips1" style="display: none;">
            <span class="g-pop-close"></span>
        
            <!-- 已参与过弹层  非常重要-->
            <!-- 已参与过弹层  非常重要-->
            <!-- 已参与过弹层  非常重要-->
            <p class="g-sorry">对不起，您已经参与过了</p>
            <p><img src="{{ assetUrlByCdn('/static/activity/geeks/images/img1.jpg') }}" width="409" height="180" ></p>

        </div>

        <div class="g-pop-main tips2" style="display: none;">
            <span class="g-pop-close"></span>
            <!-- 参与成功 -->
            <p class="g-info"><strong>感谢您的参与</strong><br>红包已发送到您的九斗鱼账户</p>
            <p><img src="{{ assetUrlByCdn('/static/activity/geeks/images/img2.jpg') }}" width="409" height="180" ></p>

        </div>

        <!-- 未登录直接跳转到登录页面 -->
    </div>
</div>

<div class="pop-wrap" id="p-pop">
    <div class="pop-mask"></div>
    <div class="g-pop-box">
        <div class="g-pop-main">
            <span class="g-pop-close"></span>
            <h4>刘强东：以极客精神打造智能生态链条</h4>
            <div class="g-box-img">
                <img src="{{ assetUrlByCdn('/static/activity/geeks/images/pioneer01-1.jpg') }}" width="229" height="287" >
                <p>面对互联网硬件的冲击，传统家电公司变革反应比较慢，也给了广大创业者巨大的机会，借此京东打造了一个智能的生态链条，把这些数据提供给创业者，用京东来为创业者背书，解决创业者的资金问题，通过产品众筹，接着是股权众筹。当然京东还是个电商平台，所以同时京东还帮助创业者解决了物流的问题、售后的问题、客户服务的问题,很多创业者刚开始不具备能力把所有的产品的环节都能搞定，京东希望提供除了硬件之外的其他服务。</p>
            </div>
            <div class="g-box-q">
                <h5>互动提问，刘强东回答</h5>
                <p><span class="q">[Q]</span>张鹏：你怎么理解极客这个概念和精神？之前有没有自己的感知和理解？</p>
                <p><span class="a">[A]</span>刘强东：我觉得，很遗憾自己学的不是技术类的，本科学的设计学。但我从小用我母亲的话来说，就是喜欢捣鼓，对新的东西充满了好奇。小的时候农村没有自行车，拖拉机走来走去，但坐拖拉机很不容易，但自己又不愿意走路上学，就做了一个带方向盘的车，前面带钩子，然后农村看到拖拉机就钩着拖拉机坐着这个车去上学了。</p>
                <p>我觉得谈不上技术，就是你在每天工作、学习、生活过程中，要不断发现，要有新的思考。</p>
                <p><span class="q">[Q]</span>张鹏：有人曾经买过五个手环、几块手表，这个事情我觉得它背后也说明一个问题，很可能他们都用了以后都放掉了，才再买一块。大家把好奇心耗尽之后，大家能不能跟产品构建足够强的联系？你有什么建议给创业者，突破几个月就衰败的瓶颈？</p>
                <p><span class="a">[A]</span>刘强东：现在确实，它很新，消费者也有巨大的好奇心，而且有无数的消费者愿意去尝试，他也知道买回去并不是他所想要的，但他有一定的实力愿意去尝试。我觉得智能硬件产品最关键的是什么？到了消费者手里，后续的二次迭代开发，可能很多创业者没有做好。</p>
                <p>往往一款智能硬件，一卖卖几万，甚至几十万，觉得很成功，然后就着急开始更多品类的智能硬件，一年就做了10几个甚至几十个产品规划，导致产品做的不够精。</p>
                <p>所以，我觉得更多的时间应该花在二次产品迭代开发中去，再进行用户分析，关注用户需求的变化。或者我这个产品跟别的能不能关联起来，通过各种品类的关联，让这个单一的产品给客户带来的价值最大化，这样才能让用户持续不断的用产品，比如手环过去只能跑跑步，后来发现还有别的功能，不断给用户带来超出他期望值的惊喜，这样这个产品才会有持续的生命力。</p>
                <p><span class="q">[Q]</span>张鹏：大部分人会认为，京东是一个电子商务的企业，做的可能是个零售业，你怎么看京东？你自己在心目中把京东设定为什么？因为我们比较熟悉你的是什么呢？对京东到底是怎么一个构想？你的愿景到底是什么？</p>
                <p><span class="a">[A]</span>刘强东：其实，我创业整个公司愿景经过一次变化。最早98年底去中关村练摊，做多媒体配件的产品，那时候希望把最新最好的产品销售出去，因为那个时候社会比较简单，多媒体产品具有更多的功能，所以我们希望能够让每个电脑都多媒体化，比较简单易懂。</p>
                <p>到了06年我们重新思考公司，其实那时候我们已经内部形成公司，我们希望京东的使命是什么，希望让我们的生活能够变得简单和快乐。</p>
                <p>所以过去多少年，现在有京东商城，有京东金融，有拍拍宝等，我们每个子公司都是为了让生活变得简单、快乐，包括我们的智能硬件，也都是为了让消费者的生活变得更加简单、更加快乐，这是我们的使命。</p>
                <p><span class="q">[Q]</span>张鹏：把产品卖给大家还不够，还希望让大家参与其中。</p>
                <p><span class="a">[A]</span>刘强东：对。</p>
                <p><span class="q">[Q]</span>张鹏：以热烈掌声感谢刘强东的分享，也欢迎强东来到极客的世界。</p>
                <p><span class="a">[A]</span>刘强东：谢谢！</p>
            </div>
        </div>
    </div>
</div>

@endsection

@section('jspage')
<script type="text/javascript">
    $(function(){
        // 导航当前状态
        $(".g-menu a").each(function(){
            $(this).click(function(){
                $(this).addClass('hover').siblings('a').removeClass('hover')
            })
        })

        // 刘强东内容介绍
        $("#p-btn").click(function(){
            $("#p-pop").fadeIn();
        })

        // 弹层关闭
        $(".g-pop-close").each(function(){
            $(this).click(function(){
                $(this).parent().parent().parent(".pop-wrap").fadeOut();
            })
        })
        $(".pop-mask").each(function(){
            $(this).click(function(){
                $(this).parent(".pop-wrap").fadeOut();
            })
        })

        // 点击
        //$(".g-left-btn,#g-btn").click(function(){
        //    $("#g-main").fadeIn();
        //})


        $(".g-left-btn,#g-btn").click(function () {
            var userStatus = "{{ $userStatus }}"
            var isReceived = $("#isReceived").val()
            var repeatHit 	= $("#repeatHit").val()

            //重复点击
            if( isReceived == 0 && repeatHit=="opened"){
                $('.g-sorry').html("请勿大量重复点击~");
                $(".pop-layer").show();
                $(".tips1").show();
                $(".tips2").hide();
                return false;
            }

            $("#repeatHit").val("opened");

            if (userStatus) {
                if (isReceived == "1") {
                    $("#g-main").show();
                    $(".tips1").show();
                    $(".tips2").hide();
                    return false;
                } else {
                    $.ajax({
                        url      :"/activity/geeks/receiveBonus",
                        dataType :'json',
                        data: { from:'wap' },
                        type     :'get',
                        success : function(json){
                            if( json.status==true || json.code==200){
                                $("#g-main").show();
                                $(".tips1").hide();
                                $(".tips2").show();
                                $("#isReceived").val(1);
                            } else if( json.status == false || json.code ==500 ){
                                $('.g-sorry').html(json.msg);
                                $("#g-main").show();
                                $(".tips1").show();
                                $(".tips2").hide();
                                $("#repeatHit").val("closed");
                            }
                            return false;
                        },
                        error : function(msg) {
                            $('.g-sorry').html("抱歉，网络出错了");
                            $("#g-main").show();
                            $(".tips1").show();
                            $(".tips2").hide();
                            $("#repeatHit").val("closed");
                            return false;
                        }
                    })

                }
            } else {
                $("#repeatHit").val("closed");
                window.location.href = '/login';
            }
        })

    })
</script>
@endsection
