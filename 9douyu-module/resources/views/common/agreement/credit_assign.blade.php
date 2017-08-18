<?php
$loginUser 			= isset($data['loginUser']) ? $data['loginUser'] : null;
$assignUser			= isset($data['assignUser']) ? $data['assignUser'] : null;
$assignCredit		= isset($data['assignCredit']) ? $data['assignCredit'] : null;
$project   			= isset($data['project']) ? $data['project'] : null;
$credit	  			= isset($data['credit']) ? $data['credit'] : null;
$signDay   			= isset($data['signDay']) ? $data['signDay'] : null;
$invested  			= isset($data['invested']) ? $data['invested'] : null;
$investing 			= isset($data['investing']) ? $data['investing'] : null;
$refundTime			= isset($data['refundTime']) ? $data['refundTime'] : null;
$refundPlan			= isset($data['refundPlan']) ? $data['refundPlan'] : null;
$projectLinkCredit	= isset($data['projectLinkCredit']) ? $data['projectLinkCredit'] : null;

		//债权类型
		$projectWay = null;
		if(count($credit) > 1){
			$projectWay = 60;
		}elseif(!empty($credit[0])){
			$creditInfo = $credit[0];
			if($creditInfo['type'] == 50){
				$projectWay = $creditInfo['source'];
			}else{
				$projectWay = $creditInfo['type'];
			}
		}

?>
<div style="position:absolute;top:5%;right:5%;z-index:1;background-image: url('{{ env('APP_URL_PC') }}/static/img/seal_ebq.png'); background-repeat: no-repeat; height: 150px;width:150px; background-position: center center;background-size: cover;"></div>
<h2 style="text-align:center;">
	站内债权转让协议
</h2>
<p>
	本债权转让协议（下称“本协议”）由以下双方于签署：
</p>
<p>
	甲方（债权转让人）：{{ $assignUser['real_name'] or null }}
</p>
<p>
	身份证号：{{ $assignUser['identity_card'] or null  }}
</p>
<p>
	乙方（债权受让人）：{{ $loginUser['real_name'] or null }}
</p>
<p>
	身份证号：{{ $loginUser['identity_card'] or null }}
</p>
<p>
	丙方（平台服务方）：星果时代信息技术有限公司&nbsp;
</p>
<p>
	营业执照注册号：91110108MA00257T3R&nbsp;
</p>
<p>
	根据《中华人民共和国合同法》及相关法律法规的规定，三方遵循平等、自愿、互利和诚实信用原则友好协商，就甲方通过丙方运营的九斗鱼（域名为www.9douyu.com）向乙方转让债权事宜，双方经协商一致，以兹信守。
</p>
<p>
	第一条&nbsp;债权转让
</p>
<p>
	乙方同意通过丙方运营的九斗鱼平台向甲方购买债权，&nbsp;甲方同意将其通过丙方运营的九斗鱼平台的居间协助而形成的有关债权（下称“标的债权”）转让给乙方，乙方同意受让该等债权。债权具体信息如下：
</p>
<p></p>
<p>
	标的债权信息：&nbsp;
</p>
<p></p>
<table align="center" border="1" width="100%">
	<tbody>
		<tr>
			<td colspan="7" align="center">
				债权基本信息@if ( $projectWay != 60 )【九省心 {{ $project['invest_time_note'] or null }} {{ $project['id'] }}】@endif
			</td>
		</tr>
		<tr>
			<td>
				借款企业名称
			</td>
			<td>
				借款人姓名
			</td>
			<td>
				借款人证件号
			</td>
			<td>
				初始借款金额
			</td>
			<td>
				还款起始日期
			</td>
			<td>
				还款期限
			</td>
			<td>
				预计债权<br />收益率
			</td>
		</tr>
		@if(!empty($credit))
	    	{{--@foreach ( $credit as $key => $creditInfo )--}}
			<?php
			$creditInfo			= $credit;
			$company_name 		= null;
			$loan_username = $loan_user_identity = [];
			//企业名称
			if(!empty($creditInfo['company_name'])){
				$company_name =$creditInfo['company_name'];
			}
			if(!empty($creditInfo['plan_name'])){
				$company_name =$creditInfo['plan_name'];
			}


			if(!empty($creditInfo['loan_username']) && !empty($creditInfo['loan_user_identity'])){
				$loan_username 		 = explode(',', $creditInfo['loan_username']);

				$loan_user_identity  = explode(',', $creditInfo['loan_user_identity']);
			}
			?>
		<tr>
            <td align="center">{{ $company_name }}</td>

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
			<td align="center"><?php echo isset($project['total_amount']) ? \App\Tools\ToolMoney::moneyFormat($project['total_amount']) : null; ?></td>
			<td rowspan="1">
				{{ $refundTime }}
			</td>
			<td rowspan="1">
				{{ $project["invest_time_note"] or null}}
			</td>
			<td rowspan="1">
				<?php echo isset($project['profit_percentage']) ? App\Tools\ToolMoney::moneyFormat($project['profit_percentage']) : null; ?>%
			</td>
        </tr>
        {{--@endforeach--}}
		@endif
	</tbody>
</table>
<p></p>
<p></p>
<p>
	标的债权转让信息
</p>
<table align="center" border="1" width="100%">
	<tbody>
		<tr>
			<td colspan="6">
				债权转让信息 【变现宝 {{ $assignCredit['id'] or null }}】
			</td>
		</tr>
		<tr>
			<td>
				债权数额
			</td>
			<td>
				折让率
			</td>
			<td>
				转让价格
			</td>
			<td>
				转让手续费
			</td>
			<td>
				转让日期
			</td>
			<td>
				剩余还款期限<br />（天数）
			</td>
		</tr>
		<tr>
			<td>
				<?php echo isset($assignCredit['total_amount']) ? App\Tools\ToolMoney::moneyFormat($assignCredit['total_amount']) : null; ?>
			</td>
			<td>{{ 0.00 }}%</td>
			<td>
				<?php echo isset($assignCredit['total_amount']) ? App\Tools\ToolMoney::moneyFormat($assignCredit['total_amount']) : null; ?>
			</td>
			<td>
				0.00
			</td>
			<td>
				<?php
				if(isset($invested["created_at"])){
					echo date('Y-m-d', strtotime($invested["created_at"]));
				}
				?>
			</td>
			<td><?php echo (isset($project["end_at"]) && isset($invested['created_at'])) ? \App\Tools\ToolTime::getDayDiff($invested['created_at'], $project["end_at"]) : null; ?></td>
		</tr>

	</tbody>
</table>
<p>
	第二条&nbsp;债权转让流程
</p>
<p>
	1.双方同意并确认，双方通过自行或授权有关方根据九斗鱼网站有关规则和说明，在九斗鱼网站进行债权转让和受让购买操作等方式确认签署本协议；
</p>
<p>
	2.双方接受本协议且九斗鱼平台审核通过时，本协议立即成立,并待转让价格款项支付完成时生效。协议成立的同时，甲乙双方即不可撤销地授权九斗鱼自行或委托第三方支付机构或合作的金融机构，将债权转让款项在扣除甲方应支付给九斗鱼的转让手续费之后划转或支付给甲方，上述转让款项划转完成即视为本协议生效且标的债权转让成功；同时甲方不可撤销地授权九斗鱼将其代为保管的甲方与标的债权原转让人签署的电子文本形式的《债权转让协议》及借款人相关信息在九斗鱼网站有关系统版块向乙方进行展示；
</p>
<p>
	3.本协议生效且标的债权转让成功后，双方特此委托九斗鱼将标的债权的转让事项及有关信息通知与标的债权对应的借款人；
</p>
<p>
	4.自标的债权转让成功之日起，乙方成为标的债权的债权人，承继借款协议项下出借人的权利并承担出借人的义务。
</p>
<p>
	第三条&nbsp;甲方的权利和义务&nbsp;&nbsp;
</p>
<p>
	1.甲方有权在丙方平台上发布债权转让信息；&nbsp;
</p>
<p>
	2.甲方保证其所用于转让的标的债权的合法性，甲方是该债权的合法所有人，如果第三方对债权的归属、合法性问题发生争议，由甲方自行负责解决；&nbsp;
</p>
<p>
	3.甲方有义务将债权交易信息通知债务人；&nbsp;
</p>
<p>
	4.甲方有义务向乙方披露原债权债务关系的详细情况；
</p>
<p>
	5.甲方保证其转让的债权系其合法、有效的债权，不存在转让的限制。6.甲方同意并承诺按有关协议及九斗鱼网站的相关规则和说明向九斗鱼支付手续费。
</p>
<p>
	第四条&nbsp;乙方的权利和义务&nbsp;
</p>
<p>
	1.乙方有利用丙方提供的平台自由选择债权进行购买的权利，购买债权的资金来源合法，如因资金来源发生纠纷，由乙方自行解决；&nbsp;
</p>
<p>
	2.乙方有权按照约定时间收取本金和利息；&nbsp;
</p>
<p>
	3.乙方在九斗鱼债权转让专区购买的债权不能再次进行转让；&nbsp;
</p>
<p>
	4.乙方有义务向甲方和丙方提供个人真实信息，因乙方提供虚假信息而造成的一切法律后果均由乙方承担；&nbsp;
</p>
<p>
	5.乙方有义务按照丙方要求进行平台的操作，向丙方提供真实有效的联系方式，并及时查收丙方发出的所有消息（包括但不限于电子邮件、手机短信等）。因乙方个人操作不当以及疏于查收信息而造成的损失由乙方承担；&nbsp;
</p>
<p>
	6.乙方购买甲方债权成功后，将承继原始借款人与项目的借款人签订的相应《借款协议》项下出借人的权利并承担出借人的义务。如相应《借款协议》中约定了由第三方承担担保责任的，第三方应根据相应《借款协议》及担保协议的约定继续对乙方承担连带保证责任。&nbsp;&nbsp;
</p>
<p>
	第五条&nbsp;丙方的权利和义务&nbsp;
</p>
<p>
	1.丙方作为信息对接平台，有向甲乙双方提供债权交易居间服务的义务；&nbsp;
</p>
<p>
	2.丙方有向甲乙双方收取债权交易服务费的权利；&nbsp;
</p>
<p>
	3.丙方须对甲乙双方的信息依法保密（本协议另有约定的除外）。&nbsp;&nbsp;
</p>
{{-- <p>
	第六条&nbsp;债权的担保&nbsp;
</p>
<p>
	根据原始借款人、丙方和唯达信用担保（北京）有限公司三方签订的《最高额担保合同》&nbsp;，唯达信用担保（北京）有限公司对本合同下的债权交易的本金及其利息提供不可撤销的连带责任担保。若债务人不能在约定时间向乙方支付本金及利息，唯达信<br/>用担保（北京）有限公司有义务在约定时间的1个工作日内向乙方支付本金和利息。
</p> --}}
<p>
	第六条&nbsp;违约
</p>
<p>
	1.协议各方同意，如果一方违反其在本协议中所作的保证、承诺或任何其他义务，致使其他方遭受或发生损害、损失等责任，违约方须向守约方赔偿守约方因此遭受的一切经济损失；
</p>
<p>
	2.协议相关方均有过错的，应根据各方实际过错程度，分别承担各自的违约责任。
</p>
<p>
	第七条&nbsp;风险提示&nbsp;
</p>
<p>
	1.政策风险：国家因宏观政策、财政政策、货币政策、行业政策、地区发展政策、法律法规等因素引起的政策风险；&nbsp;
</p>
<p>
	2.信用风险：当债务人发生资金状况的风险，或者债务人的还款意愿发生变化时，乙方的出借资金可能无法按时收回；&nbsp;
</p>
<p>
	3.不可抗力：由于战争、动乱、自然灾害等不可抗力因素的出现而可能导致甲方资产损失的风险。
</p>
<p>
	第八条&nbsp;税务处理&nbsp;
</p>
<p>
	乙方因购买转让债权而获得的收益（包括但不限于利息和罚息等）应纳税款由乙方自行申报及缴纳。
</p>
<p>
	第九条&nbsp;&nbsp;关于提前还款
</p>
<p>
	乙方同意标的债权中的借款人提前还款，利息以实际用款天数为准。
</p>
<p>
	第十条&nbsp;争议处理&nbsp;
</p>
<p>
	在本协议的履行过程中，如果发生争议，由各方协商处理。若各方协商不能达成一致，向丙方所在地北京市海淀区人民法院提起诉讼。&nbsp;
</p>
<p>
	第十一条&nbsp;其他条款&nbsp;
</p>
<p>
	本协议自&nbsp;{{ $signDay or null }}&nbsp;起生效，一式三份，甲、乙、丙各保留一份。&nbsp;
</p>
<div style="background-image: url('{{ assetUrlByCdn('static/img/seal_xingguo.png') }}'); background-repeat: no-repeat; height: 170px; background-position: 50px 0px;">

<p>
	甲方（债权转让人）：&nbsp;{{ $assignUser['real_name'] or null }}<span></span>
</p>
<p>
	乙方（债权受让人）：&nbsp;{{ $loginUser['real_name'] or null }}
</p>
<p>
	丙方：星果时代信息技术有限公司
</p>
<p>
	日&nbsp;&nbsp;期：&nbsp;{{ $signDay or null }}
</p>
</div>