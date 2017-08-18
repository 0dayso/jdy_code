<div class="Js_tab_box t-center-left-4">
    <ul class="Js_tab t-center-tab tab-three">
        <li class="cur">项目详情<span></span></li>
        <li>常见问题<span></span></li>
        <li class="t-brn">还款计划<span></span></li>
    </ul>
    <div class="js_tab_content">
        <!--项目介绍-->
        <div class="Js_tab_main t-center-left-5" style="display:block;">

            <dl class="t-center-left-6">
                <dt><span></span>项目描述</dt>
                <dd>
                    <p>
                        {!! $creditDetail['companyView']['project_desc'] or '' !!}点击<a href="javascript:;" data-target="moduldetail">借款人详情</a>可以查看部分借款人信息，如需查看全部，需进行投资。
                    </p>
                </dd>
            </dl>
            <div class="t-online"></div>
            <dl class="t-center-left-6">
                <dt><span></span>风险控制</dt>
                <dd>
                    {!! $creditDetail['companyView']['risk_control'] or '' !!}
                </dd>
            </dl>
            {{--借款人详情弹窗--}}
            <!-- 提现须知new -->
            <div class="layer_wrap js-mask" data-modul="moduldetail">
                <div class="Js_layer_mask layer_mask" data-toggle="mask" data-target="js-mask"></div>
                <div class="Js_layer layer">
                    <table class="table table-theadbg table-textcenter">
                        <thead>
                            <tr>
                                <td width="120">借款人姓名</td>
                                <td width="170">借款人身份证号</td>
                                <td width="170">借款金额（元）</td>
                                <td>借款用途</td>
                            </tr>
                        </thead>
                    </table>
                    <div class="module-table-wrap">
                        <table class="table table-theadbg table-textcenter">
                            <tbody>
                                @if( !empty($creditDetail['companyView']['credit_list_info']) )
                                    @foreach($creditDetail['companyView']['credit_list_info'] as $credit_item)
                                        <tr>
                                            <td width="120"> {{ $credit_item['realname'] }} </td>
                                            <td width="170"> {{ $credit_item['identity_card'] }}  </td>
                                            <td width="170"> {{ number_format($credit_item['amount'],2) }} </td>
                                            <td>个人消费</td>
                                        </tr>
                                    @endforeach

                                @endif
                            </tbody>
                        </table>
                    </div>
                    <a href="#" data-toggle="mask" data-target="js-mask" class="btn btn-blue btn-large t-alert-btn t-mt30px">关闭</a>

                </div>
            </div>


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
                        <td>{{ number_format($plan['refund_cash'],2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>

    </div>
</div>
