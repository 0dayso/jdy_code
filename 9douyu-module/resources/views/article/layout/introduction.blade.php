<extend name="Public@Template:frontHome" />
<block name="main">
<div class="wrap">
    <include file="Content@Common:menu" />
    <div class="lefttab-right fl mt40">
        <div class="content-block fl">
            <?php
                $parts = explode('-', $currentArticle['title']);
            ?>
            <div class="clearfix"><h1 class="title fl">{$parts[0]}</h1><h3 class="subtitle-right fl">{$parts[1]|default=''}</h3></div>
            <div class="content">
                <?php echo $currentArticle['content']; ?>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <div class="clearfix"></div>
                <h1>相关媒体报道</h1>
                <p>&nbsp;</p>
                <?php
                    $mediaArticle = array(
                        array(
                            'purl'              => array($view_ssl => '/static/images/media/media-1.png'),
                            'title'             => '财经网：【耀盛中国原旭霖：“技术流派”建根基 大浪淘沙始见金】',
                            'link'              => 'http://finance.caijing.com.cn/2014-06-25/114290062.html',
                            'intro'             => '耀盛中国总裁.原旭霖 长江后浪推前浪，一代新人换旧人。用这句话形容目前的网络借贷平台现状丝毫不为过。像极了两年前的团购行业，不断有新军冒起，又不断有公司倒下。',
                            'publish_time'      => '2014-06-25',
                        ),
                        array(
                            'purl'              => array($view_ssl => '/static/images/media/media-2.png'),
                            'title'             => '耀盛中国-九斗鱼总裁原旭霖接受CCTV采访',
                            'link'              => 'http://news.cntv.cn/2014/07/08/VIDE1404798846710525.shtml',
                            'intro'             => '2014年7月7日至9日，第二届中国中小企业投融资交易会在国家会议中心隆重召开。会议由国家发展和改革委员会、中国人民银行指导，中国银行业协会等单位主办。',
                            'publish_time'      => '2014-07-09',
                        ),
                        array(
                            'purl'              => array($view_ssl => '/static/images/media/media-3.png'),
                            'title'             => '《经济信息联播》我国中小企业第二季度发展指数略微上升',
                            'link'              => 'http://tv.cntv.cn/video/C10330/10bd2fbb75144200a7e13660e2780ac7',
                            'intro'             => '2014年7月7日至9日，第二届中国中小企业投融资交易会在国家会议中心隆重召开。接受央视媒体采访时表示，金融的本质是风险的评级和定价，中小企业的信用风险评定需要有数量分析模型。',
                            'publish_time'      => '2014-06-25 ',
                        ),
                        array(
                            'purl'              => array($view_ssl => '/static/images/media/media-4.png'),
                            'title'             => '民营银行定位中小企业融资 民企推进银行梦',
                            'link'              => 'http://www.yicai.com/news/2014/07/3993047.html',
                            'intro'             => '近日，耀盛汇融总裁原旭霖在接受《第一财经日报》记者专访时透露，该公司正式发起设立的民营银行——耀盛银行股份有限公司（下称“耀盛银行”），于今年1月20日通过预核准，注册资本10亿元人民币，目前已正式进入筹备阶段。',
                            'publish_time'      => '2014-07-15',
                        ),
                        array(
                            'purl'              => array($view_ssl => '/static/images/media/media-5.png'),
                            'title'             => '耀盛银行：中国还需要这样一家银行',
                            'link'              => 'http://finance.qq.com/a/20140603/036307.htm',
                            'intro'             => '如果细数2014年的金融关键词，“民营银行”绝对是焦点中的焦点。随着中国银监会主席尚福林在3月11日全国“两会”记者会上宣布5家民营银行试点已经获批的消息，民营企业疯狂了，金融圈儿也随之沸腾起来。',
                            'publish_time'      => '2014-06-04',
                        ),
                        array(
                            'purl'              => array($view_ssl => '/static/images/media/media-6.png'),
                            'title'             => '网贷风控日趋成熟 九斗鱼推行“风控技术标准化”进程',
                            'link'              => 'http://www.cs.com.cn/ssgs/gsxl/201407/t20140704_4437426.html',
                            'intro'             => '日前，《北京商报讯》刊文称，“网贷天眼”发布的《中国网贷平台行情2014年6月报告》数据显示：6月，我国网络贷款总成交(1.00,0.000,0.00%)达到了151.3亿元左右，环比增长14.6%。',
                            'publish_time'      => '2014-07-04',
                        ),
                        array(
                            'purl'              => array($view_ssl => '/static/images/media/media-7.png'),
                            'title'             => '不良贷款率比银行还低的九斗鱼',
                            'link'              => 'http://cxjj.cinic.org.cn/news/qiye/49658.html',
                            'intro'             => '2013年在金融圈最热不过的是互联网金融这个新概念，而网贷平台行业显而易见的是互联网金融的重要组成之一，经过一段时间的发展已初现业态，但在发展过程中风险频发，一直伴随着争议。',
                            'publish_time'      => '2014-07-10',
                        ),
                        array(
                            'purl'              => array($view_ssl => '/static/images/media/media-8.png'),
                            'title'             => '“宝宝”们降温 投资者另觅他处',
                            'link'              => 'http://finance.huagu.com/rdsm/1407/267762.html',
                            'intro'             => '2013年互联网金融将理财热推向一个高潮，全民理财风席卷全国大江南北，借款利率一度突破6%，不过好景不长，持续走跌，光环褪去，现在均回落到借款利率4.5%的水平。',
                            'publish_time'      => '2014-07-14',
                        ),
                    );
                ?>
                <include file="Content@Article:mediaItem" />
            </div>
        </div>
        <div class="clearfix mb40"></div>
    </div>
</div>
</block>

<block name="jsScript">
<?php 
if($currentArticle['id'] == 2) { //加入我们 
$js = <<<'BLOCK'
(function($) {
    $(function(){
        $(".top-tab-slide dt").each(function(){
            $(this).click(function(){
                if($(this).hasClass("on")) {
                    $(this).removeClass("on").next("dd").hide();
                } else {
                    $(this).addClass("on").next("dd").show();
                    $(this).parent("dl").siblings().find("dd").hide();
                }
            });
        });
    });
})(jQuery);
BLOCK;
$jscssminify->addScriptDeclaration($js);
} 
?>

<?php if($currentArticle['id'] == 6) { //联系我们 ?>
<include file="Public@Module:baiduMap" />
<?php } ?>
</block>