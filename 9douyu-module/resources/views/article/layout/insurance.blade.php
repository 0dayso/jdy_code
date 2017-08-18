<extend name="Public@Template:frontHome" />

<block name="sliders">
<style>
#full-screen-slider.insurance {
    height: 410px;
    background: url("/static/images/topic/insurance-1.png") no-repeat center top;
}
.riskcalc-more {
    margin-left: 320px;
    margin-top: 10px;
}
.law-more {
    margin-left: 300px;
}
</style>
<div id="full-screen-slider" class="insurance"></div>
<div class="clearfix"></div>
</block>

<block name="main">
<div class="wrap">
    <div class="img-block">
        <img class="fl" src="{:Genstatic::statics('/static/images/topic/insurance-2.png')}" usemap="#Map" alt="" />
        <map name="Map" id="Map">

            <area shape="rect" coords="450,344,652,370" href="{:U('/agreement/factorBuyback','','pdf')}" target="_blank" />
            <area shape="rect" coords="523,308,682,329" href="{:U('/agreement/heightGuarantee','','pdf')}" target="_blank" />
        </map>
    </div>
    <div class="clearfix mb40"></div>
    <div class="img-block">
        <a name="riskcalc"></a>
        <img class="fl" src="{:Genstatic::statics('/static/images/topic/insurance-3.png')}" alt="" />
        <a class="riskcalc-more fl" href="/article/88.html" target="_blank"><img class="fl" src="{:Genstatic::statics('/static/images/topic/insurance-more.png')}"/></a>
    </div>
    <div class="clearfix mb120"></div>
    <div class="img-block">
        <img class="fl" src="{:Genstatic::statics('/static/images/topic/insurance-4.png')}" alt="" />
    </div>
    <div class="clearfix mb40"></div>
    <div class="img-block">
        <img class="fl" src="{:Genstatic::statics('/static/images/topic/insurance-5.png')}" alt="" />
        <a class="law-more fl" href="/article/89.html" target="_blank"><img class="fl" src="{:Genstatic::statics('/static/images/topic/insurance-more.png')}"/></a>
    </div>
    <div class="clearfix mb40"></div>
    <div class="img-block">
        <img name="technique" id="technique" class="fl" src="{:Genstatic::statics('/static/images/topic/insurance-6.png')}" alt="" />
    </div>
    <div class="clearfix mb40"></div>
</div>
</block>
