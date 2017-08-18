<div class="Js_tab_box t-center-left-4">
    <ul class="Js_tab t-center-tab tab-two">
        <li class="cur">项目详情<span></span></li>
        {{-- <li>安全保障<span></span></li> --}}
        <li class="t-brn">还款计划<span></span></li>
    </ul>
    <div class="js_tab_content">
        <!--项目描述-->
        <div class="Js_tab_main t-center-left-5" style="display:block;">
            <dl class="t-center-left-6">
                <dt><span></span>项目描述</dt>
                <dd>该项目为九省心3/6/12月期出借计划，以耀盛中国旗下耀盛信贷针对全国各地的中小企业提供的快速融资贷款业务为基础，将多个借款人的融资借款项目打包为一个出借计划，帮助用户实现分散投资。所有项目均采用世界领先的RISKCALC®风控评级体系（耀盛中国11年金融经验研制的中小企业信用评级技术，也是国内唯一一家具有专利的信用评级技术）对借款企业和借款人资料层层把关，通过专业的信审团队和风控团队，为每个借款项目做严格的尽职调查和风控审核。严守借款借款客户准入标准，从源头杜绝风险；全方位收集客户数据，深度调查，剖析项目情况，银行级别三级审批制度；融资客户本人亲自到访耀盛总部及分支机构，完成现场签约、放款、留档，后期不定期的回访、暗访，综合判断信贷风险，切实保障出借人利益。</dd><br>
            </dl>
            <!-- <div class="t-online"></div> -->
        </div>

        <!--风险控制-->
        {{-- <div class="Js_tab_main t-center-left-7" style="display:none;">
            <dl class="t-center-left-6">
                <dt><span></span>风险控制</dt>
                <dd>平台对每个投资项目都有相应保障措施，同时建立了风险准备金账户，对平台每个投资项目提取 1%作为风险准备金。</dd>
            </dl>
            <div class="t-online"></div>
            <dl class="t-center-left-6">
                <dt><span></span>资金安全</dt>
                <dd>
                    <p>1.九斗鱼记录出借人的每笔投资，并生成符合法律法规的有效合同文件，且所有的
资金流向均由独立第三方机构代为管理，以确保用户资金安全；</p>
                    <p>2.九斗鱼平台的所有投资项目均通过多重风险控制审核，并对投资项目进行全面风
险管理，以最大程度保障出借人的资金安全；</p>
                    <p>3.九斗鱼平台全程采用 VeriSign256 位 SSL 强制加密证书进行数据加密传输，有效
保障银行账号、交易密码等机密信息在网络传输过程中不被查看、修改或窃取。</p>
                    <p>4.平台所有的投资项目均交纳 1%作为风险准备金，由东亚银行监管；查看<a href="/content/article/reservefund?id=815">《风险准备金账户》</a></p>
                </dd>
            </dl>
        </div> --}}

        <!-- 引入还款计划 -->
        <div class="Js_tab_main t-repayment" style="display: none;">
            <table class="table table-theadbg table-textcenter">


                <thead>
                <tr>
                    <td>预计还款时间</td>
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
