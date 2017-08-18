<extend name="Public@Template:frontHome" />

<block name="main">


<div class="help-banner">
</div>
<div class="help-subnav mb40">
    <div class="wrap">
        <a href="" class="hover" style="margin-left: 175px;">帮助中心</a><a href="">九斗鱼介绍</a><a href="">登录注册</a><a href="">充值提现</a><a href="">投资回款</a><a href="">九斗鱼微信</a>
    </div>
</div>
<div class="wrap">
    <div class="help-title-wrap mb60">
        <div class="help-title">投资流程</div>
    </div>
    <p class="mb50"><img src="{:Genstatic::statics(__PUBLIC2__.'/images/topic/help-step.png')}" /></p>
</div>
<div class="help-line mb50"></div>
<div class="wrap mb40">
    <div class="help-title-wrap mb40">
        <div class="help-title help-title-2">常见问题</div>
    </div>
    <div class="clearfix hidden">
        <dl class="help-dl mb60">
            
            <dt class="help-dl-bg mn"> <p>九斗鱼的平台的收费标准？</p></dt>
            <dd class="t2" style="display: block">九斗鱼推荐的出借项目均为中小型企业融资项目，所有放到平台上的企业项目都经过严格的风控筛选，而且所有的项目都是有融资性担保公司进行100%本息安全。我们承诺在项目进行逾期当天24小时内进行无条件垫付，同时，融资企业因逾期造成的罚息归九斗鱼。九斗鱼推荐的出借项目均为中小型企业融资项目，所有放到平台上的企业项目都经过严格的风控筛选，而且所有的项目都是有融资性担保公司进行100%本息安全。我们承诺在项目进行逾期当天24小时内进行无条件垫付，同时，融资企业因逾期造成的罚息归九斗鱼。</dd>
            <dt><p>项目收益怎么样？</p></dt>
            <dd class="t2">项目收益怎么样？</dd>
            <dt><p>提现到账时间和限制？</p></dt>
            <dd class="t2">项目收益怎么样？项目收益怎么样？</dd>
            <dt><p>我在九斗鱼的资金安全吗？</p></dt>
            <dd class="t2">项目收益怎么样？项目收益怎么样？项目收益怎么样？</dd>
            <dt><p>借款企业若无力偿还怎么办？</p></dt>
            <dd class="t2">项目收益怎么样？项目收益怎么样？项目收益怎么样？项目收益怎么样？</dd>
            <dt><p>投资后有什么证明或合同吗？</p></dt>
            <dd class="t2">项目收益怎么样？项目收益怎么样？项目收益怎么样？项目收益怎么样？项目收益怎么样？项目收益怎么样？</dd>
            <dt><p>在九斗鱼投资是受法律保护的吗？</p></dt>
            <dd class="t2">项目收益怎么样？项目收益怎么样？项目收益怎么样？项目收益怎么样？</dd>
            <dt><p>九斗鱼如何保证本息安全的？</p></dt>
            <dd class="t2">项目收益怎么样？项目收益怎么样？项目收益怎么样？</dd>
            <dt><p>好多平台跑路，九斗鱼如何避免？</p></dt>
            <dd class="t2">项目收益怎么样？项目收益怎么样？</dd>
            <dt><p>在九斗鱼投资是受法律保护的吗？</p></dt>
            <dd class="t2">项目收益怎么样？项目收益怎么样？项目收益怎么样？项目收益怎么样？项目收益怎么样？项目收益怎么样？项目收益怎么样？项目收益怎么样？项目收益怎么样？项目收益怎么样？项目收益怎么样？项目收益怎么样？项目收益怎么样？项目收益怎么样？项目收益怎么样？项目收益怎么样？项目收益怎么样？</dd>
        </dl>
    
    </div>
</div>
<div class="help-line mb50"></div>
<div class="wrap">
    <div class="help-title-wrap mb40">
        <div class="help-title help-title-3" style="width: 130px;">理财公开课</div>
    </div>
    <div class="help-lesson pr">
        <a href="" target="_blank" class="help-lesson-one">第一课保理项目介绍</a>
        <a href="" target="_blank" class="help-lesson-two">第二课等额本息</a>
        <a href="" target="_blank" class="help-lesson-three">第三课自动投标</a>
        <a href="" target="_blank" class="help-lesson-four">第四课债权转让</a>

    </div>
    <div class="clear mb60"></div>
</div>
<div class="help-line mb50"></div>
<div class="wrap">
    <div class="help-title-wrap mb80">
        <div class="help-title help-title-4" style="width: 130px;">联系客服</div>
    </div>
    <div class="fl"><img src="{:Genstatic::statics(__PUBLIC2__.'/images/topic/help-online.png')}" /></div>
    <a href=""></a>
    <div class="mb80 clear"></div>
</div>
</block>

<block name="jsPage"> 
<?php 
    $jscssminify->addScript(Genstatic::statics(__PUBLIC2__ . '/js/slide.js'));

    $js = <<<'BLOCK'
(function($){
    $(document).ready(function(){
        $(".help-dl dt").hover(function(){
            $(this).addClass("help-dl-bg").siblings("dt").not(".mn").removeClass("help-dl-bg");
        }).click(function(){
            $(this).addClass("help-dl-bg mn").siblings("dt").removeClass("help-dl-bg mn");
            $(this).next("dd").show("slow").siblings("dd").hide("slow");
        })

    });
})(jQuery)
BLOCK;
    $jscssminify->addScriptDeclaration($js);
?>
</block>
