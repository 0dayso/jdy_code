<div class="Js_tab_box t-center-left-4">
    <ul class="Js_tab t-center-tab tab-two">
        <li class="cur">项目详情<span></span></li>
        {{-- <li>安全保障<span></span></li> --}}
        <li class="t-brn">还款计划<span></span></li>
    </ul>
    <?php
    $company = isset($creditDetail['companyView']) ? $creditDetail['companyView'] : null;
    ?>
    <div class="js_tab_content">
        <!--项目描述-->
        <div class="Js_tab_main t-center-left-5" style="display:block;">
            
            <!-- 债权转让 -->
            <table class="detail-table">
                <tr>
                    <th width="20%">名称</th>
                    <td>【转】保理贷-12月期</td>
                </tr>
                <tr>
                    <th>原出借款利率</th>
                    <td>12%</td>
                </tr>
                <tr>
                    <th>原出借期限</th>
                    <td>3个月</td>
                </tr>
                <tr>
                    <th>还款方式</th>
                    <td>先息后本</td>
                </tr>
                <tr>
                    <th>到期还款日</th>
                    <td>2017-06-27</td>
                </tr>
                <tr>
                    <th>原借款总额</th>
                    <td>1,000,000.00元</td>
                </tr>
                <tr>
                    <th>风险等级</th>
                    <td>保守型</td>
                </tr>
                <tr>
                    <th>出借条件</th>
                    <td>一次承接</td>
                </tr>
                <tr>
                    <th>提前赎回方式</th>
                    <td>持有债权项目30天（含）即可申请债权转让，赎回时间以实际转让成功时间为准</td>
                </tr>
                <tr>
                    <th>项目介绍</th>
                    <td><a href="#" class="blue">【查看原项目详情】</a></td>
                </tr>
                {{--<tr>
                    <th>协议范本</th>
                    <td><a href="javascript:;" class="blue" data-target="moduldetail">【点击查看】</a></td>
                </tr>--}}
            </table> 
            <!-- End 债权转让 -->
            

            <!-- 保理债权 -->
            <table class="detail-table">
                <tr>
                    <th width="20%">项目名称</th>
                    <td>九安心  222</td>
                </tr>
                <tr>
                    <th>借款利率</th>
                    <td>9%</td>
                </tr>
                <tr>
                    <th>借款期限</th>
                    <td>60天</td>
                </tr>
                <tr>
                    <th>还款方式</th>
                    <td>一次到期还本</td>
                </tr>
                <tr>
                    <th>到期还款日</th>
                    <td>2017-06-27</td>
                </tr>
                <tr>
                    <th>借款总额</th>
                    <td>1,000,000.00元</td>
                </tr>
                <tr>
                    <th>募集开始时间</th>
                    <td>2017-04-02（募集时间最长不超过20天）</td>
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
                    <td>九安心产品是保理公司将应收账款收益权转让给出借人；原债权企业多为国企及上市公司，切负有连带责任，借款期限一般为30~90天，适合偏好短期，且稳定的出借人。</td>
                </tr>
                {{--<tr>
                    <th>协议范本</th>
                    <td><a href="javascript:;" class="blue" data-target="moduldetail">【点击查看】</a></td>
                </tr>--}}
            </table>
            <dl class="detail-info-style1">
                <dt>债权企业信息</dt>
                <dd>
                    <p><span>债权企业名称：北京某科技公司</span><span>企业证件号：911111111****</span></p>
                    <p><span>经营地址：北京市朝阳区</span><span>借款用途：资金周转</span></p>
                </dd>
            </dl>
            <!-- End 保理债权 -->

            <!-- 房抵 -->
            <table class="detail-table">
                <tr>
                    <th width="20%">项目名称</th>
                    <td>九省心 3月期  222</td>
                </tr>
                <tr>
                    <th>借款利率</th>
                    <td>12%</td>
                </tr>
                <tr>
                    <th>借款期限</th>
                    <td>12个月</td>
                </tr>
                <tr>
                    <th>还款方式</th>
                    <td>先息后本</td>
                </tr>
                <tr>
                    <th>到期还款日</th>
                    <td>2017-06-27</td>
                </tr>
                <tr>
                    <th>融资总额</th>
                    <td>1,000,000.00元</td>
                </tr>
                <tr>
                    <th>融资时间</th>
                    <td>最长不超过20天</td>
                </tr>
                <tr>
                    <th>风险等级</th>
                    <td>稳健性</td>
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
                    <td>借款人因资金周转需要，故以个人名下房产作为抵押进行借款。此类借款人有稳定的经济收入及良好的信用意识。</td>
                </tr>
                {{--<tr>
                    <th>协议范本</th>
                    <td><a href="javascript:;" class="blue" data-target="moduldetail">【点击查看】</a></td>
                </tr>--}}
            </table>
            <dl class="detail-info-style1">
                <dt>借款人信息</dt>
                <dd>
                    <p><span>借款人姓名：陈**</span><span>性别：男</span></p>
                    <p><span>年龄：33</span><span>婚姻：已婚</span></p>
                    <p><span>身份证号码：110**********89</span><span>户籍：北京市海淀区</span></p>
                    <p><span>借款用途：资金周转</span></p>
                </dd>
            </dl>
            <dl class="detail-info-style1">
                <dt>抵押物信息</dt>
                <dd>
                    <p><span>建筑面积：100平方米</span><span>评估单价：2.5万元</span></p>
                    <p><span>评估总值：250万元</span><span>抵押率：50%</span></p>
                </dd>
            </dl>
            <!-- End 房抵 -->

            <!-- 信贷债权 -->
            <table class="detail-table">
                <tr>
                    <th width="20%">项目名称</th>
                    <td>九省心 3月期  222</td>
                </tr>
                <tr>
                    <th>借款利率</th>
                    <td>12%</td>
                </tr>
                <tr>
                    <th>借款期限</th>
                    <td>12个月</td>
                </tr>
                <tr>
                    <th>还款方式</th>
                    <td>先息后本</td>
                </tr>
                <tr>
                    <th>到期还款日</th>
                    <td>2017-06-27</td>
                </tr>
                <tr>
                    <th>融资总额</th>
                    <td>1,000,000.00元</td>
                </tr>
                <tr>
                    <th>融资时间</th>
                    <td>最长不超过20天</td>
                </tr>
                <tr>
                    <th>风险等级</th>
                    <td>稳健性</td>
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
                    <td>债权借款人均为工薪精英人群，该人群有较高的教育背景、稳定的经济收入及良好的信用意识。</td>
                </tr>
                {{--<tr>
                    <th>协议范本</th>
                    <td><a href="javascript:;" class="blue" data-target="moduldetail">【点击查看】</a></td>
                </tr>--}}
            </table>
            <dl class="detail-info-style1">
                <dt>借款人信息</dt>
                <dd>
                    <p><span>借款人姓名：陈**</span><span>性别：男</span></p>
                    <p><span>年龄：33</span><span>婚姻：已婚</span></p>
                    <p><span>身份证号码：110**********89</span><span>户籍：北京市海淀区</span></p>
                    <p><span>借款用途：资金周转</span></p>
                </dd>
            </dl>
            
            <!-- End 信贷债权 -->

            {{--协议范本弹窗--}}
            {{--<!-- 协议范本 -->
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
                                
                            </tbody>
                        </table>
                    </div>
                    <a href="#" data-toggle="mask" data-target="js-mask" class="btn btn-blue btn-large t-alert-btn t-mt30px">关闭</a>

                </div>
            </div> --}}
            
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
                        <td>{{ number_format($plan['refund_cash'],2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>


    </div>
</div>