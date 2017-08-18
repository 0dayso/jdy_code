<?php
$loginUser 			= isset($data['loginUser']) ? $data['loginUser'] : null;
$project   			= isset($data['project']) ? $data['project'] : null;
$credit	  			= isset($data['credit']) ? $data['credit'] : null;
$signDay   			= isset($data['signDay']) ? $data['signDay'] : null;
$invested  			= isset($data['invested']) ? $data['invested'] : null;
$investing 			= isset($data['investing']) ? $data['investing'] : null;
$refundTime			= isset($data['refundTime']) ? $data['refundTime'] : null;
$refundPlan			= isset($data['refundPlan']) ? $data['refundPlan'] : null;
$projectLinkCredit	= isset($data['projectLinkCredit']) ? $data['projectLinkCredit'] : null;
?>
<div style="position:absolute;top:5%;right:5%;z-index:1;background-image: url('{{ env('APP_URL_PC') }}/static/img/seal_ebq.png'); background-repeat: no-repeat; height: 150px;width:150px; background-position: center center;background-size: cover;"></div>
<h2 style="text-align: center;">
	<span>债权转让协议</span>
</h2>
<p>
	<span>甲方（债权受让人）：{{ $loginUser['real_name'] or null }}</span>
</p>
<p>
	<span>身份证号：{{ $loginUser['identity_card'] or null }}</span>
</p>
<p>
	<span></span>
</p>
<p>
	<span>乙方（债权出让人）：池洪英 </span>
</p>
<p>
	<span>身份证号码：110222195008065720</span>
</p>
<p>
	<span></span>
</p>
<p>
	<span>丙方（平台服务方）：星果时代信息技术有限公司 </span>
</p>
<p>
	<span>营业执照注册号：91110108MA00257T3R</span>
</p>
<p>
	<span></span>
</p>
<p>
	<span>根据《中华人民共和国合同法》及相关法律法规的规定，三方遵循平等、自愿、互利和诚实信用原则友好协商，就甲方通过丙方运营的九斗鱼（域名为www.9douyu.com）向乙方购买债权等事宜达成一致，以兹信守。</span>
</p>
<p>
	<span>第一条 债权转让</span>
</p>
<p>
	<span>甲方同意通过丙方运营的九斗鱼平台向乙方购买债权，债权项目详情如下：</span>
</p>
<table border="1">
	<tbody>
		<tr>
			<td colspan="6" align="center">债权基本信息</td>
		</tr>
		<tr>
			<td align="center">项目名称</td>
			<td align="center">借款企业名称</td>
			<td align="center">借款人姓名</td>
			<td align="center">借款人证件号</td>
			<td align="center">借款用途</td>
			<td align="center">出借金额</td>
		</tr>
		<?php
		if(!empty($credit)){
		$rowSpan			   =  count($credit);
		$projectLinkCreditInfo =  !empty($projectLinkCredit['credit_info']) ? json_decode($projectLinkCredit['credit_info'], true) : [];

		foreach($credit as $kCredit => $creditInfo){
		$loan_username 		= $loan_user_identity = [];
		$totalLoanUserNum   = 1;
		$company_name 		= null;

		if(!empty($creditInfo['loan_username']) && !empty($creditInfo['loan_user_identity'])){

			//借款人信息
			$loan_username 		 = explode(',', $creditInfo['loan_username']);

			$loan_user_identity  = explode(',', $creditInfo['loan_user_identity']);
			$totalLoanUserNum	 = empty(count($loan_username) ) ? 1 : count($loan_username);
		}

		//企业名称
		if(!empty($creditInfo['company_name'])){
			$company_name =$creditInfo['company_name'];
		}
		if(!empty($creditInfo['plan_name'])){
			$company_name =$creditInfo['plan_name'];
		}

		if($creditInfo['type'] == 50){
			$projectWay = $creditInfo['source'];
		}else{
			$projectWay = $creditInfo['type'];
		}
		// 金额
		$investCash	= 0.00;
		if(!empty($projectLinkCreditInfo)){
			foreach($projectLinkCreditInfo as $kLink=> $creditLinkInfo){
				if($creditLinkInfo['type'] == $projectWay && $creditLinkInfo['credit_id'] ==$creditInfo['id']){
					$investCash  = $invested['cash'] * ($creditLinkInfo['credit_cash']/$project['total_amount']);
				}
			}
		}


		?>
		<tr>
			<?php  if($kCredit == 0){?>
			<td rowspan="{{ count($credit) }}" align="center">闪电付息 {{$project['invest_time_note'] or null }} </td>
			<?php } ?>
			<td rowspan="1" align="center">{{ $company_name }}</td>
			<td align="center">
				<?php
				if(count($loan_username) > 0){
				for ($i = 0; $i <= count($loan_username); $i++) {
				if(!empty($loan_username[$i])){
				?>
				<table>
					<tr><td>{{ $loan_username[$i] or null }}</td></tr>
				</table>
				<?php
				}
				}
				}
				?>
			</td>
			<td align="center">
				<?php
				if(count($loan_user_identity) > 0){
				for ($i = 0; $i <= count($loan_user_identity); $i++) {
				if(!empty($loan_user_identity[$i])){
				?>
				<table>
					<tr><td>{{ $loan_user_identity[$i] or null }}</td></tr>
				</table>
				<?php
				}
				}
				}
				?>
			</td>
			<td rowspan="1" align="center">{{ $creditInfo['usage'] or null }}</td>
			<td rowspan="1" align="center"><?php echo \App\Tools\ToolMoney::moneyFormat($investCash); ?></td>
		</tr>

		<?php
		}
		}
		?>

	</tbody>
</table>
<table class="ke-zeroborder" border="0">
	<tbody>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</tbody>
</table>
<table border="1">
	<tbody>
		<tr>
			<td colspan="7" align="center">借款人月还款情况下债权收益信息</td>
		</tr>
		<tr>
			<td align="center">项目名称</td>
			<td align="center">初始借款金额</td>
			<td align="center">本次转让债权</td>
		    <td align="center">需支付对价</td>
			<td align="center">投资日期</td>
			<td align="center">投资期限（天）</td>
			<td align="center">预期年收益率</td>
		</tr>
		<tr>
			<td align="center">九省心 {{ $project['invest_time_note'] or null }}</td>
			<td align="center"><?php echo isset($project['total_amount']) ? \App\Tools\ToolMoney::moneyFormat($project['total_amount']) : null; ?></td>
			<td align="center"><?php echo isset($invested["cash"]) ? \App\Tools\ToolMoney::moneyFormat($invested["cash"]) : null;?></td>
			<?php
			$cash = 0.00;
			if(!empty($invested)){
				if($invested['bonus_type'] == 300){
					$cash = $invested["cash"] -  $invested['bonus_value'];
				}else{
					$cash = $invested["cash"];
				}
			}

			?>
			<td align="center"><?php echo \App\Tools\ToolMoney::moneyFormat($cash); ?></td>
			<td align="center"><?php
				if(isset($invested["created_at"])){
					echo date('Y-m-d', strtotime($invested["created_at"]));
				}
				?></td>
			<td align="center"><?php echo (isset($project["end_at"]) && isset($invested['created_at'])) ? \App\Tools\ToolTime::getDayDiff($invested['created_at'], $project["end_at"]) : null; ?></td>
			<td align="center"><?php echo isset($project['profit_percentage']) ? App\Tools\ToolMoney::moneyFormat($project['profit_percentage']) : null; ?>%</td>
		</tr>
	</tbody>
</table>
<p>
	<span>第二条 甲方的权利和义务</span>
</p>
<p>
	<span>1. 甲方有利用丙方提供的平台自由选择债权进行购买的权利；</span>
</p>
<p>
	<span>2. 甲方有权按照约定时间收取本金和利息；</span>
</p>
<p>
	<span>3. 甲方有权将已购买的债权在网站内进行转让；</span>
</p>
<p>
	<span>4. 甲方有义务向乙方和丙方提供个人真实信息，因甲方提供虚假信息而造成的一切法律后果均由甲方承担；</span>
</p>
<p>
	<span>5. 甲方有义务按照丙方要求进行平台的操作，向丙方提供真实有效的联系方式，并及时查收丙方发出的所有消息（包括但不限于电子邮件、手机短信等）。因甲方个人操作不当以及疏于查收信息而造成的损失由甲方承担；</span>
</p>
<p>
	<span>6. 甲方购买乙方债权成功后，将承继乙方与项目的借款人签订的相应《借款协议》项下出借人的权利并承担出借人的义务。如相应《借款协议》中约定了由第三方承担担保责任的，第三方应根据相应《借款协议》及担保协议的约定继续对甲方承担连带保证责任。</span>
</p>
<p>
	<span>第三条 乙方的权利和义务</span>
</p>
<p>
	<span>1. 乙方有权在丙方平台上发布债权交易信息；</span>
</p>
<p>
	<span>2. 乙方保证其所用于转让的标的债权的资金来源合法，乙方是该资金的合法所有人，如果第三方对资金归属、合法性问题发生争议，由乙方自行负责解决；</span>
</p>
<p>
	<span>3. 乙方有义务协助甲方向债务人催收利息和本金，并按照本协议的约定通过丙方支付给甲方；</span>
</p>
<p>
	<span>4. 乙方有义务将债权交易信息通知债务人；</span>
</p>
<p>
	<span>5. 乙方有义务向甲方公布原债权债务关系的详细情况。</span>
</p>
<p>
	<span>第四条 丙方的权利和义务</span>
</p>
<p>
	<span>1. 丙方作为信息对接平台，有向甲乙双方提供债权交易居间服务的义务；</span>
</p>
<p>
	<span>2. 丙方有向甲乙双方收取债权交易服务费的权利；</span>
</p>
<p>
	<span>3. 丙方须对甲、乙两方的信息依法保密。 </span>
</p>
{{-- <p>
	<span>第五条 债权的担保</span>
</p>
<p>
	<span>根据乙方、丙方和唯达信用担保（北京）有限公司三方签订的《最高额担保合同》 ，唯达信用担保（北京）有限公司对本合同下的债权交易的本金及其利息提供不可撤销的连带责任担保。若债务人不能在约定时间向甲方支付本金及利息，唯达信<br/>用担保（北京）有限公司有义务在约定时间的1个工作日内向甲方支付本金和利息。</span>
</p> --}}
<p>
	<span>第五条 关于站内债权转让</span>
</p>
<p>
	<span>1. 甲方可在站内将已购买的债权进行再次转让，转让时须遵守网站的相关规则； </span>
</p>
<p>
	<span>2. 从站内债权转让成功之日起，债权的所有权和收益权由本协议的甲方变为新的债权受让人，本协议自动失效。 </span>
</p>
<p>
	<span>第六条 风险提示</span>
</p>
<p>
	<span>1. 政策风险：国家因宏观政策、财政政策、货币政策、行业政策、地区发展政策、法律法规等因素引起的政策风险；</span>
</p>
<p>
	<span>2. 信用风险：当债务人发生资金状况的风险，或者债务人的还款意愿发生变化时，甲方的出借资金可能无法按时收回；</span>
</p>
<p>
	<span>3. 不可抗力：由于战争、动乱、自然灾害等不可抗力因素的出现而可能导致甲方资产损失的风险。&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
</p>
<p>
	<span>第七条 税务处理 </span>
</p>
<p>
	<span>甲方因购买债权而获得的收益（包括但不限于利息和罚息等）应纳税款由甲方自行申报及缴纳。&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
</p>
<p>
	<span>第八条 争议处理 </span>
</p>
<p>
	<span>在本协议的履行过程中，如果发生争议，由各方协商处理。若各方协商不能达成一致，向北京市海淀区人民法院提起诉讼。</span>
</p>
<p>
	<span>第九条 其他条款</span>
</p>
<p>
	<span>本协议自 {{ $signDay }} 起生效，一式三份，甲、乙、丙各保留一份。 </span>
</p>
<p>
	<span>
		<br />
	</span>
</p>
<div style="background-image: url('{{ assetUrlByCdn('static/img/seal_xingguo.png') }}'); background-repeat: no-repeat; height: 170px; background-position: 50px 0px;">

<p>
	<span>甲方（债权购买人）：{{ $loginUser['real_name'] or null }}</span>
</p>
<p>
	<span>乙方（债权出让人）：池洪英</span>
</p>
<p>
	<span>丙方（平台服务方）：星果时代信息技术有限公司 </span>
</p>
	</div>