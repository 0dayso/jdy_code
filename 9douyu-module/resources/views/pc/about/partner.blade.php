@extends('pc.common.layout')
@section('title', $title)
@section('keywords', $keywords)
@section('description', $description)
@section('content')
    @include('pc.about.common.menu')
    <div class="t-wrap t-ys">
        <h4>合作伙伴</h4>
        <h5>PARTNER</h5>
        <div class="t-ys-line"></div>
        <div class="clear"></div>
        <ul class="web-partner-list">
            {{-- <li>
                <div class="web-partner-list-img">
                    <img src="{{assetUrlByCdn('/static/images/new/web-partner-img20.png')}}">
                </div>
                <div class="web-partner-list-main">
                    <h3>东亚银行</h3>
                    <p>
                        东亚银行于1918年在香港成立，是香港最大的独立本地银行，于香港联合交易所上市，为恒生指数成份股之一，成立以来一直致力为香港、中国内地，及世界其他主要市场的客户，提供全面的零售及商业银行服务。
                        目前，东亚银行为内地网络最庞大的外资银行之一，多年来在内地市场声誉卓著，在多方面均屡创先河，为内地客户提供创新和增值的银行服务。
                        在海外地区，东亚银行在东南亚、英国和美国设有据点。目前，集团在全球 ─ 包括香港及大中华其他地区，共设有超过240个网点。
                    </p>
                </div>
            </li> --}}

            <li>
                <div class="web-partner-list-img">
                    <img src="{{assetUrlByCdn('/static/images/new/web-partner-img21.png')}}">
                </div>
                <div class="web-partner-list-main">
                    <h3>普华永道</h3>
                    <p>
                        普华永道会计师事务所（英文：PricewaterhouseCoopers；简称：PwC）是世界上最顶级的会计师事务所之一，向国际及中国的主要公司提供全方位的业务咨询服务，在超过150个国家和地区设有分公司和办事处。
                        全球500强中有32%是其客户，主要国际各户有：埃克森公司、IBM公司、强生公司、戴尔电脑公司、福特汽车公司、雪佛莱公司等。
                        在大中华区域，普华永道拥有最雄厚的实力和最广大的地域覆盖。内地在H股上市的企业中有40%都信赖PWC为其审计。
                    </p>
                </div>
            </li>




            <li>
                <div class="web-partner-list-img">
                    <img src="{{assetUrlByCdn('/static/images/new/web-partner-img3.png')}}">
                </div>
                <div class="web-partner-list-main">
                    <h3>北京大学</h3>
                    <p>
                        北京大学（Peking University），简称北大（PKU），创建于1898年，初名京师大学堂，是中国近代第一所国立大学，也是中国近代最早以“大学”身份和名称建立的学校，其成立标志着中国近代高等教育的开端。北大是中国近代唯一以最高学府身份创立的学校，最初也是国家最高教育行政机关，行使教育部职能，统管全国教育。北大开创了中国高校中最早的文科、理科、政科、商科、农科、医科等学科的大学教育，是近代以来中国高等教育的奠基者。
                        2013年11月22日第三届北京大学经济·投资文化节在北大燕园召开。文化节涵盖了开幕论坛、企业参访、广场展棚、企业宣讲会、学术讲座和闭幕沙龙等系列活动。文化节邀请到国家发展研究院 黄益平教授、北京大学光华管理学 刘玉珍教授等学术界专家，以“改革•创造•引领”为主题，从各个视角全面阐释中国资本市场变局动向，分析中国经济发展机遇。耀盛汇融投资管理（北京）有限公司，以承办方的身份，参与了本次文化节。
                    </p>
                </div>
            </li>

            <li>
                <div class="web-partner-list-img">
                    <img src="{{assetUrlByCdn('/static/images/new/web-partner-img8.png')}}">
                </div>
                <div class="web-partner-list-main">
                    <h3>中国金融出版社</h3>
                    <p>
                        中国金融出版社成立于1956年5月，直属中国人民银行管理，是以出版金融类图书、期刊、音像电子制品为主的专业出版社。其下期刊有《中国金融》、《金融博览》、《金融博览·财富》。其中《中国金融》杂志是中国人民银行主管、中国金融出版社主办的全国性金融政策指导类刊物，按照“权威性、政策性、实践性”的办刊方针，在中国人民银行、中国银行业监督管理委员会、中国证券监督管理委员会、中国保险监督管理委员会的指导下，以“宣传党和国家金融方针政策，探讨现实金融政策，研究实际金融问题”为主要任务，坚持“相伴金融职场，滋养从业能力”的办刊理念，服务金融改革发展、服务金融中心工作，是广大金融从业人员的日常职业读物。而《金融博览》杂志是中国人民银行主管、中国金融出版社主办的国内外公开出版的金融类期刊。《金融博览.财富》杂志，是由中国人民银行主管、中国金融出版社主办的面向市场、面向大众的金融理财期刊。
                    </p>
                </div>
            </li>

            <li>
                <div class="web-partner-list-img">
                    <img src="{{assetUrlByCdn('/static/images/new/web-partner-img4.png')}}">
                </div>
                <div class="web-partner-list-main">
                    <h3>网银在线</h3>
                    <p>
                        网银在线（北京）科技有限公司（以下简称网银在线）为京东商城（www.jd.com）全资子公司，是国内领先的电子支付解决方案提供商，专注于为各行业提供安全、便捷的综合电子支付服务。网银在线成立于2003年，现有员工200余人，由具有丰富的金融行业经验和互联网运营经验的专业团队组成，致力于通过创新型的金融服务，支持现代服务业的发展。凭借丰富的产品线、卓越的创新能力，网银在线受到各级政府部门和银行金融机构的高度重视和认可，于2011年5月3日首批荣获央行《支付业务许可证》，并任中国支付清算协会理事单位。
                    </p>
                </div>
            </li>



            {{-- <li>
                <div class="web-partner-list-img">
                    <img src="{{assetUrlByCdn('/static/images/new/web-partner-img6.png')}}">
                </div>
                <div class="web-partner-list-main">
                    <h3>连连支付</h3>
                    <p>
                        连连银通电子支付有限公司是浙江省级高新企业，成立于2003年，注册资金1.05亿元。公司致力于通过互联网和移动手机等渠道为广大用户和商户提供第三方支付和结算服务。
                        目前，连连支付已为多数商户提供了安全支付解决方案。公司拥有由互联网行业资深工作者、优秀金融界人士、高级技术人员及专职客服人员所组成的专业管理团队，在产品开发、技术创新、市场开拓、企业管理、反洗钱等方面都积累了丰富的实战经验。
                    </p>
                </div>
            </li>

            <li>
                <div class="web-partner-list-img">
                    <img src="{{assetUrlByCdn('/static/images/new/web-partner-img5.png')}}">
                </div>
                <div class="web-partner-list-main">
                    <h3>易宝支付</h3>
                    <p>
                        易宝于2003年8月成立，作为互联网金融专家，2005年便首创了行业支付模式，为产业转型及行业变革做出了积极贡献。成立12年来，易宝服务的商家超过100万，其中包括百度、京东、乐视网、360、完美世界、中国联通、中国移动、联想、中粮、中国国际航空公司、中国南方航空公司、中国东方航空公司、中国人民财产保险、阳光保险等知名企业和机构，并长期与中国工商银行、中国农业银行、中国银行、中国建设银行、中国银联、Visa、MasterCard等近百家金融机构达成战略合作关系，年交易规模达1万亿，收入达35亿元。
                    </p>
                </div>
            </li> --}}


        </ul>
    </div>
    <div class="web-partner-email">
        <p>商务合作，请联系</p>
        <h4>business@9douyu.com</h4>
    </div>
@endsection