<div class="Js_tab_box t-center-left-4">
    <ul class="Js_tab t-center-tab tab-three">
        <li class="cur">项目详情<span></span></li>
        <li>常见问题<span></span></li>
        <li class="t-brn">还款计划<span></span></li>
    </ul>
    <div class="js_tab_content">
        <!--项目描述-->
        <div class="Js_tab_main t-center-left-5" style="display:block;">
            <!-- 保理债权 -->
            <table class="detail-table">
                <tr>
                    <th width="20%">项目名称</th>
                    <td>{{$project['product_line_note']}} {{$project['invest_time_note']}}  {{$project['id']}}</td>
                </tr>
                <tr>
                    <th>借款利率</th>
                    <td>{{(float)$project['profit_percentage']}}%</td>
                </tr>
                <tr>
                    <th>借款期限</th>
                    <td>{{ $project['format_invest_time'] . $project['invest_time_unit']}}</td>
                </tr>
                <tr>
                    <th>还款方式</th>
                    <td>{{$project['refund_type_note']}}</td>
                </tr>
                <tr>
                    <th>到期还款日</th>
                    <td>{{$project['end_at']}}</td>
                </tr>
                <tr>
                    <th>借款总额</th>
                    <td>{{ $project['total_amount'] }}元</td>
                </tr>
                <tr>
                    <th>募集开始时间</th>
                    <td>{{ date('Y-m-d',\App\Tools\ToolTime::getUnixTime($project['publish_at'])) }}（募集时间最长不超过20天）</td>
                </tr>
                <tr>
                    <th>风险等级</th>
                    <td>保守型</td>
                </tr>
                <tr>
                    <th>出借条件</th>
                    <td>最低100元起投，最高不超过剩余项目总额</td>
                </tr>
                <tr>
                    <th>提前赎回方式</th>
                    <td>持有债权项目30天（含）即可申请债权转让，赎回时间以实际转让成功时间为准</td>
                </tr>
                <tr>
                    <th>费用</th>
                    <td>买入费用：0.00%<br>退出费用：0.00%<br>提前赎回费率：0.00%</td>
                </tr>
                <tr>
                    <th>项目介绍</th>
                    <td>{{isset($company['factor_summarize']) ? htmlspecialchars_decode($company['factor_summarize']) : '九安心产品是保理公司将应收账款收益权转让给出借人；原债权企业多为国企及上市公司，切负有连带责任，借款期限一般为30~90天，适合偏好短期，且稳定的出借人。'}}</td>
                </tr>
                {{--<tr>
                    <th>协议范本</th>
                    <td><a href="javascript:;" class="blue" data-target="moduldetail">【点击查看】</a></td>
                </tr>--}}
            </table>
            <dl class="detail-info-style1">
                <dt>债权企业信息</dt>
                <dd>
                    <p><span>债权企业名称：{{isset($company['credit_company']) ? $company['credit_company'] : null}}</span><span>企业证件号：{{isset($company['format_loan_user_identity']) && !empty($company['format_loan_user_identity']) ? substr($company['format_loan_user_identity'][0] ,0,4) .'******' : null}}</span></p>
                    <p>{{isset($company['family_register']) ? '<span>经营地址：' .$company['family_register'] . '</span>' : null}}</span><span>借款用途：资金周转</span></p>
                </dd>
            </dl>
            <!-- End 保理债权 -->
        </div>

        <!--风险控制-->
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
        @include('pc.invest.project.newDetail.refundPlan')
    </div>
</div>
