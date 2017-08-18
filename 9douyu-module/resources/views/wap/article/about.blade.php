@extends('wap.common.wapBase')

@section('title', '关于我们')

@section('keywords', "{{env('META_KEYWORD')}}")

@section('description', "{{env('META_DESCRIPTION')}}")
@section('css')
<style type="text/css">
.about-wrap section{background-color: #fff; overflow: hidden; margin-bottom: 0.5rem; width: 100%;}
.about-wrap h2{font-size: 0.94rem; margin:0.85rem 0 1.92rem 0.7rem;color: #000;position: relative;}
.about-wrap h2:after{position: absolute; content: ''; left: 0.075rem; top: 1.4rem; width:1.07rem; height: 2px; background-color: #000; }
.about-logo{width: 10.112rem; display: block; margin: 1.96rem auto 2.52rem; }
.about-intro{ background: url("{{ assetUrlByCdn('/static/weixin/images/about/bg.jpg') }}") top center no-repeat; background-size: cover;}
.about-intro h2{color: #fff; margin-top: 0; margin-bottom: 1.25rem;}
.about-intro h2:after{background-color: #fff;}
.about-intro p{line-height: 0.896rem; color: #fff; font-size: 0.55rem; padding: 0 0.7rem 0.7rem;}
.about-pic{padding: 1.3rem 1.49rem 1.28rem; box-sizing: border-box;}
.about-team{padding: 0 0.64rem 0 0.7rem;}
.about-team dt{width: 3.968rem ;height: 4.82rem; float: left; margin-bottom: 0.94rem;}
.about-team dd{margin-left: 4.78rem;margin-bottom: 0.94rem; height: 4.82rem; box-sizing: border-box; font-size: 0.512rem;line-height: 0.725rem;}
.about-team dd p:first-child{line-height: 1.5rem;}
.about-team dd p strong{margin-right: 0.25rem;font-size: 0.6rem;}
.about-history{padding: 0 2.2rem 0 1.75rem;font-size: 0.55rem; line-height: 0.725rem;}
.about-history.less dd.last:after{display: none;}
.about-history dd{padding-bottom: 0.725rem;}
.about-history dt,.about-history dd{position: relative;}
.about-history dt:after{content: '';position: absolute; width: 8px; height: 8px; border-radius: 50%; border:1px solid #60b9f9; left: -0.76rem; top:0.1rem;}
.about-history dd:after{content: ''; position: absolute; width: 1px; background-color: #60b9f9; top: -0.05rem; bottom: 0.025rem; left: -0.55rem}
.about-history dd:last-child:after{display: none;}
.about-arrow{display: block; margin:0.5rem auto; position: relative; width:10px; height: 10px; border:1px solid #b4b4b4; border-width:1px 1px 0 0; transform:rotate(135deg);-webkit-transform:rotate(135deg);-webkit-transition: all 0.5s;-moz-transition: all 0.5s;-o-transition: all 0.5s;transition: all 0.5s;}
.about-arrow:after{content: '';position: absolute; top: 2px; left: -1px; width:8px; height: 8px; border:1px solid #b4b4b4; border-width:1px 1px 0 0;}
.about-arrow.up{ transform:rotate(-40deg);-webkit-transform:rotate(-40deg);}
</style>
@endsection

@section('content')
    <article class="about-wrap">
        <section class="about-intro">
            <img src="{{ assetUrlByCdn('/static/weixin/images/about/logo.png') }}" class="about-logo">
            <h2>平台简介</h2>
            <p>九斗鱼隶属于国内领先的综合性金融服务集团——耀盛投资管理集团（以下简称“耀盛中国”），是其旗下的互联网金融平台，于2014年6月正式上线。</p>
            <p>九斗鱼积极拥抱监管，坚持用专业的风控方法、领先的科技手段为借款人和出借人提供安全高效的资金撮合服务。平台上线以来，已服务借款企业、个人超3000家。</p>
            <p>未来，九斗鱼将在大数据征信、云计算、智能风险控制等方向全面发展，持续提升对个人及中小微企业的金融服务能力，推动普惠金融的落地生根。</p>
        </section>
        <section class="about-pic">
            <img src="{{ assetUrlByCdn('/static/weixin/images/about/pic.jpg') }}" class="img">
        </section>
        <section>
            <h2>管理团队</h2>
            <dl class="about-team">
                <dt>
                    <img src="{{ assetUrlByCdn('/static/weixin/images/about/img1.jpg') }}" class="img">
                </dt>
                <dd>
                    <p><strong>郭鹏</strong>创始人 CEO</p>
                    <p>11年金融、支付行业资深经验，曾任职钱袋宝支付产品运营、商务合作总经理。<br>美国著名商业杂志《Fast Company》中文版2016年度“中国商业最具创意人物”。</p>
                </dd>
                <dt>
                    <img src="{{ assetUrlByCdn('/static/weixin/images/about/img2.jpg') }}" class="img">
                </dt>
                <dd>
                    <p><strong>刘丽慷</strong>联合创始人 CTO</p>
                    <p>10年以上互联网、游戏行业从业经验；<br>曾先后任职于360、百度、开心网管理岗位；曾负责百度指数、开心网平台架构设计等重大项目。</p>
                </dd>
            </dl>
        </section>
        <section>
            <h2>发展历程</h2>
            <dl class="about-history less">
                <dt>2017.04</dt>
                <dd>九斗鱼获得国家公安部门颁发认证的“信息系统安全等级保护”三级备案证明</dd>
                <dt>2017.03</dt>
                <dd>九斗鱼荣获消费日报社颁发的2017年度“中国互联网金融行业最具创新价值品牌”</dd>
                <dt>2017.01</dt>
                <dd>九斗鱼CEO郭鹏获选《2016中国极客大奖》“科技金融创客先锋”</dd>
                <dt>2016.12</dt>
                <dd>九斗鱼首批接入中国支付清算协会小微金融风险信息共享平台</dd>
                <dt>2016.12</dt>
                <dd>九斗鱼被收入《金融蓝皮书》百家主流平台并获BB+级评级</dd>
                <dt>2016.10</dt>
                <dd>九斗鱼正式取得《中华人民共和国电信与信息服务业务经营许可证》</dd>
                <dt>2016.08</dt>
                <dd>九斗鱼CEO郭鹏入选美国著名商业杂志《Fast Company》中文版2016年度“中国商业最具创意人物”</dd>
                <dt>2016.05</dt>
                <dd class="last">九斗鱼荣获中国金融博物馆颁发的“2015互联网金融创新季度新秀”</dd>
                <dt>2016.03</dt>
                <dd>九斗鱼“晋升”中关村互联网金融行业协会“副会长单位”</dd>
                <dt>2015.12</dt>
                <dd>九斗鱼入选《投资者报》“2015互联网金融公司社会责任榜十强”</dd>
                <dt>2015.12</dt>
                <dd>九斗鱼接入中国支付清算协会“互联网金融风险信息共享系统”</dd>
                <dt>2015.07</dt>
                <dd>九斗鱼荣膺第四届中国财经峰会“2015互联网金融最佳品牌奖”</dd>
                <dt>2015.07</dt>
                <dd>九斗鱼入围《中国企业家》“未来之星”2015最具成长性的新兴企业TOP100</dd>
                <dt>2015.02</dt>
                <dd>九斗鱼荣膺互联网金融领军榜“年度创新品牌”大奖</dd>
                <dt>2014.11</dt>
                <dd>九斗鱼荣膺易观国际颁发的易观之星“互联网金融创新奖”</dd>
                <dt>2014.06</dt>
                <dd>九斗鱼平台正式上线</dd>
            </dl>
            <span class="about-arrow"></span>    
        </section>
    </article>
    <script type="text/javascript">
    setTimeout(function(){
        $(".about-history dt").filter("dt:gt(7)").hide();
        $(".about-history dd").filter("dd:gt(7)").hide()
        $(".about-arrow").on('touchend',function(){
            if($(this).hasClass('up')){
                $(this).removeClass('up');
                $(".about-history").addClass('less');
                $(".about-history dt").filter("dt:gt(7)").fadeOut();
                $(".about-history dd").filter("dd:gt(7)").fadeOut();
            }else{
                $(this).addClass('up');
                $(".about-history").removeClass('less');
                $(".about-history dt").filter("dt:gt(7)").fadeIn();
                $(".about-history dd").filter("dd:gt(7)").fadeIn();

            }
        })
    },0)
    </script>
@endsection
