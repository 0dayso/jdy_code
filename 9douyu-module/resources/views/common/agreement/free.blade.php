<?php
$loginUser = isset($data['loginUser']) ? $data['loginUser'] : null;
$project   = isset($data['project']) ? $data['project'] : null;
$credit	   = isset($data['credit'][0]) ? $data['credit'][0] : null;
$credit	   = $credit == null && isset($data['credit']) ? $data['credit'] : $credit;
$signDay   = isset($data['signDay']) ? $data['signDay'] : null;
$invested  = isset($data['invested']) ? $data['invested'] : null;
$investing = isset($data['investing']) ? $data['investing'] : null;
$refundTime= isset($data['refundTime']) ? $data['refundTime'] : null;
$refundPlan= isset($data['refundPlan']) ? $data['refundPlan'] : null;
?>

<div style="position:absolute;top:5%;right:5%;z-index:1;background-image: url('{{ env('APP_URL_PC') }}/static/img/seal_ebq.png'); background-repeat: no-repeat; height: 150px;width:150px; background-position: center center;background-size: cover;"></div>
<h2 style="text-align: center;">
	<span>九斗鱼九省心投资协议</span>
</h2>
<p style="text-align: left;  background: white;">
	<span>九斗鱼合同编号：{{ $credit['contract_no'] or null }}</span>
</p>
<p style="text-align: left;  background: white;">
	<span>九斗鱼九省心投资协议（以下简称“本协议”）由以下双方于{{ $signDay }}签订：</span>
</p>
<p>
	<span> </span>
</p>
<p style="text-align: left;  background: white;">
	<span>甲方（平台服务方）:星果时代信息技术有限公司</span>
</p>
<p style="text-align: left;  background: white;">
	<span>注：甲方具有提供互联网信息服务的资质并拥有九斗鱼（www.9douyu.com）的经营权。</span>
</p>
<p>
	<span> </span>
</p>
<p style="text-align: left; background: white;">
	<span>乙方（投资者）：{{ $loginUser['real_name'] or null}}</span>
</p>
<p style="text-align: left; background: white;">
	<span>身份证号：{{ $loginUser['identity_card'] or null}}</span>
</p>
<p>
	<span> </span>
</p>
<p>
	<span>甲乙双方经友好协商，本着平等自愿、诚实信用的原则，就九斗鱼提供的“九省心”出借计划的相关事项订立有效合约，达成如下协议：</span>
</p>
<p>
	<span>释义：</span>
</p>
<p>
	<span>除非本协议另有规定，以下词语在本协议中定义如下：</span>
</p>
<p>
	<span>a. 九斗鱼：指由甲方运营和管理的网站，域名为：www.9douyu.com。</span>
</p>
<p>
	<span>b. 出借人（乙方）：指通过甲方九斗鱼成功注册账户的会员，可参考甲方的推荐自主选择出借一定金额的资金给借款客户，且具有完全民事权利/行为能力的自然人。</span>
</p>
<p>
	<span>c. 合作机构：指与甲方建立合作关系的机构，包括但不限融资性担保公司、第三方支付机构等。  </span>
</p>
<p>
	<span>d. 借款客户：指有一定的资金需求，经过甲方或甲方的合作机构筛选、推荐并且得到甲方合作机构（包括小额贷款公司或融资性担保公司）全额本息安全后，在甲方九斗鱼注册账户，由甲方推荐给出借人并得到出借人资金，且具有完全民事权利/行为能力的自然人。</span>
</p>
<p>
	<span>e. 原始借款人：指自主选择出借一定金额的资金给借款客户，且具有完全民事权利/行为能力的自然人。</span>
</p>
<p>
	<span>f. 九斗鱼账户：指出借人或借款客户以自身名义在九斗鱼注册后系统自动产生的虚拟账户，通过第三方支付机构及/或其他通道进行充值或提现。</span>
</p>
<p>
	<span>g. 《原始借款合同》：指由原始借款人放款产生的借款合同。</span>
</p>
<p>
	<span>h. 《债权转让协议》：指注册用户通过九斗鱼平台购买原始债权产生的债权转让协议。</span>
</p>
<p>
	<span>i. 担保：指合作机构为出借人的借款提供的全额本息安全方式，包括但不限于以保证、抵押、质押等方式提供担保，或承诺进行代偿、债权回购或发放后备贷款等方式。</span>
</p>
<p>
	<span>一、主要内容</span>
</p>
<p>
	<span>1.1 九省心：九省心是九斗鱼推出的优质的债权进行自动分散投资及投资期限届满时自动转让债权的出借计划。乙方出借的本金及利息收益按照下述具体出借计划约定的还款方式按时返回其账户。</span>
</p>
<p>
	<span>1.2 乙方知悉、了解并同意，本协议项下涉及的任何收益均为预期收益，甲方未以任何方式对本金及预期收益进行承诺或担保，乙方出借本金存在不能够按期收回的风险，同时，在实际收益未达到预期收益的情况下，乙方仅能收取其期初加入本金数额所对应的实际收益，在前述前提下，乙方同意通过九斗鱼平台加入甲方提供的本期九省心出借计划，具体如下：</span>
</p>

<table height="64" width="653" border="1">
	<tbody>
		<tr>
			<td colspan="4" align="center">
				投资基本信息【九省心 {{ $project["invest_time_note"] or null }} {{ $project["id"] or null }}】
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">项目总额</td>
			<td colspan="3" align="center">人民币：<?php echo isset($project['total_amount']) ? \App\Tools\ToolMoney::moneyFormat($project['total_amount']) : null; ?></td>
		</tr>
        <tr>
			<td align="center">预期收益率</td>
			<td align="center"><?php echo isset($project['profit_percentage']) ? App\Tools\ToolMoney::moneyFormat($project['profit_percentage']) : null; ?>%</td>
            <td align="center">借款期限</td>
			<td align="center">{{ $project["invest_time_note"] or null }}</td>
		</tr>
        <tr>
			<td align="center">还款方式</td>
			<td align="center">{{ $project["refund_type_note"] or null }}</td>
            <td align="center">借款类型</td>
			<td align="center">债权组合</td>
		</tr>
		<tr>
			<td colspan="2" align="center">担保方式</td>
			<td colspan="3" align="center">本息安全</td>
		</tr>
	</tbody>
</table>
<p>
	<span> </span>
</p>
<table height="64" width="653" border="1">
	<tbody>
		<tr>
			<td align="center">期初加入本金数额</td>
			<td align="center">投资利息</td>
            <td align="center">投资日期</td>
			<td align="center">到期日期</td>
			<td align="center">本金及预期收益</td>
		</tr>

		@if ( !empty($invested) )
			<tr>
				<td align="center">{{ $invested["cash"] or null }}元</td>
				<td align="center"><?php
					if(isset($refundPlan['total']) && isset($invested["cash"])){
						//echo App\Tools\ToolMoney::moneyFormat(round(($refundPlan['total']-$invested['cash']), 2));
						echo App\Tools\ToolMoney::moneyFormat(round(($refundPlan['interestTotal']), 2));
					}
					?></td>
				<td align="center"><?php
					if(isset($invested["created_at"])){
						echo date('Y-m-d', strtotime($invested["created_at"]));
					}
					?></td>
				<td align="center">
					<?php
					if(isset($refundPlan['plan']) && is_array($refundPlan['plan'])) {
						$endPlan = end($refundPlan['plan']);
						if(isset($endPlan['times']))
							echo $endPlan['times'];
					}
					?></td>
                <!--<td align="center"><?php //echo isset($refundPlan['total']) ? App\Tools\ToolMoney::moneyFormat($refundPlan['total']) : null ?>元</td>-->
				<td align="center"><?php echo (isset($refundPlan['interestTotal']) && isset($invested['cash']))? App\Tools\ToolMoney::moneyFormat($refundPlan['interestTotal']+$invested['cash']) : null ?>元</td>
			</tr>
        @endif

	</tbody>
</table>

<p>
	<span>1.3 购买债权资金来源保证：乙方保证其所用于购买债权的资金来源合法，乙方是该资金的合法所有人，如果第三方对资金归属、合法性问题提出异议，由乙方自行解决。如未能解决，则乙方承诺放弃享有其所出购买债权资金带来的利息等收益。</span>
</p>

<p>
	<span>二、本协议的成立</span>
</p>
<p>
	<span>2.1 本协议成立：乙方应认真阅读《九斗鱼九省心投资协议》相关内容，并按照九斗鱼的规则，通过在九斗鱼上勾选“我同意《九斗鱼九省心投资协议》”以及点击“立即出借”按钮确认后，即视为乙方与甲方已达成协议并同意接受本协议的全部约定以及与九斗鱼网站所包含的其他与本协议有关的各项规则的规定。</span>
</p>
<p>
	<span>2.2 投资资金冻结：乙方点击“同意并确定投资”按钮确认后，即视为乙方已经向甲方发出不可撤销的授权指令，授权甲方委托相应的第三方支付机构及甲方开立监管账户的监管银行（“监管银行”）等合作机构，在监管账户中乙方九斗鱼用户名项下的虚拟账户（“乙方九斗鱼账户”）中，冻结金额等同于本协议第1.2条所列的“期初加入本金数额”的资金。上述冻结在本协议生效时或本协议确定失效时解除。</span>
</p>
<p>
	<span>2.3 资金划转：</span>
</p>
<p>
	<span>2.3.1 本期九省心所对应的加入资金全部冻结，且甲方系统完成所有资金的自动投标后，根据本期九省心所投不特定项目的《九斗鱼债权转让协议》的相关约定，上述不特定项目的原始借款人即不可撤销地授权甲方委托相应的第三方支付机构及监管银行等合作机构，将金额等同于本协议1.2条所列的“期初加入本金数额”的资金，逐笔按照《九斗鱼债权转让协议》第1条所列的“借款本金数额”，由监管账户中乙方九斗鱼账户下划转至监管账户中相应的原始债权人的九斗鱼账户，再由甲方系统划转至原始借款人的个人银行账户，划转完毕即视为债权转让成功。</span>
</p>
<p>
	<span>2.3.2 甲方将在任意一期九省心所对应的加入资金全部冻结后的2个工作日内完成当期九省心资金的统一出借及划转。如2个工作日未完成上述操作，则甲方将乙方冻结资金退回乙方在九斗鱼的账户，资金冻结期间不计收益。</span>
</p>
<p>
	<span>2.4 本协议生效：本协议于甲方完成本期九省心所对应的全部资金划转之日（“生效日”）立即生效，收益及相关费用自生效日开始计算。</span>
</p>
<p>
	<span>2.5 在九省心存续期限内，除本协议双方协商一致或本协议另有约定外，本协议项下的期限、收益分配方式、每月还款日期等均不得变更。</span>
</p>
<p>
	<span>三、投资管理</span>
</p>
<p>
	<span>3.1 乙方全权委托甲方按照本协议的约定，对等同于本协议1.2条所列的“期初加入本金”进行自动分散投资，作为出借资金出借给九斗鱼平台上经甲方及合作机构推荐、并且得到合作机构（包括但不限于小额贷款公司或融资性担保公司等）全额本息安全的借款客户；同时，乙方授权甲方在完成上述自动优先投资后以乙方名义代为签署相应的《九斗鱼债权转让协议》。</span>
</p>
<p>
	<span>3.2 投资范围：九斗鱼平台上的经甲方及合作机构推荐、并且得到合作机构（包括但不限于小额贷款公司或融资性担保公司等）全额本息安全的原始债权。</span>
</p>
<p>
	<span>3.2 投资范围：九斗鱼平台上的经甲方及合作机构推荐、并且得到合作机构（包括但不限于小额贷款公司或融资性担保公司等）全额本息安全的原始债权。</span>
</p>
<p>
	<span>3.3 乙方加入九省心后，甲方将按照乙方加入时间的先后顺序，对乙方加入九省心的一定金额的资金进行自动分散投资。先加入九省心的资金，在同期九省心所对应的全部资金内具有自动优先投资和到期赎回的更高优先级。</span>
</p>
<p>
	<span>3.4 乙方加入九省心后，视为乙方通过甲方平台购买“期初加入本金数额”的本期九省心所含原始债权。（投资者购买了投资数额的原始债权）</span>
</p>
<p>
	<span>3.5 后续管理：乙方全权委托甲方对本期九省心所购买债权的后续回款进行如下处理并以乙方名义代为签署相应的《九斗鱼债权转让协议》：</span>
</p>
<p>
	<span>3.5.1 在本期九省心的存续期限内，甲方将把本期九省心所投资项目返还的本金（包括但不限于等额本息还款所还本金、提前还款所还本金等）自动优先投资到新的项目中，投资范围参见本协议第3.2条。</span>
</p>
<p>
	<span>3.5.2 在本期九省心的存续期限内，对于九省心所投资项目每月返还的利息收益，甲方将根据乙方选择的本期九省心的收益分配方式（参见本协议第4.3条），决定对其的管理方式。</span>
</p>
<p>
	<span>3.6 在本期九省心期限届满后，乙方全权委托甲方将乙方所持有未到期的债权代为进行债权转让并以乙方名义代为签署相应的《九斗鱼债权转让协议》。</span>
</p>
<p>
	<span>四、收益及费用</span>
</p>
<p>
	<span>4.1 收益来源：乙方委托甲方通过自动分散投资将自有资金购买由九斗鱼平台提供的原始债权后，借款客户每月的利息还款在扣除管理费用和/或提前赎回费用后的剩余部分将作为九省心的收益支付给乙方（若借款客户每月的利息还款不足以扣除提前赎回费用的，则在本金中予以扣除）。</span>
</p>
<p>
	<span>4.2 收益起算时间：自本协议生效日开始计算收益。</span>
</p>
<p>
	<span>4.3 收益分配方式：乙方在加入本期九省心后，按本协议1.2条所述出借计划约定方式偿还本金及收益。</span>
</p>
<p>
	<span>4.4 九省心费用种类：九省心费用包括管理费用以及提前赎回费用。</span>
</p>
<p>
	<span>4.5 管理费用来源：本协议项下管理费用来源于乙方购买的九省心所对应的借款的利息中超过九省心借款利率以外的部分（本期九省心借款利率请参见本协议第1.2条）。九省心实际收益不及借款利率的，甲方不收取管理费用。</span>
</p>
<p>
	<span>4.6 费用收取方式：甲方按本协议1.2条出借计划向乙方返还（每月）收益时或进行收益复投时，甲方自动扣除当期管理费用，管理费用按月计算并收取。</span>
</p>
<p>
	<span>五、其他</span>
</p>
<p>
	<span>5.1 本协议的任何修改、补充均须以九斗鱼平台电子文本形式作出。</span>
</p>
<p>
	<span>5.2 本期九省心项目不支持在投资期限内提前退出，亦不产生提前赎回费用。</span>
</p>
<p>
	<span>5.3 双方均确认，本协议的签订、生效和履行以不违反法律为前提。如果本协议中的任何一条或多条被司法部门认定为违反所须适用的法律，则该条将被视为无效，但该无效条款并不影响本协议其他条款的效力。</span>
</p>
<p>
	<span>5.4如双方在本协议履行过程中发生任何争议，应友好协商解决；如协商不成，则须提交甲方所在地有管辖权的人民法院诉讼解决。</span>
</p>
<div style="background-image: url('{{ assetUrlByCdn('static/img/seal_xingguo.png') }}'); background-repeat: no-repeat; height: 170px; background-position: 50px 0px;">

<p>
	<span>甲方（平台服务方）：星果时代信息技术有限公司</span>
</p>
<p>
	<span>{{ $signDay }}</span>
</p>
</div>
