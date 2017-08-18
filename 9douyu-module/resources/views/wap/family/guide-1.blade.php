<extend name="Common:wap_base" />
<block name="title">
    <title>家庭账户-{:C('TITLE_SUFFIX')}</title>
</block>
<block name="keywords"><meta name="keywords" content="{:C('META_KEYWORD')}" /></block>
<block name="description"><meta name="description" content="{:C('META_DESCRIPTION')}" /></block>
<block name="cssStyle">
<meta name="format-detection" content="telephone=yes">
    <link rel="stylesheet" type="text/css" href="{:Genstatic::statics(__PUBLIC2__.'/weixin/css/familyAccount.css')}">
    <style>
       body{background-color: #f8f7fc;}
    </style>
</block>
<block name="header"></block>
<block name="content">
    <img src="{:Genstatic::statics(__PUBLIC2__.'/weixin/images/topic/family-img07.png')}" alt="" class="img">
    <img src="{:Genstatic::statics(__PUBLIC2__.'/weixin/images/topic/family-img08.png')}" alt="" class="img">
    <img src="{:Genstatic::statics(__PUBLIC2__.'/weixin/images/topic/family-img09.png')}" alt="" class="img">
    <img src="{:Genstatic::statics(__PUBLIC2__.'/weixin/images/topic/family-img10.png')}" alt="" class="img">
    <img src="{:Genstatic::statics(__PUBLIC2__.'/weixin/images/topic/family-img11.png')}" alt="" class="img">
    <section class="family-btn-ye family-mb">
        <p class="family-company1">下载九斗鱼app,绑定家庭账户</p>
        <img src="{:Genstatic::statics(__PUBLIC2__.'/weixin/images/appwx.png')}" alt="" class="img">
        <!-- <p class="family-company">星果时代信息技术有限公司</p> -->
    </section>

    @if($channel=='dspfamily')
        <script type="text/javascript">
            var _py = _py || [];
            _py.push(['a', '6ws.6-.RpeYWJuUKsS4IKWwdXcKa0']);
            _py.push(['domain','stats.ipinyou.com']);
            _py.push(['e','']);
            -function(d) {
                var s = d.createElement('script'),
                        e = d.body.getElementsByTagName('script')[0]; e.parentNode.insertBefore(s, e),
                        f = 'https:' == location.protocol;
                s.src = (f ? 'https' : 'http') + '://'+(f?'fm.ipinyou.com':'fm.p0y.cn')+'/j/adv.js';
            }(document);
        </script>
        <noscript><img src="//stats.ipinyou.com/adv.gif?a=6ws.6-.RpeYWJuUKsS4IKWwdXcKa0&e=" style="display:none;"/></noscript>
    @endif
</block>


