<extend name="Public@Template:frontHome" />

<block name="main">
<div class="index-1111">
	<div class="banner-1111"></div>
	<div class="wrap hidden">
		<div class="box-1111">
			<div class="left-1111 mr25">
				<dl>
					<dt>单个项目累计投资小于1万(含1万），</dt>
					<dd>下个项目投资可获得最低年化<span class="f20 fontorange fb">0.1%</span>的奖励，最高年化<span class="f20 fontorange fb">0.3%</span>的奖励</dd>
				</dl>
				<div class="line mt10 mb10"></div>
				<dl>
					<dt>单个项目累计投资大于1万，</dt>
					<dd>下个项目投资可获得最低年化<span class="f20 fontorange fb">0.3%</span>的奖励，最高年化<span class="f20 fontorange fb">1%</span>的奖励</dd>
				</dl>
			</div>
			<div class="mt40 mb30 fl"><img src="{:Genstatic::statics(__PUBLIC2__.'/images/topic/1111-img.jpg')}" ></div>
			<div class="clear"></div>
			<div class="icon-1111"></div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<div class="mb25 bc"><img src="{:Genstatic::statics(__PUBLIC2__.'/images/topic/1111-img2.jpg')}" ></div>
		<div class="clear"></div>
		<div class="box-1111">
			<div  class="table-th-1111 bc f14">
				<p style="width:168px;">用户名</p>
				<p style="width:180px;">投资项目</p>
				<p style="width:180px;">投资时间</p>
				<p style="width:170px;">出借金额</p>
				<p style="width:208px;">下个项目投资奖励</p>
			</div>
			<table class="table-1111 bc">
				<tr>
					<td width="168">13****06</td>
					<td width="180">NO.107</td>
					<td width="180">13:06:57</td>
					<td width="170">2,099.99元</td>
					<td width="208">年化 7 ‰</td>
				</tr>
				<tr>
					<td>13****06</td>
					<td>NO.107</td>
					<td>13:06:57</td>
					<td>2,099.99元</td>
					<td>年化 7 ‰</td>
				</tr>
				<tr>
					<td>13****06</td>
					<td>NO.107</td>
					<td>13:06:57</td>
					<td>2,099.99元</td>
					<td>年化 7 ‰</td>
				</tr>
				<tr>
					<td>13****06</td>
					<td>NO.107</td>
					<td>13:06:57</td>
					<td>2,099.99元</td>
					<td>年化 7 ‰</td>
				</tr>
				<tr>
					<td>13****06</td>
					<td>NO.107</td>
					<td>13:06:57</td>
					<td>2,099.99元</td>
					<td>年化 7 ‰</td>
				</tr>
			</table>

			<div class="page fr mr15">
	         <a href="#" class="prev off">上一页</a> <a url="/records/id/113/p/1.html" style="cursor:pointer" onclick="javascript:jQuery(this).records_ajax_page(1)" class="on">1</a> <a url="/records/id/113/p/2.html" style="cursor:pointer" onclick="javascript:jQuery(this).records_ajax_page(2)">2</a><a url="/records/id/113/p/3.html" style="cursor:pointer" onclick="javascript:jQuery(this).records_ajax_page(3)">3</a><a url="/records/id/113/p/4.html" style="cursor:pointer" onclick="javascript:jQuery(this).records_ajax_page(4)">4</a> <a url="/records/id/113/p/5.html" style="cursor:pointer" onclick="javascript:jQuery(this).records_ajax_page(5)" class="off">5</a> <a url="/records/id/113/p/2.html" style="cursor:pointer" onclick="javascript:jQuery(this).records_ajax_page(2)" class="next off">下一页</a>         
	         </div>
	         <div class="clear"></div>

			<div class="icon-1111 icon-1111-2"></div>
			<div class="clear"></div>
		</div>
		<div class="box-1111">
			<ul class="ul-1111">
				<li>
					<p class="num-1111">1</p>
					<p class="txt-1111">活动名称："一投到底"</p>
				</li>
				<li>
					<p class="num-1111">2</p>
					<p class="txt-1111">活动时间：11月1日——11月30日</p>
				</li>
				<li>
					<p class="num-1111">3</p>
					<p class="txt-1111">活动参与者：所有出借人</p>
				</li>
				<li>
					<p class="num-1111">4</p>
					<p class="txt-1111">活动规则： 1）鱼客投资项目成功后，即可获得下个项目投资一定比例的项目奖励；<br />
						<span style="margin-left:96px">具体方式为：1）单个项目累计投资小于等于1万，下个项目投资可随机获得年化<span class="f18 fb">0.1%-0.3%</span>现金奖励；</span><br />
						<span style="margin-left:180px">2）单个项目累计投资大于1万的，下个项目投资可年化<span class="f18 fb">0.3%-1%</span>现金奖励；</span><br />
						<span style="margin-left:76px">2）项目满额后，即刻计算投资奖励，满足条件的立即返还到鱼客账户内；</span><br />
						
						<span style="margin-left:76px">3）本活动最终解释权归九斗鱼所有。</span><br />
				</li>
			</ul>
			<div class="icon-1111 icon-1111-3"></div>
			<div class="mb20"></div>
		</div>
		<div class="mb40 clear"></div>
	</div>

</div>
</block>

<block name="jsPage"> 
<?php 
    $jscssminify->addScript(Genstatic::statics(__PUBLIC2__ . '/js/slide.js'));

    $js = <<<'BLOCK'
(function($){
    $(document).ready(function(){
    	$(".factor1-project").click(function(){
            window.location=$(this).find("a").attr("href"); return false;
        });

    });
})(jQuery)
BLOCK;
    $jscssminify->addScriptDeclaration($js);
?>
</block>
