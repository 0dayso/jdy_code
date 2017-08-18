<div class="Js_tab_box t-center-left-4">
    <ul class="Js_tab t-center-tab tab-three">
        <li class="cur">项目详情<span></span></li>
        <li>常见问题<span></span></li>
        <li class="t-brn">还款计划<span></span></li>
    </ul>
    <div class="js_tab_content">
        <!--项目描述-->
        <div class="Js_tab_main t-center-left-5" style="display:block;">

            <dl class="t-center-left-6">
                <?php if(!empty($creditDetail['companyView']['factor_summarize'])): ?>
                    <dt><span></span>项目描述</dt>
                <dd>
                    <?php
                        echo isset($creditDetail['companyView']['factor_summarize']) ? htmlspecialchars_decode($creditDetail['companyView']['factor_summarize']) : null;
                    ?>
                </dd><br>

                <?php endif;?>

                {{--<dd>本次保理业务类型为明保理。原债权人与耀盛商业保理签署《有追索权国内保理合同》后，将其对应的应收账款转让给耀盛商业保理，耀盛商业保理将上述债权在中国人民银行征信中心应收账款质押登记公示系统登记，由耀盛保理为原债权人提供国内保理融资服务、账款管理及账款催收服务。原债权企业和原债务企业管理严格，履约能力强，还款意愿强，经审阅基础交易合同无误，合同单真实有效，同意放款。</dd><br>--}}



                <?php if(!empty($creditDetail['companyView']['factoring_opinion'])): ?>
                <dd>保理公司意见</dd>
                <dd>
                    <?php
                        echo isset($creditDetail['companyView']['factoring_opinion']) ? htmlspecialchars_decode($creditDetail['companyView']['factoring_opinion']) : null;
                    ?>
                </dd>

                <br>

                <?php endif;?>

                <?php if(!empty($creditDetail['companyView']['business_background'])): ?>
                <dd>【原债权企业介绍】</dd>
                <dd>
                    <?php
                        echo isset($creditDetail['companyView']['business_background']) ? htmlspecialchars_decode($creditDetail['companyView']['business_background']) : null;
                    ?>
                </dd>

                <br>

                <?php endif;?>

                <?php if(!empty($creditDetail['companyView']['introduce'])): ?>
                <dd>【原债务企业介绍】</dd><dd>
                    <?php
                        echo isset($creditDetail['companyView']['introduce']) ? htmlspecialchars_decode($creditDetail['companyView']['introduce']) : null;
                    ?>
                </dd>

                <br>

                <?php endif;?>

            </dl>

            <?php if(!empty($creditDetail['companyView']['trade_info_links']) || !empty($creditDetail['companyView']['factor_info_links'])) { ?>

            <div class="default t-center-img">
                <a href="#" class="prev">&lsaquo;</a>
                <div class="carousel">
                    <ul>
                        <?php
                        if(!empty($creditDetail['companyView']['trade_info_links'])) {
                            foreach($creditDetail['companyView']['trade_info_links'] as $k1=> $image){
                                if(($k1+1)/3 == 0){
                                    echo '<li class="t-pr0">';
                                }else{
                                    echo '<li>';
                                }
                        ?>
                            <img width="177" height="127" src="<?php echo $image['thumb'][$view_ssl];?>" big-src="<?php echo $image['src'][$view_ssl];?>"><span>{{ $image['title'] }}</span>
                            </li>
                            <?php
                            }
                        }
                        ?>

                        <?php
                        if(!empty($creditDetail['companyView']['factor_info_links'])) {
                            foreach($creditDetail['companyView']['factor_info_links'] as $k2=> $image){
                            if(($k2+1)/3 == 0){
                                echo '<li class="t-pr0">';
                            }else{
                                echo '<li>';
                            }
                        ?>
                            <img width="177" height="127" src="<?php echo $image['thumb'][$view_ssl];?>" big-src="<?php echo $image['src'][$view_ssl];?>"><span>{{ $image['title'] }}</span>
                            </li>
                        <?php
                            }
                        }
                        ?>
                    </ul>
                </div>
                <a href="#" class="next">&rsaquo;</a>
                <div class="clear"></div>
            </div>

            <?php } ?>

            <div class="t-online"></div>
            <dl class="t-center-left-6">
                <dt><span></span>资金安全</dt>
                <dd>
                    <p>1.整个交易流程九斗鱼不触碰用户资金，交易资金完全由第三方支付机构监管</p>
                    <p>2.东亚银行依据专款专用原则，对每笔资金进出进行严格监控，确保用户账户资金安全</p>
                    <p>3.专注中小企业融资领域11年，运用国内唯一获得专利技术的中小企业信用评价体系RISKCALC为您推荐优质债权</p>
                </dd>
            </dl>

            {{--<div class="t-online"></div>
            <dl class="t-center-left-6">
                <dt><span></span>到期前如何赎回？</dt>
                <dd>该出借计划满标审核日起算，30天后可发起债权转让收回资金，查看<a href="/help/894.html" target="_blank">债权转让详细规则</a></dd>
            </dl>--}}
            <div class="t-online"></div>
            <dl class="t-center-left-6">
                <dt><span></span>到期后如何赎回？</dt>
                <dd>该项目到期后本金和利息会自动存入您的九斗鱼账户，申请提现即可转入您绑定的银行卡中</dd>
            </dl>

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
        <div class="Js_tab_main t-repayment" style="display: none;">
            <table class="table table-theadbg table-textcenter">
                <thead>
                <tr>
                    <td>预计还款时间</td>
                    <td>类型</td>
                    <td>预计还款金额（元）</td>
                </tr>
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

                </tbody>
            </table>

        </div>

    </div>
</div>
