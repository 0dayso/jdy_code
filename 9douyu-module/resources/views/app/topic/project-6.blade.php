@include('wap.common.appBase')
@section('title', '出借介绍')
<block name="content">
    <article class="project-bg">
        <div class="project-wrap first">
            <div class="proj-contrast-title">
                <i></i>每10万元借款利率对比
            </div>
            <div class="proj-contrast-main">
                <div class="proj-contrast-txt1">九省心6月期</div>
                <div class="proj-contrast-txt2">银行系</div>
                <div class="proj-contrast-txt3">互联网系</div>
                <div class="proj-contrast-txt5">
                    <p>1.3%</p>
                    <p><small>利率</small></p>
                </div>
                <div class="proj-contrast-txt6">
                    <p>8%</p>
                    <p><small>利率</small></p>
                </div>
                <div class="proj-contrast-txt4">
                    <p>11%</p>
                    <p><small>利率</small></p>
                </div>
            </div>
            <ul class="proj-profit">
                <li>
                    <p>银行系</p>
                    <p class="orange"><small>预计</small>649元</p>
                </li>
                <li>
                    <p>九省心6月期</p>
                    <p class="orange"><small>预计</small><strong>5425元</strong></p>
                </li>
                <li>
                    <p>互联网系</p>
                    <p class="orange"><small>预计</small>3945元</p>
                </li>
            </ul>
        </div>
        <div class="project-wrap">
            <div class="project-title">安不安全</div>
            <ul class="proj-safe">
                <li>
                    <h3>CFCA认证</h3>
                    <p>中国金融认证中心（CFCA）认证，国家级网络信息体系保障电子合同合规合法</p>
                    <i class="one"></i>
                </li>
                <li>
                    <h3>资产安全</h3>
                    <p>银行资金监管，千万风险准备金提供多重保障方式</p>
                    <i class="two"></i>
                </li>
                <li>
                    <h3>资本雄厚</h3>
                    <p>注册实缴资本3亿，11年金融风控经验，公司业务遍布全国各地</p>
                    <i class="three"></i>
                </li>
                <li>
                    <h3>风险控制</h3>
                    <p>国际领先的Riskcalc风控系统定量分析风险,专业的风控团队层层把关，完善的风险保障机制</p>
                    <i class="four"></i>
                </li>
            </ul>
        </div>
        <div class="project-wrap">
            <div class="project-title">购买准备</div>
            <div class="proj-verify">
                <span></span>
                <p>完成身份验证</p>
            </div>
            <img src="{{ env('TMPL_PARSE_STRING.__PUBLIC2__')}}/app/images/topic/project-step.png" alt="" class="img">
            <p class="proj-txt1">首次购买需完成“身份验证”,需要填写本人姓名、身份证号、银行储蓄卡账号</p>
        </div>
        <div class="project-wrap">
            <div class="project-title">怎样赎回</div>
            <p class="proj-txt2">本产品到期后将自动赎回至零钱计划，使本金和收<img src="{{ env('TMPL_PARSE_STRING.__PUBLIC2__')}}/app/images/topic/project-img.png" alt="" class="proj-img">益继续在零钱计划中赚取零钱计划收益，钱继续生钱，一分钱都不错过。
            </p>
        </div>
        <div class="project-wrap">
            <div class="project-title">着急用钱</div>
            <div class="proj-title2">债权转让介绍</div>
            <div class="proj-main">
                <h3>什么是债权转让？</h3>
                <p>可以在九斗鱼理财购买的指定理财产品到期前，帮助你在急用钱时获得资金。</p>
                <h3>债权转让有哪些条件？</h3>
                <p class="proj-txt3"><i></i>对于直接购买的债权自满标审核日起，30日后可挂牌转让；</p>
                <p class="proj-txt3"><i></i>出借人在九斗鱼债权转让专区购买的债权可以再次申请转让；</p>
                <p class="proj-txt3"><i></i>项目完结日当天不能债转，其他时间均可以发起债权转让；</p>
                <p class="proj-txt3"><i></i>对于转让人：申请债权转让时，目前仅支持一次性转让某债权的全部本金；</p>
                <p class="proj-txt3"><i></i>对于受让人：目前仅支持一次性购买某转让债权全部本金。</p>
            </div>
        </div>

    </article>
</block>
