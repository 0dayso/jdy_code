@extends('pc.common.layout')
@section('title', $title)
@section('keywords', $keywords)
@section('description', $description)
@section('content')
    @include('pc.about.common.menu')

    {{-- <div class="ys-banner">
        <img src="{{assetUrlByCdn('/static/images/new/about/ys-banner-replace-1.jpg')}}" width="100%" />
    </div> --}}

    <h3 class="ys-inner-box ys-bt">走进耀盛大家庭<span></span>投资本该很安心</h3>
    <div class="ys-bt-line"></div>

    <div class="t-wrap ys-bgcolor">
        <div class="ys-inner-box ys-summary">11年<span>/</span>1项核心技术<span>/</span>8万家小微<span>/</span>300亿价值</div>
        <div class="ys-box ys-article">
            <p>耀盛中国，一家成立于 2006 年的综合化现代金融服务集团。</p>
            <p>十一年来，我们深耕“中小企业金融生态圈”，已建立起扎实、完整的中小企业金融服务体系，全心全意为中小企业提供系统化、定制化的金融服务解决方案，用心服务中小企业从初创、成长、成熟的全生命周期。</p>
            <p>我们自主掌握着中国中小企业信用评价的核心系统——瑞思科雷RISKCALC中小企业信用评级专利技术(专利号：2014sr137684)，并运用十一年积累的移动互联网、大数据、云计算、机器学习、人工智能等多项金融科技成果，向中小微企业提供了多点接触、一站满足的完整金融服务平台，切实解决着中国中小企业融资难、 融资贵的大问题。</p>
            <p>我们服务的重点行业涉及大消费、大健康、大文化、互联网四个领域,累计服务超过8万余家中小企业, 年均创造经济总产值超 300 亿元。</p>
            <p>耀盛中国业务涵盖企业征信、小额信贷、商业保理、融资租赁、股权投资、互联网金融、消费金融、电影金融、海外投资、智能支付管理等多业务板块。集团旗下包括耀盛商业保理有限公司、耀江融资租赁有限公司、北京耀盛小额贷款有限公司、北京汉泰基金管理中心、耀盛影业有限公司、瑞思科雷征信有限公司、星果时代信息技术有限公司、星果科技有限公司、北京耀汉网络科技有限公司<!-- 、耀盛财富管理有限公司 -->等定位清晰、运营良好的商业主体。</p>
        </div>
    </div>

    <h3 class="ys-inner-box ys-bt">协同创新<span></span>群星闪耀</h3>

    <div class="Js_tab_box">
        <div class="ys-inner-box1 ys-tab-box">
            <ul class="Js_tab ys-tab clearfix">
                <li  class="cur">
                    <a href="javascript:;">战略控股</a>
                </li>
                <li>
                    <a href="javascript:;">投资板块</a>
                </li>
                <li>
                    <a href="javascript:;">金融科技板块</a>
                </li>
                <!-- <li>
                    <a href="javascript:;">融资板块</a>
                </li> -->
            </ul>
        </div>

        <div class="js_tab_content t-wrap ys-bgcolor">
            <div class="Js_tab_main">
                <div class="ys-box">
                    <div class="ys-article-head clearfix">
                        <div class="ys-article-bt fl">
                            <h6>耀盛投资管理集团<span>资本金4.9亿</span></h6>
                            <h4>中小企业金融服务一体化平台</h4>
                        </div>
                        {{--以下的标签 none 了 是要页面显示一个链接但是要显示多张图片 所以不要注释也不要删除--}}
                        <a rel="example_group1" href="{{assetUrlByCdn('/static/images/new/about/ys-tz1.jpg')}}" class="ys-checkout fr">点击查看资质证书</a>
                        <a rel="example_group1" href="{{assetUrlByCdn('/static/images/new/about/ys-tz2.jpg')}}" class="ys-checkout fr none">点击查看资质证书</a>
                    </div>

                    <div class="ys-article">
                        <p>集团负责战略规划、前瞻布局、推动业务协同，打造了一批有影响力的优秀企业，实现了各板块价值的持续增长。</p>
                        <p>“耀盛投资”为中国领先的大型多元化投资集团，构建起了“战略投资 + 财务投资”双轮业务协同驱动的创新商业模式，打造价值不断成长的投资组合。</p>
                        <p>“耀盛投资”的战略投资业务分布于金融服务、IT、创新消费、现代服务、医疗健康、文化创意等主要板块；财务投资业务主要包括风险投资及私募股权投资，覆盖企业成长的所有阶段。</p>
                    </div>


                </div>

            </div>
            <div class="Js_tab_main none">
                <div class="ys-box">
                    <div class="ys-box">
                        {{--耀盛商业保理有限公司--}}
                        <div class="ys-article-head clearfix">
                            <div class="ys-article-bt fl">
                                <h6>耀盛商业保理有限公司<span>资本金2亿</span></h6>
                                <h4>专注供应链金融丨1+N模式为中小企业融资输出新思维</h4>
                            </div>
                        </div>

                        <div class="ys-article">
                            <p>耀盛保理于2014年1月经国家工商总局、深圳市前海管理局核准，在深圳市前海深港合作区成立，注册资本2亿元，是国内最大的非银行独立商业保理公司之一。</p>
                            <p>在当下的供应链金融体系里，依旧有很多中间需求没有被满足，而耀盛保理已充分运用多种好的金融工具优化供应链、再造供应链，提升中小微资金使用效率，并降低运营成本。</p>
                            <p>耀盛保理的资挖掘主要是偏向供应链融资，偏向电商行业，偏向以大数据为经营决策依据的商业保理项目。在做供应链产品过程中，可以清楚地知道借款企业上下游的真实交易情况，有效降低了项目风险。</p>
                        </div>

                        <div class="ys-article-hr"></div>

                        {{--北京耀盛小额贷款有限公司--}}
                        <div class="ys-article-head clearfix">
                            <div class="ys-article-bt fl">
                                <h6>北京耀盛小额贷款有限公司<span>资本金1亿</span></h6>
                                <h4>专注小额信贷丨向中小企业提供定制化金融服务</h4>
                            </div>
                            <a rel="example_group2" href="{{assetUrlByCdn('/static/images/new/about/ys-xd.jpg')}}" class="ys-checkout fr">点击查看资质证书</a>
                        </div>

                        <div class="ys-article">
                            <p>耀盛小贷于2016年4月经北京市金融工作局批准成立，以金融科技的作业方式、细分市场的创新服务场景见长，正成为小贷行业的领跑者。</p>
                            <p>耀盛小贷是一家“创新驱动、科技引领、特色鲜明、惠民利民”的小额贷款公司。我们面向传统商业银行不能覆盖、也无法有效服务的中小微企业客户，定制化地提供创新的小贷产品与服务，有效解决了中小微企业小额、分散、快速的资金需求，更“破例”成为一家民营机构全资控股的小额贷款公司。</p>
                        </div>

                        <div class="ys-article-hr"></div>

                        {{--耀江融资租赁有限公司--}}
                        <div class="ys-article-head clearfix">
                            <div class="ys-article-bt fl">
                                <h6>耀江融资租赁有限公司<span>资本金3000万美元</span></h6>
                                <h4>专注细分行业丨让中小企业以“融物”来“融资”</h4>
                            </div>
                            <a rel="example_group3" href="{{assetUrlByCdn('/static/images/new/about/ys-zl.jpg')}}" class="ys-checkout fr">点击查看资质证书</a>
                        </div>

                        <div class="ys-article">
                            <p>耀江租赁经天津市商委批准，成立于2015年1月，位于天津东疆保税港区，是国内规模较大的外商投资融资租赁公司。</p>
                            <p>耀江租赁以融资租赁、融资担保、股权投资等为一体的集团多品种业务优势，积极致力于船舶、装备制造、节能环保、电信、能源、交通等行业的融资租赁业务的拓展。</p>
                            <p>耀江租赁更在积极布局医疗健康类租赁资产，我们相信伴随着中国二胎政策的放开，医疗健康产业将是不断上升的产业。</p>
                        </div>

                        <div class="ys-article-hr"></div>

                        {{--北京汉泰基金管理中心--}}
                        <div class="ys-article-head clearfix">
                            <div class="ys-article-bt fl">
                                <h6>北京汉泰基金管理中心（有限合伙）<span>资本金500万元</span></h6>
                                <h4>专注成长型中小企业丨与中小企业共担创业风险</h4>
                            </div>
                            {{--以下的标签 none 了 是要页面显示一个链接但是要显示多张图片 所以不要注释也不要删除--}}
                            <a rel="example_group4" href="{{assetUrlByCdn('/static/images/new/about/ys-ht1.jpg')}}" class="ys-checkout fr">点击查看资质证书</a>
                            <a rel="example_group4" href="{{assetUrlByCdn('/static/images/new/about/ys-ht2.jpg')}}" class="ys-checkout fr none">点击查看资质证书</a>

                        </div>

                        <div class="ys-article">
                            <p>汉泰基金成立于2015年5月，为专业从事针对中小微企业的私募股权投资基金。</p>
                            <p>汉泰基金于2016年1月获得中国证券投资基金业协会所颁发的《私募投资基金管理人登记证明》，经营范围包括非证券业务的投资管理、咨询，股权投资管理。</p>
                            <p>汉泰基金通过严谨的研究发现市场投资机会；通过专业的风险管理体系、分散化投资提高风险防御能力；通过科学有效的投资流程获得优质中小微企业长期的资产增值。</p>
                            <p>汉泰基金重视通过长期、稳健的价值发现为投资创造价值，承担受托人职责，以长期效益的方式为出借人谋福利。汉泰基金为出借人提供包括主动投资、被动投资、量化投资、固定收益投资、特定资产管理等在内的多元资产管理服务，力求全面满足出借人的财富管理需求。</p>
                            <p>汉泰基金致力于发展成为厚重丰满的投资机构，让更多人共享经济发展成果。</p>
                        </div>

                        <div class="ys-article-hr"></div>

                        {{--耀盛影业有限公司--}}
                        <div class="ys-article-head clearfix">
                            <div class="ys-article-bt fl">
                                <h6>耀盛影业有限公司（有限合伙）<span>资本金6000万元</span></h6>
                                <h4>科技+艺术+资本丨为文创型中小企业保驾护航</h4>
                            </div>
                            <a rel="example_group5" href="{{assetUrlByCdn('/static/images/new/about/ys-yy.jpg')}}" class="ys-checkout fr">点击查看资质证书</a>
                        </div>

                        <div class="ys-article">
                            <p>耀盛影业于2015年6月经国家新闻出版广电总局批复而正式成立，并获得了广播电视、电影、院线等产品服务的经营许可。</p>
                            <p>耀盛影业致力于为中国电影业增添一个健康与完整的电影投资、制作、发行的金融保障体系。“耀盛影业”正充分发挥专门服务于影视产业的金融平台属性，在中国电影逐渐走向产业化的道路上，促进创作、确保质量、规避风险。</p>
                            <p>在“互联网+” 的大创意时代，耀盛影业力图打造一种全新的商业模式——使前沿的科技、前卫的艺术便捷地与资本对接，让每一个有才华、有梦想的年轻人实现自由连接。</p>
                            <p>耀盛影业通过市场打通影视和金融之间的需求，为广大新兴富裕阶层输出更多样、更优化的投资服务。在电影金融领域，创新性地引领一种消费与投资的新常态，使闲散资金将发挥能量，让投资理财、文娱消费形成良性循环。</p>
                        </div>
                    </div>
                </div>

            </div>


            <div class="Js_tab_main none">
                <div class="ys-box">
                    <div class="ys-box">
                        {{--瑞思科雷征信有限公司--}}
                        <div class="ys-article-head clearfix">
                            <div class="ys-article-bt fl">
                                <h6>瑞思科雷征信有限公司<span>资本金6000万元</span></h6>
                                <h4>自主核心技术丨清晰描绘中小企业“信用身份证”</h4>
                            </div>
                            <a rel="example_group6" href="{{assetUrlByCdn('/static/images/new/about/ys-rsk1.jpg')}}" class="ys-checkout fr">点击查看资质证书</a>
                            {{--以下的标签 none 了 是要页面显示一个链接但是要显示多张图片 所以不要注释也不要删除--}}
                            <a rel="example_group6" href="{{assetUrlByCdn('/static/images/new/about/ys-rsk2.jpg')}}" class="ys-checkout fr none">点击查看资质证书</a>
                            <a rel="example_group6" href="{{assetUrlByCdn('/static/images/new/about/ys-rsk3.jpg')}}" class="ys-checkout fr none">点击查看资质证书</a>

                        </div>

                        <div class="ys-article">
                            <p>瑞思科雷成立于2014年9月，2015年11月获得中国人民银行颁发的《中华人民共和国企业征信业务经营备案证》。</p>
                            <p>瑞思科雷自主掌握着一项核心专利技术——瑞思科雷RISKCALC中小企业信用评级技术。该技术的主要功能在于判断中小企业真实的资产负债比，清晰描绘出了一张张中小企业“信用身份证”。</p>
                            <p>瑞思科雷RISKCALC中小企业信用评级技术是在耀盛中国在金融风险管理理论基础上，根据数学计量方法和金融风险管理模型对中小企业信用风险进行建模分析，最终形成的一套中小企业信用评价体系。2010年该技术问世以来，确立了耀盛中国在中小企业信用风险评级领域的行业领先地位。</p>
                            <p>目前，九斗鱼对外输出的所有项目均经过瑞思科雷全程严格的风控把关，以确保为出借人提供优质、安全的资产。</p>
                        </div>

                        <div class="ys-article-hr"></div>

                        {{--九斗鱼--}}
                        <div class="ys-article-head clearfix">
                            <div class="ys-article-bt fl">
                                <h6>九斗鱼<span>资本金6000万元</span></h6>
                                <h4>倡导共享金融丨让老百姓分享优质中小企业的发展红利</h4>
                            </div>
                        </div>

                        <div class="ys-article">
                            <p>详见九斗鱼官网“关于我们”</p>
                        </div>

                        <div class="ys-article-hr"></div>

                        {{--快金--}}
                        <div class="ys-article-head clearfix">
                            <div class="ys-article-bt fl">
                                <h6>快金<span>资本金5000万</span></h6>
                                <h4>专注消费金融丨为年轻人释放信用的力量</h4>
                            </div>
                        </div>

                        <div class="ys-article">
                            <p>快金，商业主体为星果科技有限公司，于2015年8月在深圳福田区成立，为耀盛中国深耕“大消费金融”领域的移动互联网金融服务平台。快金，致力于快速连接与响应2亿中国年轻人多样的、新颖的、个性的信用消费需求</p>
                            <p>快金，深谙互联网+金融的聚合作用，充分释放出信用力量，将信用轻松、快捷地转化为财富与资源，助力每一个正青春、爱折腾、有梦想、不等待的年轻人绽放各自的生命力。</p>
                            <p>快金相信信用是数据连接的本质、商业文明的基石；更是个体安身立命的支撑、美好社会的原点。</p>
                            <p>快金，是新技术，其基于“互联网+”时代的海量信用信息，通过云计算、大数据和机器学习等现代技术，为每一位没有信用记录的个体建立信用。当每一位信用合格的年轻人有资金需要时，为提供快速授信、快速放款、灵活还款的服务。</p>
                            <p>快金，更是新思维，其领先的信息技术、扁平的组织管理、有效的运营方法，让快金比银行信用卡门槛低、速度快、额度高、费用省，帮年轻人及时将信用转化为财富，让未来的自己帮助现在的自己。</p>
                        </div>

                        <div class="ys-article-hr"></div>

                        {{--普付宝--}}
                        <div class="ys-article-head clearfix">
                            <div class="ys-article-bt fl">
                                <h6>普付宝<span>资本金500万元</span></h6>
                                <h4>让小生意有大未来丨用金融科技提升小微商户综合经营能力</h4>
                            </div>
                        </div>

                        <div class="ys-article">
                            <p>普付宝用互联网+金融的新工具、新理念来助力小微商户的转型升级，用互联网工具真正去提高小微商户的经营效率，用普惠金融理念切实去解决资金痛点。</p>
                            <p>普付宝，不论是强大的聚合支付、订单管理、会员管理、卡券营销、微信营销能力，还是后台的销售数据管理、交易行为分析、消费者数据分析等服务，都让小微商户切实感受到“智慧经营”的魅力；其以几近“全能”的增值服务与营销管理功能，真正将实体商户与互联网连接，打造全新的商业生态闭环。</p>
                            <p>面对小微企业轻管理、简流程、重结果的管理需求，普付宝会是小微商户的“最忠实员工”，可以成为小微商户的专属收银员、出纳、会计、HR、社保专员乃至法务顾问、财务顾问。</p>
                            <p>普付宝运用供应链金融、互联网金融、消费金融、智能支付、金融科技等新工具，一个“界面”为小微商户全面打造三大金融价值“模块”——小额信贷与供应链融资平台、消费金融服务平台、投资与理财服务平台。</p>
                            <p>普付宝创新服务模式、研发定制产品，致力于全方位帮助小微商户解决市场营销、客户管理、资金需求、财务税务、企业管理、日常运营等问题与痛点。</p>
                        </div>

                    </div>
                </div>

            </div>


           {{--  <div class="Js_tab_main none">
                <div class="ys-box">
                    <div class="ys-box">
                        <!--耀盛财富-->
                       <div class="ys-article-head clearfix">
                            <div class="ys-article-bt fl">
                                <h6>耀盛财富<span>资本金6000万元</span></h6>
                                <h4>定义高端丨创造财富，更分享价值</h4>
                            </div>
                        </div>

                        <div class="ys-article">
                            <p>耀盛财富的经营主体为耀盛财富管理有限公司，注册资本6000万元，是耀盛中国旗下独立财富管理业务品牌，致力于为数十万名中国高净值出借人提供专业的资产配置服务。</p>
                            <p>耀盛财富一直致力于为客户提供优秀且与其资产和需求高度匹配的资产类别，包含固定收益、私募股权、资本市场、保险保障等全方位理财产品与服务，为客户提供财富保值、增值全方位解决方案。</p>
                            <p>耀盛财富通过自主开发产品、引入外部产品以及与国内外优秀的投资机构合作设计产品的多种形式，为客户提供优质投资标的，帮助客户享受权益类投资、另类投资、固定收益等领域创造的红利。</p>
                            <p>耀盛财富的核心管理人员由十余名资产配置专家组成，均在各自研究、工作的领域有所建树，涉及领域包括固定收益、私募股权、资本市场、家族办公室、企业财务、资产证券化、保险等多个方面，用专业精神服务于客户的全方位资产配置需求。</p>
                        </div>
                    </div>
                </div>

            </div> --}}



        </div>

    </div>


@endsection
@section('jspage')
    <script type="text/javascript">

        $(function(){
            //点击查看资质证书



            for(var i=1;i<=6;i++){
                $("a[rel=example_group"+i+"]").fancybox({
                    'transitionIn'      : 'none',
                    'transitionOut'     : 'none',
                    'titlePosition'     : 'over',
                });
            }

        })

    </script>
@endsection
