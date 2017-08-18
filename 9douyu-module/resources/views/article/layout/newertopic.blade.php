<extend name="Public@Template:frontHome" />

<block name="cssPage">
<?php 
$jscssminify->addStyleSheet(__PUBLIC2__ . '/css/style.css');
?>
</block>

<block name="title">
    <title>新手指引 - {:C('TITLE_SUFFIX')}</title>
</block>

<block name="main">

<div class="yellow-wrap">
    <div class="wrap">
        <div class="newertopic-one"> 九斗鱼—安全理财专家-一个你必须爱上的安全理财平台-九斗鱼是耀盛汇融投资管理（北京）有限公司于2014年推出的全新互联网金融平台，国内首家引入商业保理公司对投资者持有的违约债权再收购平台，旨在为有融资及理财需求的中小企业和个人提供高效、透明、安全、便捷的互联网金融服务。用户可通过九斗鱼投资经过RISKCALC认证的优质中小企业债权，享受提供本息安全计划的理财服务。</div>
    </div>
</div>
<div class="purple-wrap">
    <div class="wrap">
        <div class="newertopic-two">为什么选择九斗鱼？-好收益： 借款利率高达15%投资当日立即生息,低门槛：100元起投,最安全：资本实力雄厚 RISKCALC风控系统 第三方担保机构100%本息安全 项目逾期当天赔付</div>
    </div>
</div>
<div class="blue-wrap">
    <div class="wrap">
        <div class="newertopic-three">投资产品介绍-等额本息还款,100元起投，12%-15%收益，直接投资给经过riskcalc风控系统评级的优质的中小企业，投资本息由合作担保机构100%保障本息，借款企业每月等额本息回款。等额本息：例如：投资10000元，期限12个月，年利率13.8%，本息合计 10,763.16 元，利息收入共 763.16 元，每月回款896.93元。循环投资：例如：投资10000元，期限12个月，年利率13.8%循环出借可获得本息合计 1,147.71元，利息收入：1470.71元。</div>
        <div class="newertopic-four">按月付息到期还本-100元起投，12%-15%收益，直接投资给经过riskcalc风控系统评级的优质的中小企业，投资本息由合作担保机构100%保障本息，借款企业每个月偿还利息，到期偿还本金。例如：投资10000元，期限12个月，借款利率13.8%，每月返回利息115元，最后一期返回10115元。</div>
    </div>
</div>
<div class="green-wrap">
    <div class="wrap">
        <div class="newertopic-slide">
            <div class="newertopic-step" id="newertopic-step">
            <ul>
              <li>
                <div class="newertopic-step-title one"></div>
                <div class="newertopic-step-img one"></div>
              </li>
              <li>
                <div class="newertopic-step-title two"></div>
                <div class="newertopic-step-img two"></div>
              </li>
              <li>
                <div class="newertopic-step-title three"></div>
                <div class="newertopic-step-img three"></div>
              </li>
              <li>
                <div class="newertopic-step-title four"></div>
                <div class="newertopic-step-img four"></div>
              </li><li>
                <div class="newertopic-step-title five"></div>
                <div class="newertopic-step-img five"></div>
              </li>
              <li>
                <div class="newertopic-step-title six"></div>
                <div class="newertopic-step-img six"></div>
              </li>
            </ul>
          </div>
          <div class="newertopic-step-li">
            <ul id="newertopic-step-li">
              <li class="on">1</li>
              <li>2</li>
              <li>3</li>
              <li>4</li>
              <li>5</li>
              <li>6</li>
            </ul>
          </div>
          <div class="newertopic-prev"></div>
          <div class="newertopic-next"></div>
        </div>
    </div>
</div>
<div class="red-wrap">
    <div class="wrap">
        <div class="newertopic-five">
        <a href="{:U('/register')}">立即注册</a>
            <p>安全投资获取高收益</p>
        </div>
    </div>
</div>
</block>

<block name="jsPage">
<?php 
$jscssminify->addScript(__PUBLIC2__ . '/js/slide.js');
$js = <<<'BLOCK'
(function($){
 $(document).ready(function(){   
    //step切换
    jQuery("#newertopic-step").jCarouselLite({
            auto:5000,
            speed:1000,
            visible:1,
            stop:$("#newertopic-step"),
            btnGo:$("#newertopic-step-li li"),
            btnGoOver:true,
            btnPrev:$(".newertopic-prev"),
            btnNext:$(".newertopic-next")
    
        });
    //可信网站取消放大缩小
    $(".kxwz img").attr("onmouseout", null).attr("onmouseover", null).attr("title","可信网站");
    });
 })(jQuery);
BLOCK;
$jscssminify->addScriptDeclaration($js);
?>
</block>
</body>
</html>
