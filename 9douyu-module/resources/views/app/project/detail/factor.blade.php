@include('wap.common.appBase')
@section('title')<title>九安心详情</title>@show
@section('content')
<article>
     <div class="t-coupon">
      	<h3 class="t-detail"><span class="t-icon1"></span>{{ $project["default_title"] }}</h3>
      	<div class="t-detail-2">
      		<table class="t-detail-1">
      			<tr>
      				<td width="36%">借款利率</td>
      				<td>{{ $project["percentage_float_one"] }}%</td>
      			</tr>
      			<tr>
      				<td>期限</td>
      				<td>{{ $project["format_invest_time"] }}{{ $project["invest_time_unit"] }}</td>
      			</tr>
      			<tr>
      				<td>预计到期日</td>
      				<td>{{ $project["refund_end_time"] }}</td>
      			</tr>
      			<tr>
      				<td>起购金额</td>
      				<td>{{ $project["invest_min_cash"] }}元起投</td>
      			</tr>
      			<tr>
      				<td>还款方式</td>
      				<td>{{ $project["refund_type_text"] }}</td>
      			</tr>
      			<tr>
      				<td>赎回</td>
      				<td>
	      				<p>资金按期自动返还至账户余额，申请提现即可转入绑定的银行卡中</p>
				    </td>
      			</tr>

      		</table>
      	</div>
    </div>

    <div class="t-coupon">
      	<h3 class="t-coupon-1"><span class="t-icon1"></span>项目描述</h3>
            <div class="t-detail-12">
                  <p class="t-detail-11">
                      <?php
                      if(!empty($company["credit_company"])){
                          //echo htmlspecialchars_decode($company["credit_company"]);
                      }
                      ?>
                  </p>
                  <div class="t-detail-13">
                        <span class="t-detail-icon"></span>
                        <h4>原债权企业介绍</h4>
                        <p>
                            <?php
                            if(!empty($company["credit_company"])){
                                echo htmlspecialchars_decode($company["factor_summarize"]);
                            }
                            ?>
                        </p>
                  </div>
            </div> 
    </div>
      <div class="t-coupon">
                  <h3 class="t-coupon-1"><span class="t-icon1"></span>风险控制</h3>
                  <div class="t-coupon-2">
                  <p class="t-detail-14">平台对每个投资项目都有相应保障措施，同时建立了风险准备金账户，对平台每个投资项目提取 1%作为风险准备金。</p>
                  </div>
      </div>
      <div class="t-coupon">
                  <h3 class="t-coupon-1"><span class="t-icon1"></span>资金安全</h3>
                  <div class="t-coupon-2">
                  <p class="t-detail-14"><span>●</span>九斗鱼记录出借人的每笔投资，并生成符合法律法规的有效合同文件，且所有的
资金流向均由独立第三方机构代为管理，以确保用户资金安全；</p>
                  <p class="t-detail-14"><span>●</span>九斗鱼平台的所有投资项目均通过多重风险控制审核，并对投资项目进行全面风
          险管理，以最大程度保障出借人的资金安全；</p>
                  <p class="t-detail-14"><span>●</span>九斗鱼平台全程采用 VeriSign256 位 SSL 强制加密证书进行数据加密传输，有效
          保障银行账号、交易密码等机密信息在网络传输过程中不被查看、修改或窃取。</p>
                  <p class="t-detail-14"><span>●</span>平台所有的投资项目均交纳 1%作为风险准备金，由东亚银行监管；查看《风险准备金账户》</p>
            </div>
      </div>

    <?php if(!empty($company['trade_info_links'])) { ?>
      <div class="t-coupon t-mb20px">
                  <h3 class="t-coupon-1"><span class="t-icon1"></span>基础交易资料</h3>
                  <div class="t-detail-15">
                      @foreach ( $company["trade_info_links"] as $image )
                        <img src="<?php echo $image['thumb'][$view_ssl];?>" class="t-detail-16">
                      @endforeach
                  </div>
      </div>
    <?php } ?>

    <?php if(!empty($company['factor_info_links'])) { ?>
    <div class="t-coupon t-mb20px">
        <h3 class="t-coupon-1"><span class="t-icon1"></span>企业照片</h3>
        <div class="t-detail-15">
            @foreach ( $company["factor_info_links"] as $image )
                <img src="<?php echo $image['thumb'][$view_ssl];?>" class="t-detail-16">
            @endforeach
        </div>
    </div>
    <?php } ?>


</article>
@show
@section('jsPage')
<script type="text/javascript">
$(document.body).css("background","#f4f4f4");
</script> 

@show
