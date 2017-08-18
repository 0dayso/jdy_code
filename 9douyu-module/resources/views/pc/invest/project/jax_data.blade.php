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
