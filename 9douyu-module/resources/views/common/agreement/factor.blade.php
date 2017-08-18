<?php
$loginUser = isset($data['loginUser']) ? $data['loginUser'] : null;
$project   = isset($data['project']) ? $data['project'] : null;
$credit	   = isset($data['credit'][0]) ? $data['credit'][0] : null;
$credit	   = $credit == null && isset($data['credit']) ? $data['credit'] : $credit;
$signDay   = isset($data['signDay']) ? $data['signDay'] : null;
$invested  = isset($data['invested']) ? $data['invested'] : null;
$investing = isset($data['investing']) ? $data['investing'] : null;
$refundTime= isset($data['refundTime']) ? $data['refundTime'] : null;

?>
<div style="position:absolute;top:5%;right:5%;z-index:1;background-image: url('{{ env('APP_URL_PC') }}/static/img/seal_ebq.png'); background-repeat: no-repeat; height: 150px;width:150px; background-position: center center;background-size: cover;"></div>
<p>
	<span><br /></span>
</p>
<h2 style="text-align: center;">
	<span>应收账款购买服务协议</span>
</h2>
<p>
	<span><br /></span>
</p>
<p style="text-align: left;  background: white;">
	<span>甲方：{{ $loginUser['real_name'] or null}}</span>
</p>
<p style="text-align: left; background: white;">
	<span>九斗鱼用户名： {{ $loginUser['phone'] or null}}</span>
</p>
<p style="text-align: left; background: white;">
	<span>身份证号：{{ $loginUser['identity_card'] or null}}</span>
</p>
<p style="text-align: left; background: white;">
	<span>电子邮件地址：{{ $loginUser['user_info']['email'] or null }}</span>
</p>
<p style="text-align: left; background: white;">
	<span>联系电话：{{ $loginUser['phone'] or null}}</span>
</p>
<p>
	<span> </span>
</p>
<p style="text-align: left; background: white;">
	<span>乙方：星果时代信息技术有限公司 </span>
</p>
<p style="text-align: left; background: white;">
	<span>注册地址：北京市朝阳区郎家园6号
郎园vintage 2号楼A座2层</span>
</p>
<p style="text-align: left; background: white;">
	<span>电子邮件地址：<span><a href="mailto:customer@9douyu.com" target="_blank"><span style="color: #1E5494;">customer@9douyu.com</span></a></span></span>
</p>
<p>
	<span> </span>
</p>
<p>
	<span>根据《中华人民共和国合同法》及相关法律法规规定，双方遵循平等、自愿和诚实信用原则，经友好协商，就乙方为甲方提供经济信息咨询服务、市场调查服务等内容达成一致，以兹共同遵守。</span>
</p>
<p>
	<span>第一条 释义</span>
</p>
<p>
	<span>在本合同中，除非上下文另有解释，下列词语具有以下含义：</span>
</p>
<p>
	<span>1.1 受让人/甲方：指通过乙方的互联网平台www.9douyu.com（以下简称平台）向保理公司自主选择购买一定数量应收账款的合格出借人；</span>
</p>
<p>
	<span>1.2  转让人/保理公司：耀盛商业保理有限公司；</span>
</p>
<p>
	<span>1.3 原债权人：基于基础交易而取得债权的独立法人；</span>
</p>
<p>
	<span>1.4 原债务人：基于接受基础交易中的服务或货物而应当支付对价的独立法人；</span>
</p>
<p>
	<span>1.5 应收账款：指原债权人和原债务人基于基础交易产生的应收账款；</span>
</p>
<p>
	<span>1.6 工作日：指中华人民共和国法律规定的工作日。</span>
</p>
<p>
	<span>第二条 甲方权利与义务</span>
</p>
<p>
	<span>2.1 甲方有利用乙方提供的平台自由选择向保理公司购买应收账款的权利；</span>
</p>
<p>
	<span>2.2 甲方有权按照约定期限要求保理公司回购转让的应收账款；</span>
</p>
<p>
	<span>2.3 甲方有义务按照乙方要求向乙方提供真实的个人信息，因甲方提供虚假信息而造成的一切法律后果（包括但不限于民事赔偿，行政处罚等）均由甲方承担；</span>
</p>
<p>
	<span>2.4 甲方有义务按照乙方平台要求操作以及查收乙方发出的所有消息（包括但不限于电子邮件、手机短信等）。因甲方个人操作不当以及疏于查收信息造成的损失由甲方自行承担。</span>
</p>
<p>
	<span>第三条 乙方的权利和义务</span>
</p>
<p>
	<span>3.1 乙方应当按照本协议的规定，恪尽职守，以诚实、信用、谨慎、有效管理的原则为甲方进行服务；</span>
</p>
<p>
	<span>3.2 乙方须对甲方个人信息，资产情况及其他服务相关事务的情况和资料依法保密；</span>
</p>
<p>
	<span>3.3 乙方有义务对保理公司的资质以及应收账款的情况进行形式审查，保证在平台上注册的保理公司以及应收账款真实存在并签署相关协议。</span>
</p>
<p>
	<span>第四条 追偿服务</span>
</p>
<p>
	<span>如保理公司在回购期限到期后不履行回购义务，乙方应当协助甲方向保理公司或原债权人及原债务人追偿，保证甲方的合法权益得以顺利实现。</span>
</p>
<p>
	<span>第五条 风险提示</span>
</p>
<p>
	<span>5.1 政策风险：因国家宏观政策、财政政策、货币政策、行业政策、法律法规等因素而引起的政策风险；</span>
</p>
<p>
	<span>5.2 信用风险：当原债务人发生经营状况的风险，或者原债务人的还款意愿发生变化时，甲方的资金可能无法按时收回；</span>
</p>
<p>
	<span>5.3 不可抗力：由于战争、动乱、自然灾害等不可抗力因素的出现而可能导致甲方资产损失的风险 。</span>
</p>
<p>
	<span>第六条 税务处理</span>
</p>
<p>
	<span>甲方在获得收益过程中产生的相关税费，由甲方自行向其主管税务机关申报、缴纳，乙方不负责相关事宜处理。</span>
</p>
<p>
	<span>第七条 违约责任</span>
</p>
<p>
	<span>任何一方违反本协议的约定，使得本协议的全部或部分不能履行，均应承担违约责任，并赔偿对方因此遭受的损失（包括由此产生的诉讼费和律师费等相关费用）；如多方违约，根据实际情况各自承担相应的责任。违约方应当赔偿因其违约而给守约方造成的损失，包括合同履行后可以获得的利益，但不得超过违反合同一方订立合同时可以预见或应当预见的因违反合同可能造成的损失。</span>
</p>
<p>
	<span>第八条 争议的处理</span>
</p>
<p>
	<span>本协议双方如有争议应当协商解决，协商不成的均可向合同签订地北京市海淀区人民法院提起诉讼。</span>
</p>
<p>
	<span>第九条 其他事项</span>
</p>
<p>
	<span>9.1 如果甲方出现转让资金的继承或赠与，必须由主张权利的继承人或受赠人向乙方出示经国家权威机关认证的继承或赠与权利归属证明文件，乙方确认后方予协助进行资产的转移，由此产生的相关税费，由主张权利的继承人或受赠人，向其主管税务机关申报、缴纳，乙方不负责相关事宜处理；</span>
</p>
<p>
	<span>9.2 本协议的传真件、复印件、扫描件、电子文档、电子邮件等有效副本的效力与本协议原件效力一致；</span>
</p>
<p>
	<span>9.3 本协议自签署之日起生效，如果甲方需要对本协议版本进行修订，甲方有义务告知乙方后并经乙方同意后签署新版协议；</span>
</p>
<p>
	<span>9.4 双方确认，本协议的签署、生效和履行以不违反中国的法律法规为前提。如果本协议中的任何一条或多条违反适用的法律法规，则该条将被视为无效，但该无效条款并不影响本协议其他条款的效力；</span>
</p>
<p>
	<span>9.5 本协议一式两份，甲、乙双方各保留一份。</span>
</p>
<p>
	<span><br /></span>
</p>
<div style="background-image: url('{{ assetUrlByCdn('static/img/seal_xingguo.png') }}'); background-repeat: no-repeat; height: 170px; background-position: 50px 0px;">

<p>
	<span>甲方：{{ $loginUser['real_name'] or null}}</span>
</p>
<p>
	<span>乙方：星果时代信息技术有限公司</span>
</p>
<p>
	<span>合同签署日期： {{ $signDay }}</span>
</p>
	</div>
<p>
	<span><br /></span>
</p>
<p>
    <span><br /></span>
</p>
<p>
    <span><br /></span>
</p>
<p>
    <span><br /></span>
</p>
<p>
    <span><br /></span>
</p>
<p>
    <span><br /></span>
</p>
<p>
    <span><br /></span>
</p>
<p>
    <span><br /></span>
</p>
<p>
    <span><br /></span>
</p>
<p>
    <span><br /></span>
</p>
<p>
	<span><br /></span>
</p>
<p>
	<span><br /></span>
</p>
<p>
	<span><br /></span>
</p>
<p>
	<span><br /></span>
</p>
<p>
	<span><br /></span>
</p>
<p>
	<span><br /></span>
</p>
<h2 style="text-align: center;">
	<span>应收账款转让及回购合同</span>
</h2>
<p>
	<span>应收账款转让人：耀盛商业保理（北京）有限公司 （以下简称“甲方”）</span>
</p>
<p>
	<span>营业执照号码：440301108735633</span>
</p>
<p>
	<span></span>
</p>
<p>
	<span>应收账款受让人：{{ $loginUser['real_name'] or null}} （以下简称“乙方”）</span>
</p>
<p>
	<span>证件号码：{{ $loginUser['identity_card'] or null}}</span>
</p>
<p>
	<span></span>
</p>
<p>
	<span>定义：如上下文无其他解释，本文相关定义如下。</span>
</p>
<p>
	<span>原债权人：基于基础交易而取得债权的法人、组织或个人。</span>
</p>
<p>
	<span>原债务人：基于接受基础交易中的服务或货物而应当支付对价的法人、组织或个人。</span>
</p>
<p>
	<span>保理方：在工商管理部门登记的，拥有商业保理资质的独立法人。</span>
</p>
<p>
	<span>应收账款受让人：受让保理公司保理的应收账款的合格出借人。</span>
</p>
<p>
	<span></span>
</p>
<p>
	<span>现经甲乙双方协商一致，甲方将上述受让的应收账款再转让给乙方，为保证本业务的顺利进行，双方约定如下：</span>
</p>
<p>
	<span>第一条 业务简介</span>
</p>
<p>
	<span>本合同业务为应收账款转让及应收账款委托管理业务，即乙方在本合同有效期及延续期间受让甲方的应收账款，并委托甲方代为管理和催收应收账款，本合同约定回购期限到期后由甲方向乙方回购本合同转让的应收账款。</span>
</p>
<p>
	<span>第二条 应收账款转让</span>
</p>
<p>
	<span>甲方同意通过九斗鱼将自身的债权（“标的债权”）转让给乙方，乙方同意受让该等债权：</span>
</p>
<p>
<table height="64" width="653" border="1">
	<tbody>
		<tr>
			<td colspan="6" align="center">债权基本信息</td>
		</tr>
		<tr>
			<td colspan="2" align="center">项目名称</td>
			<td colspan="2" align="center">借款企业名称</td>
			<td colspan="2" align="center">初始借款金额</td>
		</tr>
		<tr>
			<td colspan="2" rowspan="{{ $realLoanUserNum or 1 }}" align="center">{{ $project['name'] or '九安心'}} {{ $project['id'] or null}}</td>
			<td colspan="2" rowspan="{{ $realLoanUserNum or 1}}" align="center">{{ $credit['company_name'] or null}}</td>
			<td colspan="2" rowspan="{{ $realLoanUserNum or 1}}" align="center"><?php echo isset($project['total_amount']) ? \App\Tools\ToolMoney::moneyFormat($project['total_amount']) : null; ?></td>
		</tr>
	</tbody>
</table>
<table class="ke-zeroborder" border="0">
	<tbody>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</tbody>
</table>
<table border="1" width="653">
	<tbody>
		<tr>
			<td colspan="7" align="center">借款人债权收益信息</td>
		</tr>
		<tr>
			<td align="center">项目名称</td>
			<td align="center">初始借款金额</td>
			<td align="center">本次转让债权价值</td>
			<td align="center">需支付对价</td>
			<td align="center">还款日期</td>
			<td align="center">还款期限（{{ $project['invest_time_unit'] or null }}）</td>
			<td align="center">{{  $project['refund_type_note'] or null}}</td>
		</tr>

		@if ( !empty($invested) )
			<tr>
				<td align="center">{{ $project['name'] or '九安心'}} {{ $project['id'] or null}}</td>
				<td align="center"><?php echo isset($project['total_amount']) ? \App\Tools\ToolMoney::moneyFormat($project['total_amount']) : null; ?> </td>
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
				<td align="center">{{ $refundTime or null }}</td>
				<td align="center"><?php echo (isset($project["end_at"]) && isset($invested['created_at'])) ? \App\Tools\ToolTime::getDayDiff($invested['created_at'], $project["end_at"]) : null; ?></td>
				<td align="center"><?php echo isset($project['profit_percentage']) ? App\Tools\ToolMoney::moneyFormat($project['profit_percentage']) : null; ?>%</td>
			</tr>
		@endif
	</tbody>
</table>
</p>
<p>
	<span>第三条 转让资金支付方式</span>
</p>
<p>
	<span>乙方应当通过九斗鱼官方网站（www.9douyu.com）规定的支付方式一次性支付转让款。</span>
</p>
<p>
	<span>第四条 应收账款回购</span>
</p>
<p>
	<span>在应收账款到期日，甲方应当向乙方溢价回购本合同约定的应收账款。甲方依据九斗鱼官方网站（www.9douyu.com）发出的付款通知，将回购资金支付到指定账户即完成回购义务。</span>
</p>
<p>
	<span>第五条 逾期利息</span>
</p>
<p>
	<span>5.1 应收账款到期日，甲方无法足额向乙方按本合同约定溢价回购应收账款的，应当承担逾期利息，逾期利息的支付方式如下：</span>
</p>
<p>
	<span>逾期利息=拖欠款项×逾期天数×逾期利率</span>
</p>
<p>
	<span>5.2 拖欠款项是指截止应收账款到期日，应收账款转让款中甲方应当回购而未回购的部分。逾期天数是指从应收账款到期日（不含当日）起至拖欠款项（包括本金及溢价收益）及逾期利息等清偿完毕之日止（含到账日）的自然日天数：</span>
</p>
<p>
	<span>逾期利率：逾期利率为每日 0.05%</span>
</p>
<p>
	<span>5.3 逾期期间甲方除支付逾期利息外，还应当支付本协议约定的溢价收益，溢价收益按日计算：</span>
</p>
<p>
	<span>溢价收益=拖欠款项×逾期天数÷365×10%</span>
</p>
<p>
	<span>第六条 应收账款管理及催收服务</span>
</p>
<p>
	<span>本合同项下应收账款管理及催收服务是指对于甲方向乙方转让的应收账款，甲方应当尽到管理义务，及时将应收账款在有关部门进行登记并签署相关合同。同时甲方应当尽到催收义务，原债务人所承担的债务到期后应当及时催收。</span>
</p>
<p>
	<span>第七条 应收账款瑕疵担保</span>
</p>
<p>
	<span>甲方保证每一笔应收账款均符合以下全部条件：</span>
</p>
<p>
	<span>7.1 基础合同真实、合法且有效，没有禁止或限制该合同项下的应收账款转让，基础合同中不存在任何不利于乙方行使应收账款项下权利的条款，原债权人已经或将会适当的履行其在基础合同项下的义务；</span>
</p>
<p>
	<span>7.2 保理合同真实、合法且有效，保理合同没有禁止或限制该合同项下应收账款的再次转让，保理合同中不存其他任何不利于乙方行使应收账款项下权利的条款；</span>
</p>
<p>
	<span>7.3 甲方已履行了保理合同项下的保理预付款支付义务和其他已到期的义务；</span>
</p>
<p>
	<span>7.4 每一笔应收账款均不存在任何质押或任何其他担保，也不存在任何权属争议；就已在本合同项下转让给乙方的每一笔应收账款（甲方已回购的应收账款除外）而言，甲方不会将其另行转让给任何其他第三人。</span>
</p>
<p>
	<span>第八条 应收账款代位追偿权</span>
</p>
<p>
	<span>如出现以下情形，乙方可通过九斗鱼官方网站（www.9douyu.com）规定的方式向原债权人和原债务人代位行使追偿权，无需征得甲方同意：</span>
</p>
<p>
	<span>8.1 本合同约定回购期到期后，甲方未能向乙方溢价回购应收账款；</span>
</p>
<p>
	<span>8.2 甲方被人民法院宣布破产；</span>
</p>
<p>
	<span>8.3 原债务人应付账款到期后，甲方怠于行使债权而影响到乙方的合法权利。</span>
</p>
<p>
	<span>第九条 再转让通知</span>
</p>
<p>
	<span>本合同签署后，由甲方通知原债务人应收账款转让事宜。</span>
</p>
<p>
	<span>第十条 回购约定</span>
</p>
<p>
	<span>甲方完成应收账款回购的同时乙方按照九斗鱼官方网站（www.9douyu.com）规定的方式将对原债务人的应收账款债权转回至甲方。</span>
</p>
<p>
	<span>第十一条 合同的生效、终止和变更</span>
</p>
<p>
	<span>11.1 本合同经甲、乙双方签字、盖章后成立并生效；至本合同项下乙方受让的应收账款、回购溢价费用、逾期利息等清偿完毕之日终止；</span>
</p>
<p>
	<span>11.2 本合同的有效性不因个别条款无效而受影响；</span>
</p>
<p>
	<span>11.3 除本合同另有规定外，任何一方未经另一方书面同意，无权单方面更改本合同的任何条款；一方要求对本合同条款进行任何修改，应书面通知另一方，在取得另一方的书面同意后方可进行；</span>
</p>
<p>
	<span>11.4 有关本合同的所有通知、变更等均应采取书面形式。</span>
</p>
<p>
	<span>第十二条 风险提示</span>
</p>
<p>
	<span>12.1 政策风险：因国家宏观政策、财政政策、货币政策、行业政策、地区发展、法律法规等因素引起的政策风险；</span>
</p>
<p>
	<span>12.2 信用风险：如转让人发生资金状况或经营状况的风险，或者转让人的回购意愿发生消极的变化时，乙方可能无法按时获得应收账款项下款项。需经过司法程序以甲方、原债权人及原债务人财产进行清偿后才能收回借款。如各方合法财产清偿完毕仍然不足以偿还乙方损失的，乙方可能无法获得应收账款项下款项；</span>
</p>
<p>
	<span>12.3 不可抗力：由于战争、动乱、自然灾害等不可抗力因素的出现而可能导致乙方无法按时获得应收账款项下款项的风险。</span>
</p>
<p>
	<span>第十三条 税务处理</span>
</p>
<p>
	<span>乙方在转让过程产生的相关税费，由乙方自行向税务机关申报、缴纳，甲方不负责相关事宜处理。</span>
</p>
<p>
	<span>第十四条 保密条款</span>
</p>
<p>
	<span>未经双方同意，任何一方不得将本合同内容泄露给第三方（法律、行政法规、司法解释另有规定的情形除外）。</span>
</p>
<p>
	<span>第十五条 期日定义</span>
</p>
<p>
	<span>本合同中所有涉及的发生日、到期日以及期限日均为自然日。</span>
</p>
<p>
	<span>第十六条 争议解决</span>
</p>
<p>
	<span>与本合同相关的任何争议双方均可向合同签订地北京市海淀区人民法院提起诉讼。</span>
</p>
<p>
	<span>第十七条 适用法律、法规</span>
</p>
<p>
	<span>本合同适用中国的法律。</span>
</p>
<p>
	<span>第十八条 补充约定</span>
</p>
<p>
	<span>乙方可将本协议约定的债权转让给第三人，具体转让程序依据九斗鱼官方网站（www.9douyu.com）规定。本合同一式二份，双方各执一份，具有同等法律效力。</span>
</p>
<p>
	<span>
		<br />
	</span>
</p>
<div style="background-image: url('{{ env('APP_URL_PC') }}/static/img/seal_baoli.png'); background-repeat: no-repeat; height: 170px; background-position: 50px 0px;">

<p>
	<span>甲方 ：耀盛商业保理有限公司</span>
</p>
<p>
	<span>乙方：{{ $loginUser['real_name'] or null }}</span>
</p>
<p>
	<span>合同签署日期： {{ $signDay }}</span>
</p>
	</div>