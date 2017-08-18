<div class="Js_tab_box t-center-left-4">
    <ul class="Js_tab t-center-tab tab-three">
        <li class="cur">项目详情<span></span></li>
        <li>常见问题<span></span></li>
        <li class="t-brn">还款计划<span></span></li>
    </ul>
    <div class="js_tab_content">
        <!--项目介绍-->
        <div class="Js_tab_main t-center-left-5" style="display:block;">
            <dl class="t-property">
                <dt><img src="{{assetUrlByCdn('static/images/new/t-img-new.png')}}" width="284" height="147" alt="九省心"></dt>
                <dd>
                    <h4>九省心寓意“安全无忧”</h4>
                    <p>一笔资金购买一个债权组合，包含多个项目，风险分散</p>
                    <p>比一笔资金一次只能购买一个项目更安全</p>
                    <p>自动分散投资，到期自动赎回，省心省力</p>
                </dd>
            </dl>
            <div class="t-online1"></div>
            <dl class="t-property1">
                <dt><img src="{{assetUrlByCdn('static/images/new/t-img1.png')}}" width="206" height="183" alt="九省心"></dt>
                <dd>
                    <h4>九省心意味“短期高收益"</h4>
                    <p>借款利率<span> 8% </span>，是余额宝的<span> 2 </span>倍，<br>活期存款的<span> 20 </span>倍</p>
                    <p>投资1万元，20天收益对比</p>
                    <p class="t-p">“20天出借计划能赚51元，享受浪漫双人电影</p>
                    <p class="t-p1">余额宝只能有24元，勉强够一份KFC工作日午餐”</p>
                </dd>
            </dl>
            <div class="t-online1"></div>

            <dl class="t-property2">
                <dt><img src="{{assetUrlByCdn('static/images/new/t-img2.png')}}" width="202" height="199" alt="九省心"></dt>
                <dd>
                    <h4>九省心资金安全保驾护航</h4>
                    <p>第一重：精选优质债权，最佳风险收益比构建债权组合，</p>
                    <p class="t-p">投资风险更低</p>
                    <p>第二重：东亚银行《资金管理协议》，千万风险准备金</p>
                    <p class="t-p">保障，查看<a href="/content/article/reservefund?id=815" target="_blank">《风险准备金账户》</a></p>
                    {{--<p>第三重： 合作的担保公司承担连带责任担保，查看</p>--}}
                    {{--<p class="t-p"><a href="/static/resource/heightGuaranteeAndFactorBuyback.pdf" target="_blank">《最高额担保合同》</a></p>--}}
                    {{--<p>第四重：耀盛商业保理有限公司对违约债权当日无条件回购，</p>--}}
                    {{--<p class="t-p">查看<a href="/static/resource/heightGuaranteeAndFactorBuyback.pdf" target="_blank">《耀盛保理违约债权收购》</a></p>--}}
                </dd>
            </dl>
        </div>

        <!--常见问题-->
        <div class="Js_tab_main t-center-left-7 t-property3" style="display:none;">
            <h4><span>1</span>项目到期后，我能直接拿回本息吗？</h4>
            <p>是的，根据不同的还款方式，到期后系统将自动转让您持有的债权为您收回本金，债权转让成功后或债权到期后，您就可以拿回本息。</p>
            <h4><span>2</span>项目安全吗？</h4>
            <p>九斗鱼以严谨负责任的态度筛选优质债权供用户投资，另外有风险准备金机制，借款人连带担保机制，分散投资机制将出借人的风险降到最小。</p>
            <h4><span>3</span>出借人是否需要支付费用？</h4>
            <p>出借人在九斗鱼平台的充值、投资及债权转让均无任何费用；对投资后回收的本息每月拥有四次免费提现次数，超出四次，每笔提现将收取5元手续费。</p>
            <h4><span>4</span>借款企业会提前还款吗？提前还款的项目利息怎么计算？ </h4>
            <p>借款企业在借款期间可以提前偿还剩余本金，即提前还款。如发生提前还款，九斗鱼将提前终止项目的完结时间，并将实际持用期间的利息支付给出借人。如九省心3月期产品，在持有第二个月十天时企业发生了提前还款，九斗鱼会在当天将剩余十天利息连同本金一起返还到账户中。</p>
            <h4><span>5</span>可以取消投资或赎回吗？是否有违约费用？</h4>
            <p class="t-pb0px">为保障出借人和借款人利益，当您确认出借成功后，资金将被冻结，无法撤回资金或取消投资；但您在持有该债权30天后可以申请债权转让，可以将持有的债权出让给他人，申请债权转让是免费的。</p>
        </div>

        <!-- 引入还款计划 -->
        <div class="Js_tab_main t-repayment" style="display: none;">
            <table class="table table-theadbg table-textcenter">
                <thead>
                <tr>
                    <td>还款时间</td>
                    <td>还款类型</td>
                    <td>预计还款金额（元）      </tr>
                </thead>
                <tbody>
                @foreach($refundPlan as $plan)
                    <tr>
                        <td>{{ $plan['refund_time'] }}</td>
                        <td>{{ $plan['refund_note'] }}</td>
                        <td>{{ number_format($plan['refund_cash'], 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>

    </div>
</div>
