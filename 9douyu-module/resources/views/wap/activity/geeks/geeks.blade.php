@extends('wap.common.wapBase')

@section('title', '2016中国极客大奖年度评选')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
<meta name="format-detection" content="telephone=yes">
<link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/weixin/activity/geeks/css/geeks.css') }}">

@endsection

@section('content')
<input type="hidden" name="isReceived" id="isReceived" value="{{ $isReceived }}">
<input type="hidden" name="repeatHit"  id="repeatHit"  value="{{ $repeatHit }}">
<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/g-title.png') }}" class="img g-title">
<div class="g-bg">
	<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/text.png') }}" class="img g-text">
	<p>他们崇尚科技、自由、与创新；</p>
	<p>他们勇于创造，敢为天下先，不受创同商业模式羁绊；</p>
	<p>他们洞察人性，却不失本色，</p>
	<p>他们历尽坎坷，却不言放弃；</p>
	<p>他们不懂投机取巧，只会乘风破浪，砥砺前行；</p>
	<p>无论过去，还是现在，他们都是中国梦的最佳编织者；</p>
	<p>他们身上有着共同的标签：<span> 极客！</span></p>
</div>

<div style=" margin:0.4rem 1rem 0;">
<div class="swiper-container">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <a href="#"> <img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/g-img.jpg') }}" class="img g-img"></a>
            <h4>华为技术有限公司总裁 <span>任正非</span></h4>
            <p class="g-y">Geek语录：</p>
            <p>企业发展就是要发展一批狼。</p>
            <p>狼有三大特性：一是敏锐的嗅觉；二是不屈不挠、</p>
            <p>奋不顾身的进攻精神；三是群体奋斗的意识。</p>
        </div>
        <div class="swiper-slide">
            <a href="#"> <img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/g-img1.jpg') }}" class="img g-img"></a>
            <h4>京东集团首席执行官 <span>刘强东</span></h4>
            <p class="g-y">Geek语录：</p>
            <p>世界虽然残酷，但只要你愿意走，总会有路。</p>
        </div>
        <div class="swiper-slide">
            <a href="#"> <img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/g-img2.jpg') }}" class="img g-img"></a>
            <h4>58同城首席执行官 <span>姚劲波</span></h4>
            <p class="g-y">Geek语录：</p>
            <p>成功比你想象的慢，但规模可能比想象的大。</p>
        </div>
        <div class="swiper-slide">
            <a href="#"> <img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/g-img3.jpg') }}" class="img g-img"></a>
            <h4>耀盛中国金融科技事业群总裁 <span>郭鹏</span></h4>
            <p class="g-y">Geek语录：</p>
            <p>一生做好一事，始终敬畏风险，始终心存用户。</p>
        </div>
        <div class="swiper-slide">
            <a href="#"> <img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/g-img4.jpg') }}" class="img g-img"></a>
            <h4>大街网创始人 <span>王秀娟</span></h4>
            <p class="g-y">Geek语录：</p>
            <p>把颠覆做到极致。</p>
        </div>
    </div>
    <!-- Add Pagination -->
    <div class="swiper-pagination"></div>
</div>
</div>

<div class="g-line"><img src=" {{ assetUrlByCdn('/static/weixin/activity/geeks/images/line.png') }} " class="img g-line1"></div>

<div class="g-mr">
	<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/text2.png') }}" class="img g-text2">
	<p>变局已然成为了我们这个时代的新常态，</p>
	<p>蓬勃发展的技术代表着时代前进的脚步，</p>
	<p>拥有极客精神的创新型企业才是未来的践行</p>
	<p>科学发展观的明星企业。</p>
	<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/text3.png') }}" class="img g-text3">
	<p class="g-1">系列奖项从IT网络通信、新电商&互联网、手机&智能硬件、</p>
	<p class="g-2">新能源汽车、人物这五个维度进行展开，共计50多个奖项。</p>
	<dl>
		<dt>
			<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/g-icon.png') }}" class="img">
		</dt>
		<dd>
			<p>人物类</p>
			<p>年度人物</p>
			<p>创客先锋</p>
		</dd>
	</dl>
	<dl>
		<dt>
			<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/g-icon1.png') }}" class="img">
		</dt>
		<dd>
			<p>品牌类</p>
			<p>最具影响力品牌</p>
			<p>最佳供应商</p>
			<p>最具成长潜力品牌</p>
		</dd>
	</dl>
	<dl>
		<dt>
			<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/g-icon2.png') }}" class="img">
		</dt>
		<dd>
			<p>产品类</p>
			<p>年度最佳产品</p>
			<p>年度最受欢迎产品</p>
			<p>最具竞争力产品</p>
		</dd>
	</dl>

	<div class="g-box">
		<p>覆盖领域</p>
		<p>IT网络通信、新电商&互联网、手机&智能硬件、</p>
		<p>汽车科技&新能源汽车、金融科技(FinTech)</p>
	</div>
	<div class="g-line"><img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/line.png') }}" class="img g-line1"></div>
	<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/text4.png') }}" class="img g-text4">
	<dl class="g-instro">
		<dt>
			<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/img.jpg') }}" class="img">
		</dt>
		<dd>
			<p>刘强东</p>
			<p>以极客精神打造智能生态链条</p>
		</dd>
	</dl>
	<dl class="g-instro">
		<dt>
			<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/img1.jpg') }}" class="img">
		</dt>
		<dd>
			<p>郭鹏</p>
			<p>立足金融科技 打破产品隔阂 搭建小微金融生态圈</p>
		</dd>
	</dl>
	<dl class="g-instro">
		<dt>
			<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/img2.jpg') }}" class="img">
		</dt>
		<dd>
			<p>王秀娟</p>
			<p>极尽颠覆 偏执创新</p>
		</dd>
	</dl>
	<dl class="g-instro">
		<dt>
			<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/img3.jpg') }}" class="img">
		</dt>
		<dd>
			<p>李凌霄</p>
			<p>将服装智能化 突破社交局限拓展未来智能发展方向</p>
		</dd>
	</dl>
	<dl class="g-instro">
		<dt>
			<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/img4.jpg') }}" class="img">
		</dt>
		<dd>
			<p>马旭</p>
			<p>互联网+不应该只有“颠覆”还要有“帮助”</p>
		</dd>
	</dl>
	<dl class="g-instro">
		<dt>
			<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/img5.jpg') }}" class="img">
		</dt>
		<dd>
			<p>郭伟</p>
			<p>互联网人力资源平台缔造者 打造社保无死角时代</p>
		</dd>
	</dl>
	<div class="g-line"><img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/line.png') }}" class="img g-line1"></div>
	<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/g-title1.png') }}" class="img" style="width: 5.275rem; margin:0.9rem auto 0;">
	<div class="g-list">
		<h6>获奖人物：</h6>
		<p><em></em>2016年度通信产业最具影响力人物 <span>任正非</span></p>
		<p><em></em>2016年度实体+互联网最具影响力人物 <span>王健林</span></p>
		<p><em></em>2016年度新电商领域最具影响力人物 <span>马   云</span></p>
		<p><em></em>2016年度互联网产业最具影响力人物<span>刘强东</span></p>
		<p><em></em>2016年度互联网产业年度风云人物<span>贾跃亭</span></p>
		<p><em></em>2016年度O2O领域最具影响力人物<span>李彦宏</span></p>
		<p><em></em>2016年度通信产业年度风云人物<span>王晓初</span> </p>
		<p><em></em>2016年度IT产业年度风云人物<span>王恩东</span> </p>
		<p style="color: #f1c376;"><em></em>2016年度科技金融创客先锋<span>郭   鹏</span> </p>
		<p><em></em>2016年度终端产业年度风云人物<span>余承东</span> </p>
		<p><em></em>2016年度O2O领域创客先锋<span>吴   玮</span> </p>
		<p><em></em>2016年度区块链领域创客先锋<span>陈   刚</span> </p>
		<p><em></em>2016年度IT产业最具影响力人物<span>胡厚崑</span> </p>
	</div>

	<div class="g-list">
		<h6>获奖企业：</h6>
		<p><em class="icon"></em>新电商&互联网：</p>
		<p><em></em>2016年度最具影响力新电商品牌	 <span>阿里巴巴</span></p>
		<p><em></em>2016年度互联网产业最具竞争力品牌 <span>京   东</span></p>
		<p style="color:#f1c376;"><em></em>2016年度最具成长潜力科技金融创新品牌 <span>普付宝</span></p>
		<p style="color:#f1c376;"><em></em>2016年度最具成长潜力消费金融创新产品 <span>快   金</span></p>
		<p><em></em>2016年度科技金融最具影响力品牌 <span>蚂蚁金服</span> </p>
		<p><em></em>2016年度科技金融最具竞争力品牌 <span>京东金融</span> </p>
		<p><em></em>2016年度最佳在线人力资源服务平台 <span>金柚网</span> </p>
		<p><em></em>2016年度最具影响力实体+互联网开放平台 <span>飞   凡</span> </p>
		<p><em></em>2016年度最具成长潜力网络银行 <span>I邦银行</span> </p>
	</div>

	<div class="g-list">
		<p><em class="icon1"></em>IT网络通信：</p>
		<p><em></em>2016年度通信产业最具影响力品牌 <span>华   为</span></p>
		<p><em></em>2016年度通信产业最具竞争力品牌 <span>爱立信</span></p>
		<p><em></em>2016年度IT产业最具竞争力品牌 <span>浪   潮</span></p>
		<p><em></em>2016年度最佳数据库安全供应商 <span>安华金和</span></p>
		<p><em></em>2016年度最具影响力云主机供应商 <span>阿里云</span></p>
		<p><em></em>2016年度最佳云主机供应商 <span>天翼云</span></p>
		<p><em></em>2016年度最佳云计算解决方案供应商<span>浪   潮</span></p>
		<p><em></em>2016年度最佳无线解决方案供应商<span>思   科</span></p>
		<p><em></em>2016年度最具影响力办公设备供应商<span>惠   普</span></p>
		<p><em></em>2016年度最佳办公设备供应商<span>联   想</span></p>
		<p><em></em>2016年度最佳移动销售管理解决方案供应商<span>红圈营销</span></p>
		<p><em></em>2016年度最佳验证技术解决方案供应商<span>极验验证</span></p>
	</div>

	<div class="g-list">
		<p><em class="icon2"></em>手机&智能硬件：</p>
		<p><em></em>2016年度中国手机行业最具影响力品牌 <span>OPPO、VIVO</span></p>
		<p><em></em>2016年度中国手机行业最具竞争力品牌 <span>华   为</span></p>
		<p><em></em>2016年度最佳手机设计 <span>三星C9</span></p>
		<p><em></em>2016年度最佳户外手机 <span>云狐手机</span></p>
		<p><em></em>2016年度中国手机行业最佳奋进奖 <span>天语手机</span></p>
		<p><em></em>2016年度最具影响力互联网电视品牌 <span>乐视电视</span></p>
		<p><em></em>2016年度最具竞争力互联网电视品牌 <span>暴风TV</span></p>
		<p><em></em>2016年度最具潜力互联网电视品牌 <span>微   鲸</span></p>
		<p><em></em>2016年度最具影响力游戏品牌 <span>苏州蜗牛</span></p>
		<p><em></em>2016年度最受欢迎运动手表 <span>华米手表</span></p>
	</div>

	<div class="g-list" style="border-bottom: none; padding-bottom: 0.3rem; ">
		<p><em class="icon3"></em>新能源汽车：</p>
		<p><em></em>2016年度最具影响电动汽车品牌 <span>特斯拉</span></p>
		<p><em></em>2016年度最具竞争力电动汽车品牌 <span>比亚迪</span></p>
		<p><em></em>2016年度最受消费者欢迎电动汽车品牌 <span>北汽新能源</span> </p>
		<p><em></em>2016年度最具潜力电动汽车品牌 <span>蔚来汽车</span> </p>
	</div>

	<div class="g-line"><img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/line.png') }}" class="img g-line1"></div>
<!-- 	<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/text6.png') }}" class="img g-prize">
 -->	
  	<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/g-title2.png') }}" class="img g-text5">
  	<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/g-media.jpg') }}" class="img g-media">
  	<p class="g-media1">2016年度科技金融创客先锋	    <span>九斗鱼CEO－郭鹏</span></p>
	<a class="g-media2" href="http://suo.im/1rEaUf">
		<p>2016中国极客大奖揭晓 耀盛中国郭鹏获选</p>
		<p>“科技金融创客先锋”</p>
  		<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/arrow.png') }}" class="img g-arrow">
	</a>
	<a class="g-media2" href="http://district.ce.cn/newarea/qyzx/201701/18/t20170118_19695452.shtml">
		<p>普付宝荣膺</p>
		<p>“2016最具成长潜力科技金融创新品牌”</p>
  		<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/arrow.png') }}" class="img g-arrow">
	</a>
	<a class="g-media2" href="http://t.m.china.com.cn/convert/c_mAELXx.html">
		<p>快金TimeCash喜获</p>
		<p>“2016最具成长潜力消费金融创新产品”大奖 </p>
  		<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/arrow.png') }}" class="img g-arrow">
	</a>
	<a class="g-media2" href="http://www.fromgeek.com/news/70859.html">
		<p>2016极客大奖颁奖盛典落幕 </p>
		<p>50个大奖勾勒中国创新图谱 </p>
  		<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/arrow.png') }}" class="img g-arrow">
	</a>
	<a class="g-media2" href="http://www.fromgeek.com/awards/news/70883.html">
		<p>2016年度人物揭晓：任正非王健林最具影响， </p>
		<p>贾跃亭当选风云人物 </p>
  		<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/arrow.png') }}" class="img g-arrow">
	</a>
 	<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/logo.png') }}" class="img g-logo">
	<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/text7.png') }}" class="img g-text7">
</div>
<a href="#" id="receiveBtn" class="g-btn"><img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/btn.png') }}" class="img"></a>

<div class="pop-layer">
    <div class="pop-mask"></div>

	<div class="pop">
        <div class="pop-box tips1" style="display: none">
        <!-- 参与过的 -->
    	<h4 class="pop-title">对不起，您已经参与过了</h4>
    	<img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/g-b.png') }}" class="img g-b">
        </div>

		<div class="pop-box tips2" style="display: none">
			<!-- 第一次参与的 -->
			<h4 class="pop-title1">感谢您的参与</h4>
            <p>红包已发送到您的九斗鱼账户</p>
            <img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/g-b1.png') }}" class="img g-b img2">

		</div>

        <a href="#" class="g-close"><img src="{{ assetUrlByCdn('/static/weixin/activity/geeks/images/g-close.png') }}" class="img"></a>
    </div>

</div>
@endsection

@section('footer')

@endsection

@section('jsScript')
<script src="{{ assetUrlByCdn('/static/weixin/activity/geeks/js/swiper3.1.0.jquery.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">

	var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        autoplay: 2000,
        loop: true
    });


	$(document).ready(function() {
		$("#receiveBtn").click(function () {
			var userStatus 	= "{{ $userStatus }}"
			var isReceived 	= $("#isReceived").val()
			var repeatHit 	= $("#repeatHit").val()
			var client 	= "{{ $client }}"
			var token 	= "{{ $token }}"

			//重复点击
			if( isReceived == 0 && repeatHit=="opened"){
				$('.pop-title').html("请勿大量重复点击~");
				$(".pop-layer").show();
				$(".tips1").show();
				$(".tips2").hide();
				return false;
			}

			$("#repeatHit").val("opened");

			if (userStatus) {
				if (isReceived == "1") {
					$(".pop-layer").show();
					$(".tips1").show();
					$(".tips2").hide();
					return false;
				} else {
					$.ajax({
						url      :"/activity/geeks/receiveBonus",
						dataType :'json',
						data: { from:'app',token: token,client:client,_token:'{{csrf_token()}}'},
						type     :'get',
						success : function(json){
							if( json.status==true || json.code==200){
								$(".pop-layer").show();
								$(".tips1").hide();
								$(".tips2").show();
								$("#isReceived").val(1);
							} else if( json.status == false || json.code ==500 ){
								$('.pop-title').html(json.msg);
								$(".pop-layer").show();
								$(".tips1").show();
								$(".tips2").hide();
								$("#repeatHit").val("closed");
							}
							return false;
						},
						error : function(msg) {
							$('.pop-title').html("抱歉，网络出错了");
							$(".pop-layer").show();
							$(".tips1").show();
							$(".tips2").hide();
							$("#repeatHit").val("closed");
							return false;
						}
					})

				}
			} else {
				$("#repeatHit").val("closed");
				if (client == 'ios') {
					window.location.href = "objc:gotoLogin";
					;
					return false;
				}
				if (client == 'android') {
					window.jiudouyu.login()
					return false;
				}
				window.location.href = '/login';
			}
		})

		$(".g-close").click(function(){
			$(".pop-layer").hide();
		});

	});


</script>
@endsection